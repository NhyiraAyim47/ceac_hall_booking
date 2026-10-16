<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Mailer;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/** Admin > Manage Bookings (list, filters, delete, reply by email). */
class BookingController extends Controller
{
    public function index(Request $request)
    {
        // ── Soft delete / permanent delete (links carry a CSRF token) ──
        if ($request->has('soft_delete') && $this->validGetToken($request)) {
            $did = (int) $request->query('soft_delete');
            Sql::execute("UPDATE bookings SET deleted_at=NOW() WHERE id=?", [$did]);
            adminLog('delete_booking', "Soft deleted booking ID $did");
            return redirect('/admin/bookings');
        }
        if ($request->has('hard_delete') && $this->validGetToken($request)) {
            $did = (int) $request->query('hard_delete');
            Sql::execute("DELETE FROM bookings WHERE id=?", [$did]);
            adminLog('hard_delete_booking', "Permanently deleted booking ID $did");
            return redirect('/admin/bookings');
        }

        // ── Filters ──────────────────────────────────────────────
        $status  = Security::sanitize($request->query('status'));
        $search  = Security::sanitize($request->query('search'));
        $dateF   = Security::sanitize($request->query('date_from'));
        $dateT   = Security::sanitize($request->query('date_to'));
        $page    = max(1, (int) $request->query('page', 1));
        $perPage = 15;
        $offset  = ($page - 1) * $perPage;

        $where  = 'b.deleted_at IS NULL';
        $params = [];
        if ($status) {
            $where .= " AND b.status = ?";
            $params[] = $status;
        }
        if ($search) {
            $where .= " AND (b.full_name LIKE ? OR b.email LIKE ? OR b.booking_ref LIKE ? OR b.event_title LIKE ?)";
            $s = "%$search%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if ($dateF) {
            $where .= " AND b.event_date >= ?";
            $params[] = $dateF;
        }
        if ($dateT) {
            $where .= " AND b.event_date <= ?";
            $params[] = $dateT;
        }

        $total      = Sql::fetch("SELECT COUNT(*) AS c FROM bookings b WHERE $where", $params)['c'] ?? 0;
        $bookings   = Sql::fetchAll(
            "SELECT b.*, h.name AS hall_name, h.pricing_json
             FROM bookings b LEFT JOIN halls h ON b.hall_id=h.id
             WHERE $where ORDER BY b.created_at DESC LIMIT $perPage OFFSET $offset",
            $params
        );
        $totalPages = ceil($total / $perPage);

        return view('admin.bookings', [
            'pageTitle'     => 'Manage Bookings',
            'status'        => $status,
            'search'        => $search,
            'dateF'         => $dateF,
            'dateT'         => $dateT,
            'page'          => $page,
            'total'         => $total,
            'bookings'      => $bookings,
            'totalPages'    => $totalPages,
            'pendingCount'  => Sql::fetch("SELECT COUNT(*) AS c FROM bookings WHERE status='pending' AND deleted_at IS NULL")['c'] ?? 0,
            'approvedCount' => Sql::fetch("SELECT COUNT(*) AS c FROM bookings WHERE status='approved' AND deleted_at IS NULL")['c'] ?? 0,
        ]);
    }

    /** POST /admin/bookings (reply_email_send) - send an email reply to a booker. */
    public function reply(Request $request)
    {
        if (!$request->has('reply_email_send')) {
            return redirect('/admin/bookings');
        }

        $rTo      = Security::sanitize($request->input('reply_to'));
        $rName    = Security::sanitize($request->input('reply_to_name'));
        $rSubject = Security::sanitize($request->input('reply_subject'));
        $rBody    = Security::sanitize($request->input('reply_body'));

        try {
            Mailer::sendHtml($rTo, $rName, $rSubject, Mailer::styledReply($rName, $rBody), $rBody, true);
            return redirect('/admin/bookings')->with('reply_result', 'sent');
        } catch (\Throwable $e) {
            report($e);
            return redirect('/admin/bookings')
                ->with('reply_result', 'failed')
                ->with('reply_error_detail', $e->getMessage());
        }
    }
}
