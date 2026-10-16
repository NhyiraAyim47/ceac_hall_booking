<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/** Admin > Payments: record a payment against a booking, and the analytics page. */
class PaymentController extends Controller
{
    /** POST /admin/payment_action */
    public function store(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        $bookingId = (int) $request->input('booking_id', 0);
        $amount    = (float) $request->input('amount', 0);
        $method    = Security::sanitize($request->input('method'));
        $note      = Security::sanitize($request->input('reference_note'));

        if ($bookingId && $amount > 0 && $method !== '') {
            Sql::execute(
                "INSERT INTO booking_payments (booking_id, amount, method, reference_note, recorded_by) VALUES (?,?,?,?,?)",
                [$bookingId, $amount, $method, $note, admin_id()]
            );
        }

        return redirect("/admin/booking_detail?id={$bookingId}&payment=logged");
    }

    /** GET /admin/payment_logs */
    public function index(Request $request)
    {
        $dateFrom = Security::sanitize($request->query('date_from'));
        $dateTo   = Security::sanitize($request->query('date_to'));

        $where  = "1=1";
        $params = [];
        if ($dateFrom) {
            $where .= " AND DATE(bp.created_at) >= ?";
            $params[] = $dateFrom;
        }
        if ($dateTo) {
            $where .= " AND DATE(bp.created_at) <= ?";
            $params[] = $dateTo;
        }

        $payments = Sql::fetchAll(
            "SELECT bp.*, b.booking_ref, b.full_name AS booker_name, au.username AS recorded_by_name
             FROM booking_payments bp
             JOIN bookings b ON b.id = bp.booking_id
             LEFT JOIN admin_users au ON au.id = bp.recorded_by
             WHERE $where
             ORDER BY bp.created_at DESC",
            $params
        );

        $totalCollected = array_sum(array_column($payments, 'amount'));
        $byMethod = [];
        foreach ($payments as $p) {
            $byMethod[$p['method']] = ($byMethod[$p['method']] ?? 0) + (float) $p['amount'];
        }

        return view('admin.payment_logs', [
            'pageTitle'      => 'Payment Analytics',
            'dateFrom'       => $dateFrom,
            'dateTo'         => $dateTo,
            'payments'       => $payments,
            'totalCollected' => $totalCollected,
            'byMethod'       => $byMethod,
        ]);
    }
}
