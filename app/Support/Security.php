<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Input hygiene, IP blocking, rate limiting and booking reference generation.
 * (CSRF protection and sessions are now handled by Laravel itself.)
 */
class Security
{
    /** Strip HTML tags and trim - for storing in the database. */
    public static function sanitize(?string $input): string
    {
        return strip_tags(trim((string) $input));
    }

    /** Escape for HTML output. */
    public static function sanitizeOutput(?string $input): string
    {
        return htmlspecialchars(strip_tags(trim((string) $input)), ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePhone(string $phone): bool
    {
        return (bool) preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $phone);
    }

    public static function getClientIp(?Request $request = null): string
    {
        $request ??= request();
        return $request->ip() ?? '0.0.0.0';
    }

    public static function isIpBlocked(string $ip): bool
    {
        return Sql::fetch(
            "SELECT id FROM blocked_ips WHERE ip_address = ? AND (blocked_until IS NULL OR blocked_until > NOW())",
            [$ip]
        ) !== null;
    }

    /**
     * Returns true if the action is allowed, false if the IP has hit the limit.
     */
    public static function checkRateLimit(string $ip, string $action, int $maxAttempts, int $windowMinutes = 60): bool
    {
        Sql::execute("DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL ? MINUTE)", [$windowMinutes]);

        $row = Sql::fetch("SELECT attempts FROM rate_limits WHERE ip_address = ? AND action = ?", [$ip, $action]);
        if (!$row) {
            Sql::execute("INSERT INTO rate_limits (ip_address, action) VALUES (?,?)", [$ip, $action]);
            return true;
        }
        if ((int) $row['attempts'] >= $maxAttempts) {
            return false;
        }
        Sql::execute("UPDATE rate_limits SET attempts = attempts + 1 WHERE ip_address = ? AND action = ?", [$ip, $action]);
        return true;
    }

    public static function generateBookingRef(): string
    {
        do {
            $ref = 'CEAC-' . strtoupper(substr(uniqid(), -4)) . '-' . rand(1000, 9999);
        } while (Sql::fetch("SELECT id FROM bookings WHERE booking_ref = ?", [$ref]));
        return $ref;
    }
}
