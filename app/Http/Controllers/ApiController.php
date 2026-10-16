<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Small JSON endpoints used by the front-end scripts. */
class ApiController extends Controller
{
    /** GET /api/stats - polled by the stats counters and the admin notification bell. */
    public function stats(): JsonResponse
    {
        try {
            $stats = getSiteStats();
            $stats['overrunning'] = count(getOverrunBookings((int) config('ceac.overrun_buffer')));
            return response()->json($stats);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['error' => true, 'pending' => 0, 'messages_unread' => 0, 'overrunning' => 0], 500);
        }
    }

    /** POST /api/push-subscription (admin only) - keeps the browser push subscription in the session. */
    public function pushSubscription(Request $request): JsonResponse
    {
        $subscription = $request->input('subscription');
        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'Invalid data.']);
        }
        $request->session()->put('push_subscription', json_encode($subscription));
        return response()->json(['success' => true]);
    }
}
