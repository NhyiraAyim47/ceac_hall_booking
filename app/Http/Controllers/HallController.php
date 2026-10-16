<?php

namespace App\Http\Controllers;

use App\Support\Sql;
use Illuminate\Http\Request;

class HallController extends Controller
{
    /** SELECT used for both the list and the single-hall view. */
    private const BASE_SELECT = "SELECT h.*,
            (SELECT COUNT(*) FROM bookings b WHERE b.hall_id=h.id AND b.status='approved') AS approved_count,
            (SELECT COUNT(*) FROM bookings b WHERE b.hall_id=h.id AND MONTH(b.event_date)=MONTH(NOW()) AND YEAR(b.event_date)=YEAR(NOW()) AND b.status='approved') AS this_month,
            (SELECT COUNT(*) FROM bookings b WHERE b.hall_id=h.id AND b.event_date>=CURDATE() AND b.status='approved') AS upcoming_count
        FROM halls h";

    public function index(Request $request)
    {
        $halls = Sql::fetchAll(self::BASE_SELECT . " WHERE h.status='active' ORDER BY h.id ASC");

        $metaTitle       = 'Halls | CEAC Hall Booking – Christ Embassy Airport City';
        $metaDescription = 'Browse all available halls at Christ Embassy Airport City. View details, capacity, amenities and book online.';

        // Single hall detail view (?id=)
        $viewHall = null;
        if ($request->has('id')) {
            $viewHall = Sql::fetch(
                self::BASE_SELECT . " WHERE h.id=? AND h.status='active'",
                [(int) $request->query('id')]
            );
            if ($viewHall) {
                $metaTitle       = $viewHall['name'] . ' | CEAC Hall Booking';
                $metaDescription = substr(strip_tags($viewHall['description'] ?? ''), 0, 160);
            }
        }

        return view('pages.halls', compact('halls', 'viewHall', 'metaTitle', 'metaDescription'));
    }
}
