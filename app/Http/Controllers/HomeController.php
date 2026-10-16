<?php

namespace App\Http\Controllers;

use App\Support\Sql;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'metaTitle'       => 'Home | CEAC Hall Booking – Christ Embassy Airport City',
            'metaDescription' => 'Officially book the CEAC Hall at Christ Embassy Airport City, Accra Ghana. Perfect for cell meetings, fellowship meetings, executive meetings and all-night services.',
            'stats'           => getSiteStats(),
            'hall'            => Sql::fetch("SELECT * FROM halls WHERE slug = 'ceac-hall' LIMIT 1"),
            'upcomingEvents'  => Sql::fetchAll(
                "SELECT * FROM events
                 WHERE status IN ('upcoming','ongoing') AND (deleted_at IS NULL OR deleted_at = '')
                 ORDER BY start_datetime ASC LIMIT 6"
            ),
        ]);
    }
}
