<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Mailer;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/** Admin > Booking detail page: view, save notes, reply to the booker. */
class BookingDetailController extends Controller
{
    public function show(Request $request)
    {
        $id      = (int) $request->query('id', 0);
        $booking = $this->find($id);

        if (!$booking) {
            return view('admin.booking_missing', ['pageTitle' => 'Booking Detail']);
        }

        $savedAction    = Security::sanitize($request->query('action'));
        $actionMessages = [
            'approve_done'  => ['type' => 'success', 'msg' => 'Booking approved successfully. Confirmation email sent to booker.'],
            'reject_done'   => ['type' => 'danger',  'msg' => 'Booking rejected. The booker has been notified.'],
            'cancel_done'   => ['type' => 'warning', 'msg' => 'Booking cancelled. The booker has been notified.'],
            'complete_done' => ['type' => 'info',    'msg' => 'Booking marked as completed.'],
        ];

        return view('admin.booking_detail', [
            'pageTitle'      => 'Booking Detail',
            'id'             => $id,
            'booking'        => $booking,
            'savedAction'    => $savedAction,
            'actionMessages' => $actionMessages,
            'hasConflict'    => $request->has('conflict') && $request->query('conflict') == '1',
            'conflictRef'    => Security::sanitize($request->query('conflict_ref')),
            'conflictName'   => Security::sanitize($request->query('conflict_name')),
            'conflictStart'  => Security::sanitize($request->query('conflict_start')),
            'conflictEnd'    => Security::sanitize($request->query('conflict_end')),
        ]);
    }

    /** POST: either "reply by email" or "save admin notes". */
    public function update(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        $id = (int) $request->query('id', 0);

        // Reply email
        if ($request->has('reply_email_send')) {
            $rTo      = Security::sanitize($request->input('reply_to'));
            $rName    = Security::sanitize($request->input('reply_to_name'));
            $rSubject = Security::sanitize($request->input('reply_subject'));
            $rBody    = Security::sanitize($request->input('reply_body'));

            try {
                Mailer::sendHtml($rTo, $rName, $rSubject, Mailer::styledReply($rName, $rBody), $rBody, true);
                $flash = ['reply_result' => 'sent'];
            } catch (\Throwable $e) {
                report($e);
                $flash = ['reply_result' => 'failed', 'reply_error_detail' => $e->getMessage()];
            }
            return redirect("/admin/booking_detail?id={$id}")->with($flash);
        }

        // Save admin notes
        if ($this->find($id)) {
            Sql::execute("UPDATE bookings SET admin_notes=? WHERE id=?", [Security::sanitize($request->input('admin_notes')), $id]);
        }
        return redirect("/admin/booking_detail?id={$id}&saved=1");
    }

    private function find(int $id): ?array
    {
        return Sql::fetch(
            "SELECT b.*, h.name AS hall_name FROM bookings b LEFT JOIN halls h ON b.hall_id=h.id WHERE b.id=?",
            [$id]
        );
    }
}
