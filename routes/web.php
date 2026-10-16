<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingActionController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BookingDetailController;
use App\Http\Controllers\Admin\BookingEditController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\HallController as AdminHallController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index']);
Route::get('/halls', [HallController::class, 'index']);
Route::get('/events', [EventController::class, 'index']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/contact', [PageController::class, 'contact']);
Route::get('/policy', [PageController::class, 'policy']);
Route::get('/sitemap.xml', SitemapController::class);

// Endpoints used by assets/js/main.js
Route::post('/booking/submit', [BookingController::class, 'submit']);
Route::get('/api/check-availability', [BookingController::class, 'availability']);
Route::post('/contact/submit', [ContactController::class, 'submit']);
Route::get('/api/stats', [ApiController::class, 'stats']);

// Old URLs (e.g. links already shared or indexed by Google) -> new URLs
foreach (['halls', 'events', 'about', 'contact', 'policy'] as $page) {
    Route::get("/pages/{$page}.php", function (Request $request) use ($page) {
        $qs = $request->getQueryString();
        return redirect("/{$page}" . ($qs ? "?{$qs}" : ''), 301);
    });
}

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
| URLs keep the old page names (without .php) so every link and the
| JavaScript in the admin pages work exactly as before.
*/
Route::prefix('admin')->group(function () {
    Route::get('/', fn () => redirect('/admin/index'));

    // Login / logout (public)
    Route::get('login', [AuthController::class, 'showLogin']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);

    // Everything below needs a logged-in admin
    Route::middleware('admin')->group(function () {
        Route::get('index', [DashboardController::class, 'index']);

        Route::get('bookings', [AdminBookingController::class, 'index']);
        Route::post('bookings', [AdminBookingController::class, 'reply']);
        Route::get('booking_detail', [BookingDetailController::class, 'show']);
        Route::post('booking_detail', [BookingDetailController::class, 'update']);
        Route::get('booking_edit', [BookingEditController::class, 'edit']);
        Route::post('booking_edit', [BookingEditController::class, 'update']);
        Route::get('booking_action', BookingActionController::class);

        Route::post('payment_action', [PaymentController::class, 'store']);
        Route::get('payment_logs', [PaymentController::class, 'index']);

        Route::get('events', [AdminEventController::class, 'index']);
        Route::post('events', [AdminEventController::class, 'save']);

        Route::get('halls', [AdminHallController::class, 'index']);
        Route::post('halls', [AdminHallController::class, 'save']);

        Route::get('messages', [MessageController::class, 'index']);
        Route::post('messages', [MessageController::class, 'handle']);

        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'handle']);

        Route::get('logs', [LogController::class, 'index']);

        Route::get('settings', [SettingController::class, 'index']);
        Route::post('settings', [SettingController::class, 'update']);
    });
});

// Push-notification subscription (admin browser)
Route::post('/api/push-subscription', [ApiController::class, 'pushSubscription'])->middleware('admin');
