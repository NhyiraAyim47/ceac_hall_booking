<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Sql;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'pageTitle' => 'Dashboard',
            'stats'     => getSiteStats(),
            'hall'      => Sql::fetch("SELECT * FROM halls WHERE id=1"),
            'recent'    => Sql::fetchAll("SELECT b.*, h.name AS hall_name FROM bookings b LEFT JOIN halls h ON b.hall_id=h.id ORDER BY b.created_at DESC LIMIT 8"),
            'pendingB'  => Sql::fetchAll("SELECT * FROM bookings WHERE status='pending' ORDER BY created_at ASC LIMIT 5"),
        ]);
    }
}
