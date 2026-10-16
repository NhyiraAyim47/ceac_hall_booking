<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Mailer;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/**
 * Approve / reject / cancel / complete a booking and email the booker.
 *   GET /admin/booking_action?id=..&action=..&csrf_token=..[&reason=..]
 */
class BookingActionController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        $id      = (int) $request->query('id', 0);
        $action  = Security::sanitize($request->query('action'));
        $allowed = ['approve', 'reject', 'cancel', 'complete'];

        if (!$id || !in_array($action, $allowed)) {
            return redirect('/admin/bookings');
        }

        // These links change data, so they must carry the session's CSRF token.
        if (!$this->validGetToken($request)) {
            return redirect('/admin/bookings');
        }

        $booking = Sql::fetch(
            "SELECT b.*, h.name AS hall_name FROM bookings b LEFT JOIN halls h ON b.hall_id = h.id WHERE b.id=?",
            [$id]
        );
        if (!$booking) {
            return redirect('/admin/bookings?msg=not_found');
        }
        $hallName = $booking['hall_name'] ?? 'CEAC Hall';

        // Block approval if it overlaps an already-approved booking
        if ($action === 'approve') {
            $conflict = Sql::fetch(
                "SELECT booking_ref, full_name, start_time, end_time
                 FROM bookings
                 WHERE hall_id = ? AND event_date = ? AND status = 'approved' AND id != ? AND deleted_at IS NULL
                   AND start_time < ? AND end_time > ?
                 LIMIT 1",
                [$booking['hall_id'], $booking['event_date'], $id, $booking['end_time'], $booking['start_time']]
            );

            if ($conflict) {
                $params = http_build_query([
                    'id'             => $id,
                    'conflict'       => 1,
                    'conflict_ref'   => $conflict['booking_ref'],
                    'conflict_name'  => $conflict['full_name'],
                    'conflict_start' => $conflict['start_time'],
                    'conflict_end'   => $conflict['end_time'],
                ]);
                return redirect("/admin/booking_detail?{$params}");
            }
        }

        $newStatus = [
            'approve'  => 'approved',
            'reject'   => 'rejected',
            'cancel'   => 'cancelled',
            'complete' => 'completed',
        ][$action];

        // Reason (reject / cancel) is stored in admin_notes
        $reason = Security::sanitize($request->query('reason'));

        Sql::execute(
            "UPDATE bookings SET status=?, admin_notes=CASE WHEN ? != '' THEN ? ELSE admin_notes END, updated_at=NOW() WHERE id=?",
            [$newStatus, $reason, $reason, $id]
        );

        adminLog("booking_{$action}", "Booking {$booking['booking_ref']} set to {$newStatus}");

        // ── Email the booker ─────────────────────────────────────
        $dateTs  = !empty($booking['event_date']) ? strtotime($booking['event_date']) : 0;
        $startTs = !empty($booking['start_time']) ? strtotime($booking['start_time']) : 0;
        $endTs   = !empty($booking['end_time']) ? strtotime($booking['end_time']) : 0;

        $formattedDate  = ($dateTs && $dateTs > 0) ? date('l, F j, Y', $dateTs) : 'Not specified';
        $formattedStart = ($startTs && $startTs > 0) ? date('g:i A', $startTs) : 'Not specified';
        $formattedEnd   = ($endTs && $endTs > 0) ? date('g:i A', $endTs) : 'Not specified';
        $ref            = $booking['booking_ref'];
        $fullName       = $booking['full_name'];
        $eventTitle     = $booking['event_title'];

        $subject = match ($action) {
            'approve'  => "✅ Booking Approved – {$ref}",
            'reject'   => "❌ Booking Request Declined – {$ref}",
            'cancel'   => "⚠️ Booking Cancelled – {$ref}",
            'complete' => "🎉 Booking Completed – {$ref}",
        };
        $statusColor = match ($action) {
            'approve'  => '#10B981',
            'reject'   => '#EF4444',
            'cancel'   => '#F59E0B',
            'complete' => '#6366F1',
        };
        $statusLabel = match ($action) {
            'approve'  => 'APPROVED',
            'reject'   => 'DECLINED',
            'cancel'   => 'CANCELLED',
            'complete' => 'COMPLETED',
        };

        try {
            $html = view('emails.booking-status', compact(
                'action', 'statusColor', 'statusLabel', 'hallName', 'ref', 'fullName', 'eventTitle',
                'formattedDate', 'formattedStart', 'formattedEnd', 'reason', 'newStatus'
            ))->render();

            Mailer::sendHtml(
                $booking['email'],
                $booking['full_name'],
                $subject,
                $html,
                "Dear {$fullName}, your booking {$ref} for {$eventTitle} on {$formattedDate} has been {$newStatus}. Contact us at " . config('ceac.phone') . " for enquiries."
            );
        } catch (\Throwable $e) {
            report($e);   // a mail problem must not undo the status change
        }

        return redirect("/admin/booking_detail?id={$id}&action={$action}_done");
    }
}
