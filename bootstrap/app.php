<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Route middleware alias used in routes/web.php:  ->middleware('admin')
        $middleware->alias([
            'admin' => AdminAuth::class,
        ]);

        // Security headers on every web response (X-Frame-Options, HSTS, ...)
        $middleware->web(append: [SecurityHeaders::class]);

        // Behind Cloudflare / the host's proxy the real visitor IP comes in a
        // forwarded header. Set TRUSTED_PROXIES in .env to restrict this.
        $middleware->trustProxies(at: env('TRUSTED_PROXIES', '*'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A stale/missing CSRF token on one of the AJAX forms: answer in JSON
        // (the front-end scripts expect JSON) instead of the "Page Expired" page.
        $exceptions->render(function (HttpException $e, Request $request) {
            // Stale login page: just show a fresh login form
            if ($e->getStatusCode() === 419 && $request->is('admin/login')) {
                return redirect('/admin/login');
            }
            if ($e->getStatusCode() === 419 && $request->is('booking/*', 'contact/*', 'api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Security token invalid. Please refresh the page and try again.',
                ], 419);
            }
        });
    })->create();
