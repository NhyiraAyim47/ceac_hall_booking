<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Admin login / logout.
 *
 *  - password check with Hash::check (bcrypt, same hashes as before)
 *  - lock the account for LOCKOUT_MINUTES after MAX_LOGIN_ATTEMPTS failures
 *  - the session ID is regenerated on login (prevents session fixation)
 *  - CSRF protection comes from Laravel's VerifyCsrfToken middleware (@csrf)
 */
class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/index');
        }

        $error       = '';
        $lockoutSecs = 0;

        // Show the lock countdown on page load if the user is still locked
        if ($request->has('user')) {
            $user = Sql::fetch("SELECT locked_until FROM admin_users WHERE username = ?", [Security::sanitize($request->query('user'))]);
            if ($user && $user['locked_until'] && strtotime($user['locked_until']) > time()) {
                $lockoutSecs = strtotime($user['locked_until']) - time();
                $error = 'Account is temporarily locked.';
            }
        }

        return view('admin.login', ['error' => $error, 'lockoutSecs' => $lockoutSecs, 'username' => '']);
    }

    public function login(Request $request)
    {
        $username = trim((string) $request->input('username', ''));
        $result   = $this->attempt($username, (string) $request->input('password', ''), $request);

        if ($result['success']) {
            return redirect($this->safeRedirect($request->query('redirect')));
        }

        // Work out remaining lock time for the countdown
        $lockoutSecs = 0;
        $user = Sql::fetch("SELECT locked_until FROM admin_users WHERE username = ? OR email = ?", [$username, $username]);
        if ($user && $user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $lockoutSecs = strtotime($user['locked_until']) - time();
        }

        return view('admin.login', [
            'error'       => $result['message'],
            'lockoutSecs' => $lockoutSecs,
            'username'    => $username,
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            adminLog('logout', 'Admin logged out');
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login?logout=1');
    }

    /** Core login logic (ported from Auth::login in the old db.php). */
    private function attempt(string $username, string $password, Request $request): array
    {
        $ip = Security::getClientIp($request);

        if (Security::isIpBlocked($ip)) {
            return ['success' => false, 'message' => 'Access denied from your network.'];
        }

        $user = AdminUser::where('is_active', 1)
            ->where(fn ($q) => $q->where('username', $username)->orWhere('email', $username))
            ->first();

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }

        // Locked?
        if ($user->locked_until && $user->locked_until->getTimestamp() > time()) {
            $mins = (int) ceil(($user->locked_until->getTimestamp() - time()) / 60);
            return ['success' => false, 'message' => "Account locked. Try again in {$mins} minutes."];
        }

        if (!Hash::check($password, $user->password_hash)) {
            $attempts = $user->login_attempts + 1;
            $max      = (int) config('ceac.max_login_attempts');
            $minutes  = (int) config('ceac.lockout_minutes');

            if ($attempts >= $max) {
                $user->forceFill([
                    'login_attempts' => $attempts,
                    'locked_until'   => now()->addMinutes($minutes),
                ])->save();
                return ['success' => false, 'message' => "Too many failed attempts. Account locked for {$minutes} minutes."];
            }

            $user->forceFill(['login_attempts' => $attempts])->save();
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }

        // Success - logging in also regenerates the session ID
        Auth::guard('admin')->login($user);
        $request->session()->regenerate();

        $user->forceFill(['login_attempts' => 0, 'locked_until' => null, 'last_login' => now()])->save();
        Sql::execute(
            "INSERT INTO admin_logs (admin_id, action, description, ip_address) VALUES (?,?,?,?)",
            [$user->id, 'login', 'Admin login successful', $ip]
        );

        return ['success' => true, 'message' => 'Login successful.'];
    }

    /** Only allow redirects to a path on this site (no open redirect). */
    private function safeRedirect(?string $target): string
    {
        if ($target && str_starts_with($target, '/') && !str_starts_with($target, '//') && !str_contains($target, '\\')) {
            return $target;
        }
        return '/admin/index';
    }
}
