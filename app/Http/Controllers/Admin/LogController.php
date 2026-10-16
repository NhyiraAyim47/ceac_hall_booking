<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Sql;

/** Activity log - super admins only. */
class LogController extends Controller
{
    public function index()
    {
        if (!is_super_admin()) {
            return view('admin.denied', ['pageTitle' => 'Activity Log']);
        }

        return view('admin.logs', [
            'pageTitle' => 'Activity Log',
            'logs' => Sql::fetchAll(
                "SELECT l.*, u.full_name, u.username
                 FROM admin_logs l LEFT JOIN admin_users u ON l.admin_id=u.id
                 ORDER BY l.created_at DESC LIMIT 100"
            ),
        ]);
    }
}
