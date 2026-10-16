<?php

/*
|--------------------------------------------------------------------------
| Global helper functions
|--------------------------------------------------------------------------
| Loaded by Composer ("autoload.files"). These are the helpers that the
| original project defined inside includes/db.php and a few page files.
*/

use App\Support\Security;
use App\Support\Sql;
use Illuminate\Support\Facades\Auth;

if (!function_exists('base_url')) {
    /** Site root URL without a trailing slash (replaces the old BASE_URL constant). */
    function base_url(): string
    {
        return rtrim(url('/'), '/');
    }
}

if (!function_exists('sanitizeOutput')) {
    function sanitizeOutput(?string $value): string
    {
        return Security::sanitizeOutput($value);
    }
}

// ── Admin session helpers ────────────────────────────────────────────────
if (!function_exists('admin_user')) {
    function admin_user()
    {
        return Auth::guard('admin')->user();
    }
}

if (!function_exists('admin_id')) {
    function admin_id(): ?int
    {
        $u = admin_user();
        return $u ? (int) $u->id : null;
    }
}

if (!function_exists('admin_role')) {
    function admin_role(): string
    {
        return admin_user()->role ?? 'viewer';
    }
}

if (!function_exists('is_super_admin')) {
    function is_super_admin(): bool
    {
        return admin_role() === 'super_admin';
    }
}

if (!function_exists('adminLog')) {
    /** Write an entry to the admin activity log. */
    function adminLog(string $action, string $description): void
    {
        Sql::execute(
            "INSERT INTO admin_logs (admin_id, action, description, ip_address) VALUES (?,?,?,?)",
            [admin_id(), $action, $description, Security::getClientIp()]
        );
    }
}

// ── Statistics ───────────────────────────────────────────────────────────
if (!function_exists('getSiteStats')) {
    function getSiteStats(): array
    {
        return [
            'total_bookings'  => Sql::fetch("SELECT COUNT(*) AS c FROM bookings")['c'] ?? 0,
            'approved'        => Sql::fetch("SELECT COUNT(*) AS c FROM bookings WHERE status IN ('approved','completed')")['c'] ?? 0,
            'pending'         => Sql::fetch("SELECT COUNT(*) AS c FROM bookings WHERE status='pending'")['c'] ?? 0,
            'this_month'      => Sql::fetch("SELECT COUNT(*) AS c FROM bookings WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")['c'] ?? 0,
            'upcoming_events' => Sql::fetch("SELECT COUNT(*) AS c FROM events WHERE status IN ('upcoming','ongoing')")['c'] ?? 0,
            'messages_unread' => Sql::fetch("SELECT COUNT(*) AS c FROM contact_messages WHERE is_read=0")['c'] ?? 0,
        ];
    }
}

if (!function_exists('getOverrunBookings')) {
    function getOverrunBookings(int $bufferMinutes = 15): array
    {
        return Sql::fetchAll(
            "SELECT b.id, b.booking_ref, b.full_name, b.email, b.phone, b.event_title,
                    b.event_date, b.start_time, b.end_time,
                    h.name AS hall_name, h.pricing_json,
                    TIMESTAMPDIFF(MINUTE, CONCAT(b.event_date, ' ', b.end_time), NOW()) AS overrun_minutes
             FROM bookings b
             JOIN halls h ON h.id = b.hall_id
             WHERE b.status = 'approved'
               AND b.deleted_at IS NULL
               AND b.event_date IS NOT NULL
               AND b.end_time IS NOT NULL
               AND TIMESTAMPDIFF(MINUTE, CONCAT(b.event_date, ' ', b.end_time), NOW()) > ?
             ORDER BY overrun_minutes DESC",
            [$bufferMinutes]
        );
    }
}

if (!function_exists('getBookingPaymentTotal')) {
    function getBookingPaymentTotal(int $bookingId): float
    {
        return (float) (Sql::fetch(
            "SELECT COALESCE(SUM(amount),0) AS total FROM booking_payments WHERE booking_id = ?",
            [$bookingId]
        )['total'] ?? 0);
    }
}

if (!function_exists('getBookingPayments')) {
    function getBookingPayments(int $bookingId): array
    {
        return Sql::fetchAll(
            "SELECT bp.*, au.username AS recorded_by_name
             FROM booking_payments bp
             LEFT JOIN admin_users au ON au.id = bp.recorded_by
             WHERE bp.booking_id = ?
             ORDER BY bp.created_at DESC",
            [$bookingId]
        );
    }
}

// ── Hall images ──────────────────────────────────────────────────────────
if (!function_exists('hall_image_url')) {
    /** URL of an image stored under public/assets/images, or null if the file is missing. */
    function hall_image_url(?string $relative): ?string
    {
        if (!$relative) {
            return null;
        }
        return file_exists(public_path('assets/images/' . $relative))
            ? base_url() . '/assets/images/' . $relative
            : null;
    }
}

if (!function_exists('getHallImage')) {
    function getHallImage(array $hall, int $index = 0): string
    {
        if (!empty($hall['images_json'])) {
            $imgs = json_decode($hall['images_json'], true);
            if (is_array($imgs) && isset($imgs[$index]) && ($u = hall_image_url($imgs[$index]))) {
                return $u;
            }
        }
        if (!empty($hall['image']) && ($u = hall_image_url($hall['image']))) {
            return $u;
        }
        return base_url() . '/assets/images/hall-placeholder.svg';
    }
}

if (!function_exists('getAllHallImages')) {
    function getAllHallImages(array $hall): array
    {
        $result = [];
        if (!empty($hall['images_json'])) {
            $imgs = json_decode($hall['images_json'], true);
            if (is_array($imgs)) {
                foreach ($imgs as $img) {
                    if ($u = hall_image_url($img)) {
                        $result[] = $u;
                    }
                }
            }
        }
        if (empty($result)) {
            $result[] = base_url() . '/assets/images/hall-placeholder.svg';
        }
        return $result;
    }
}

if (!function_exists('hallImage')) {
    function hallImage(array $hall): string
    {
        return getHallImage($hall, 0);
    }
}

if (!function_exists('hallImages')) {
    function hallImages(array $hall): array
    {
        $imgs = [];
        if (!empty($hall['images_json'])) {
            $decoded = json_decode($hall['images_json'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $img) {
                    if ($u = hall_image_url($img)) {
                        $imgs[] = $u;
                    }
                }
            }
        }
        if (empty($imgs)) {
            $imgs[] = hallImage($hall);
        }
        return $imgs;
    }
}
