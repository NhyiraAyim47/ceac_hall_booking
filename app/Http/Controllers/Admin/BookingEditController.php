<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/** Admin > Edit booking details. */
class BookingEditController extends Controller
{
    public function edit(Request $request)
    {
        $id      = (int) $request->input('id', $request->query('id', 0));
        $booking = Sql::fetch("SELECT * FROM bookings WHERE id = ?", [$id]);
        if (!$booking) {
            return redirect('/admin/bookings');
        }

        return view('admin.booking_edit', [
            'pageTitle'  => 'Edit Booking',
            'id'         => $id,
            'booking'    => $booking,
            'formErrors' => [],
        ]);
    }

    public function update(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        $id      = (int) $request->input('id', $request->query('id', 0));
        $booking = Sql::fetch("SELECT * FROM bookings WHERE id = ?", [$id]);
        if (!$booking) {
            return redirect('/admin/bookings');
        }

        $fullName   = Security::sanitize($request->input('full_name'));
        $email      = Security::sanitize($request->input('email'));
        $phone      = Security::sanitize($request->input('phone'));
        $org        = Security::sanitize($request->input('organization'));
        $eventTitle = Security::sanitize($request->input('event_title'));
        $attendees  = (int) $request->input('expected_attendees', 0);
        $specialReq = Security::sanitize($request->input('special_requirements'));
        $startTs    = strtotime(Security::sanitize($request->input('start_datetime')));
        $endTs      = strtotime(Security::sanitize($request->input('end_datetime')));

        $errors = [];
        if (strlen($fullName) < 2)            $errors[] = 'Full name is required.';
        if (!Security::validateEmail($email)) $errors[] = 'A valid email is required.';
        if (!$startTs || !$endTs || $startTs >= $endTs) $errors[] = 'Valid start and end times are required.';

        if (empty($errors)) {
            $eventDate = date('Y-m-d', $startTs);
            $startTime = date('H:i:s', $startTs);
            $endTime   = date('H:i:s', $endTs);

            // Re-check for conflicts with OTHER approved bookings before saving
            $conflict = Sql::fetch(
                "SELECT booking_ref FROM bookings
                 WHERE hall_id = ? AND event_date = ? AND status = 'approved' AND id != ? AND deleted_at IS NULL
                   AND start_time < ? AND end_time > ?
                 LIMIT 1",
                [$booking['hall_id'], $eventDate, $id, $endTime, $startTime]
            );

            if ($conflict) {
                $errors[] = "This time conflicts with approved booking {$conflict['booking_ref']}. Adjust the time or resolve that booking first.";
            } else {
                Sql::execute(
                    "UPDATE bookings SET full_name=?, email=?, phone=?, organization=?, event_title=?,
                     expected_attendees=?, special_requirements=?, event_date=?, start_time=?, end_time=?
                     WHERE id=?",
                    [$fullName, $email, $phone, $org, $eventTitle, $attendees, $specialReq,
                     $eventDate, $startTime, $endTime, $id]
                );
                adminLog('booking_edit', "Booking {$booking['booking_ref']} details edited by admin");
                return redirect("/admin/booking_detail?id={$id}&edited=1");
            }
        }

        return view('admin.booking_edit', [
            'pageTitle'  => 'Edit Booking',
            'id'         => $id,
            'booking'    => $booking,
            'formErrors' => $errors,
        ]);
    }
}
