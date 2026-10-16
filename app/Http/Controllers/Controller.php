<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * The "viewer" role is read-only. Call this at the top of any action that
     * changes data; it returns a response when the user may not proceed.
     */
    protected function denyViewer()
    {
        if (admin_role() === 'viewer') {
            return response()->view('admin.denied', ['pageTitle' => 'Access denied'], 403);
        }
        return null;
    }

    /**
     * CSRF check for the few state-changing admin links that are plain GET
     * links (delete / approve / reject). The token travels as ?csrf_token=...
     * POST forms are already covered by Laravel's VerifyCsrfToken middleware.
     */
    protected function validGetToken(Request $request): bool
    {
        return hash_equals((string) csrf_token(), (string) $request->query('csrf_token', ''));
    }
}
