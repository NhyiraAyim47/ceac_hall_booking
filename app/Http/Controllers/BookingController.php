<?php

namespace App\Http\Controllers;

use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Public booking endpoints used by the booking modal (assets/js/main.js):
 *   POST /booking/submit          -> submit()
 *   GET  /api/check-availability  -> availability()
 *
 * CSRF is checked by Laravel's VerifyCsrfToken middleware (the form carries @csrf).
 */
class BookingController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $ip = Security::getClientIp($request);

        // IP block check
        if (Security::isIpBlocked($ip)) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        // Rate limit (the original script forgot to stop here - now it does)
        if (!Security::checkRateLimit($ip, 'booking', config('ceac.rate_limit_booking'), config('ceac.rate_limit_window'))) {
            return response()->json(['success' => false, 'message' => 'Too many booking requests from your network. Please wait a few minutes and try again.']);
        }

        // ── Sanitise ─────────────────────────────────────────────
        $hallId         = (int) $request->input('hall_id', 1);
        $fullName       = Security::sanitize($request->input('full_name'));
        $email          = Security::sanitize($request->input('email'));
        $phone          = Security::sanitize($request->input('phone'));
        $org            = Security::sanitize($request->input('organization'));
        $eventTitle     = Security::sanitize($request->input('event_title'));
        $eventType      = 'meetings';
        $otherType      = '';
        $eventTypeLabel = 'Meetings';

        // Combined datetime fields (optional)
        $startDatetime = Security::sanitize($request->input('start_datetime'));
        $endDatetime   = Security::sanitize($request->input('end_datetime'));
        $startTs       = !empty($startDatetime) ? strtotime($startDatetime) : null;
        $endTs         = !empty($endDatetime) ? strtotime($endDatetime) : null;
        $eventDate     = $startTs ? date('Y-m-d', $startTs) : null;
        $startTime     = $startTs ? date('H:i:s', $startTs) : null;
        $endTime       = $endTs ? date('H:i:s', $endTs) : null;
        $startDisplay  = $startTs ? date('D, M j Y \a\t g:i A', $startTs) : 'Not specified';
        $endDisplay    = $endTs ? date('D, M j Y \a\t g:i A', $endTs) : 'Not specified';

        $attendees  = (int) $request->input('expected_attendees', 0);
        $specialReq = Security::sanitize($request->input('special_requirements'));
        $needsProj  = $request->has('needs_projector') ? 1 : 0;
        $needsPA    = $request->has('needs_pa_system') ? 1 : 0;
        $needsAC    = $request->has('needs_ac') ? 1 : 0;
        $needsWB    = $request->has('needs_whiteboard') ? 1 : 0;

        // ── Validate ─────────────────────────────────────────────
        $errors = [];
        if (strlen($fullName) < 2)            $errors[] = 'Full name is required (minimum 2 characters).';
        if (!Security::validateEmail($email)) $errors[] = 'A valid email address is required.';
        if (!Security::validatePhone($phone)) $errors[] = 'A valid phone number is required.';
        if (strlen($eventTitle) < 2)          $errors[] = 'Event title is required.';
        if ($startTs && $endTs && $startTs >= $endTs) $errors[] = 'End date & time must be after start date & time.';

        $hall     = Sql::fetch("SELECT id, name, capacity, pricing_json FROM halls WHERE id = ? AND status = 'active'", [$hallId]);
        $hallName = $hall['name'] ?? 'CEAC Hall';
        if (!$hall) $errors[] = 'The selected hall is not available.';
        $hallCapacity = $hall['capacity'] ?? 9999;
        if ($attendees < 1)             $errors[] = 'Number of attendees must be at least 1.';
        if ($attendees > $hallCapacity) $errors[] = "Number of attendees cannot exceed the hall capacity of {$hallCapacity}.";

        if (!in_array($eventType, ['meetings'])) $errors[] = 'Invalid event type selected.';

        if (!empty($errors)) {
            return response()->json(['success' => false, 'message' => implode(' ', $errors)]);
        }

        // ── Pricing from the hall's pricing_json ─────────────────
        $pricing      = !empty($hall['pricing_json']) ? (json_decode($hall['pricing_json'], true) ?: []) : [];
        $durationType = Security::sanitize($request->input('booking_duration_type'));
        if ($durationType === '') $durationType = 'half';
        $currency  = $pricing['currency'] ?? 'GHS';
        $curSymbol = $currency === 'USD' ? '$' : ($currency === 'EUR' ? '€' : '₵');

        if ($durationType === 'full') {
            $venuePrice    = (int) ($pricing['meetings_full_price'] ?? 0);
            $venueDurLabel = ($pricing['meetings_full_hours'] ?? 4) . ' hrs (Full Time)';
        } else {
            $venuePrice    = (int) ($pricing['meetings_half_price'] ?? 0);
            $venueDurLabel = ($pricing['meetings_half_hours'] ?? 2) . ' hrs (Half Time)';
        }

        $facilityLines = [];
        $facilityTotal = 0;
        $facilities = [
            [$needsProj, 'facility_projector',  'Display Screen'],
            [$needsPA,   'facility_pa_system',  'PA System'],
            [$needsAC,   'facility_ac',         'Air Conditioning'],
            [$needsWB,   'facility_whiteboard', 'Whiteboard'],
        ];
        foreach ($facilities as [$needed, $key, $label]) {
            if ($needed && !empty($pricing[$key])) {
                $amt = (int) $pricing[$key];
                $facilityLines[] = [$label, $amt];
                $facilityTotal  += $amt;
            }
        }

        $grandTotal  = $venuePrice + $facilityTotal;
        $hasPricing  = $grandTotal > 0;
        $penaltyNote = !empty($pricing['penalty_per_hour'])
            ? "A penalty of {$curSymbol}{$pricing['penalty_per_hour']}/hr applies if you exceed your booked time."
            : '';

        // Bill summary rows for the confirmation email
        $pricingRowsHtml = '';
        if ($hasPricing) {
            $pricingRowsHtml .= "<tr><td colspan='2' style='padding:10px 8px 4px;border:1px solid #eee;background:#F9FAFB;font-size:0.8rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;'>Bill Summary</td></tr>";
            if ($venuePrice > 0) {
                $pricingRowsHtml .= "<tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Venue — " . e($eventTypeLabel) . " (" . e($venueDurLabel) . ")</td><td style='padding:8px;border:1px solid #eee;'>{$curSymbol}" . number_format($venuePrice) . "</td></tr>";
            }
            foreach ($facilityLines as [$fLabel, $fAmt]) {
                $pricingRowsHtml .= "<tr><td style='padding:8px;border:1px solid #eee;color:#666;'>{$fLabel}</td><td style='padding:8px;border:1px solid #eee;'>{$curSymbol}" . number_format($fAmt) . "</td></tr>";
            }
            $pricingRowsHtml .= "<tr style='background:#FFF7ED;'><td style='padding:10px 8px;border:1px solid #FF6B00;font-weight:bold;color:#92400E;'>TOTAL ESTIMATED COST</td><td style='padding:10px 8px;border:1px solid #FF6B00;font-weight:bold;font-size:1.1rem;color:#FF6B00;'>{$curSymbol}" . number_format($grandTotal) . "</td></tr>";
            if ($penaltyNote) {
                $pricingRowsHtml .= "<tr><td colspan='2' style='padding:6px 8px;border:1px solid #eee;font-size:0.78rem;color:#9CA3AF;font-style:italic;'>" . e($penaltyNote) . "</td></tr>";
            }
        }

        // ── Conflict check + insert, serialised per hall ─────────
        // Locking the hall row means two simultaneous requests for the same
        // hall are processed one after the other, so they cannot both pass
        // the availability check (closes the race condition).
        $ref = Security::generateBookingRef();
        try {
            $conflict = DB::transaction(function () use (
                $hallId, $eventDate, $startTime, $endTime, $ref, $fullName, $email, $phone, $org, $eventTitle, $eventType,
                $otherType, $durationType, $attendees, $specialReq, $grandTotal, $currency,
                $needsProj, $needsPA, $needsAC, $needsWB, $ip, $request
            ) {
                DB::select("SELECT id FROM halls WHERE id = ? FOR UPDATE", [$hallId]);

                if ($eventDate && $startTime && $endTime) {
                    $clash = Sql::fetch(
                        "SELECT id FROM bookings
                         WHERE hall_id = ? AND event_date = ? AND deleted_at IS NULL AND status IN ('pending','approved')
                           AND start_time < ? AND end_time > ?",
                        [$hallId, $eventDate, $endTime, $startTime]
                    );
                    if ($clash) {
                        return true;
                    }
                }

                Sql::execute(
                    "INSERT INTO bookings (booking_ref, hall_id, full_name, email, phone, organization, event_title, event_type,
                     other_event_type, booking_duration_type, event_date, start_time, end_time, expected_attendees, special_requirements,
                     total_cost, currency,
                     needs_projector, needs_pa_system, needs_ac, needs_whiteboard, status, ip_address, user_agent)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                    [$ref, $hallId, $fullName, $email, $phone, $org, $eventTitle, $eventType,
                     $otherType, $durationType, $eventDate, $startTime, $endTime, $attendees, $specialReq,
                     $grandTotal, $currency,
                     $needsProj, $needsPA, $needsAC, $needsWB,
                     'pending', $ip, substr((string) $request->userAgent(), 0, 500)]
                );
                return false;
            });
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'A system error occurred. Please try again or contact us directly.']);
        }

        if ($conflict) {
            return response()->json(['success' => false, 'message' => 'The hall already has a booking during this time slot on the selected date. Please choose a different time or date.']);
        }

        // ── Confirmation email to the booker ─────────────────────
        try {
            $body = view('emails.booking-received', compact(
                'fullName', 'hallName', 'ref', 'eventTitle', 'eventTypeLabel',
                'startDisplay', 'endDisplay', 'attendees', 'pricingRowsHtml'
            ))->render();

            Mail::html($body, function ($m) use ($email, $fullName, $ref, $body) {
                $m->to($email, $fullName)
                  ->subject("Booking Request Received – {$ref}")
                  ->text(strip_tags(str_replace(['<br>', '<p>', '</p>'], "\n", $body)));
            });
        } catch (\Throwable $e) {
            report($e);   // a failed email must not fail the booking
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Booking request submitted successfully.',
            'booking_ref' => $ref,
            'email'       => htmlspecialchars($email),
        ]);
    }

    /** Live availability check used while the visitor fills in the form. */
    public function availability(Request $request): JsonResponse
    {
        $hallId   = (int) $request->query('hall_id', 0);
        $startStr = Security::sanitize($request->query('start_datetime'));
        $endStr   = Security::sanitize($request->query('end_datetime'));

        $startTs = $startStr ? strtotime($startStr) : null;
        $endTs   = $endStr ? strtotime($endStr) : null;

        if (!$hallId || !$startTs || !$endTs || $startTs >= $endTs) {
            return response()->json(['available' => true, 'checked' => false]);
        }

        $conflict = Sql::fetch(
            "SELECT booking_ref FROM bookings
             WHERE hall_id = ? AND event_date = ? AND deleted_at IS NULL AND status IN ('pending','approved')
               AND start_time < ? AND end_time > ?
             LIMIT 1",
            [$hallId, date('Y-m-d', $startTs), date('H:i:s', $endTs), date('H:i:s', $startTs)]
        );

        return response()->json(['available' => !$conflict, 'checked' => true]);
    }
}
