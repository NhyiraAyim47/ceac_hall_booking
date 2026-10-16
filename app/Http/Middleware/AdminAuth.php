<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects every admin page (replaces Auth::requireLogin()).
 * Optional roles:  ->middleware('admin:super_admin')
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $guard = Auth::guard('admin');

        if (!$guard->check() || !$guard->user()->is_active) {
            $guard->logout();
            return redirect('/admin/login?redirect=' . urlencode($request->getRequestUri()));
        }

        if ($roles && !in_array($guard->user()->role, $roles, true)) {
            return response()->view('admin.denied', ['pageTitle' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
