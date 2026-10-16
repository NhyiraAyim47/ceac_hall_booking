<?php

namespace App\Http\Controllers;

use App\Support\Sql;

class EventController extends Controller
{
    public function index()
    {
        return view('pages.events', [
            'metaTitle'       => 'Events | CEAC Hall Booking – Christ Embassy Airport City',
            'metaDescription' => 'View all events held at the CEAC Hall – Cell Meetings, Fellowship Meetings, Executive Meetings and All Night Services at Christ Embassy Airport City.',
            'events' => Sql::fetchAll(
                "SELECT e.*, h.name AS hall_name FROM events e
                 LEFT JOIN halls h ON e.hall_id=h.id
                 WHERE e.deleted_at IS NULL ORDER BY e.start_datetime DESC"
            ),
            'upcomingCount'  => Sql::fetch("SELECT COUNT(*) AS c FROM events WHERE status='upcoming' AND start_datetime >= NOW()")['c'] ?? 0,
            'completedCount' => Sql::fetch("SELECT COUNT(*) AS c FROM events WHERE status='completed'")['c'] ?? 0,
            'recurringCount' => Sql::fetch("SELECT COUNT(*) AS c FROM events WHERE is_recurring=1")['c'] ?? 0,
        ]);
    }
}
