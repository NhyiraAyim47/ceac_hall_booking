<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** Admin > Settings: own profile and password. */
class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings', [
            'pageTitle' => 'Settings',
            'msg'       => session('msg', ''),
            'msgType'   => session('msgType', 'success'),
            'admin'     => Sql::fetch("SELECT * FROM admin_users WHERE id=?", [admin_id()]),
        ]);
    }

    public function update(Request $request)
    {
        $msg     = '';
        $msgType = 'success';

        // ── Update profile ──────────────────────────────────────
        if ($request->has('update_profile')) {
            $fullName = Security::sanitize($request->input('full_name'));
            $username = Security::sanitize($request->input('username'));
            $email    = Security::sanitize($request->input('email'));

            if (strlen($fullName) < 2) {
                $msg = 'Full name must be at least 2 characters.';
                $msgType = 'danger';
            } elseif (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $msg = 'Username must be at least 3 characters and contain only letters, numbers and underscores.';
                $msgType = 'danger';
            } elseif (!Security::validateEmail($email)) {
                $msg = 'Please enter a valid email address.';
                $msgType = 'danger';
            } elseif (Sql::fetch("SELECT id FROM admin_users WHERE (username=? OR email=?) AND id != ?", [$username, $email, admin_id()])) {
                $msg = 'That username or email is already in use by another admin.';
                $msgType = 'danger';
            } else {
                Sql::execute(
                    "UPDATE admin_users SET full_name=?, username=?, email=?, updated_at=NOW() WHERE id=?",
                    [$fullName, $username, $email, admin_id()]
                );
                adminLog('update_profile', 'Admin updated their profile');
                $msg = 'Profile updated successfully.';
            }
        }

        // ── Change password ─────────────────────────────────────
        if ($request->has('change_password')) {
            $current = (string) $request->input('current_password', '');
            $new     = (string) $request->input('new_password', '');
            $confirm = (string) $request->input('confirm_password', '');
            $admin   = Sql::fetch("SELECT * FROM admin_users WHERE id=?", [admin_id()]);

            if (!Hash::check($current, $admin['password_hash'])) {
                $msg = 'Current password is incorrect.';
                $msgType = 'danger';
            } elseif (strlen($new) < 8) {
                $msg = 'New password must be at least 8 characters.';
                $msgType = 'danger';
            } elseif ($new !== $confirm) {
                $msg = 'New passwords do not match.';
                $msgType = 'danger';
            } else {
                Sql::execute(
                    "UPDATE admin_users SET password_hash=?, updated_at=NOW() WHERE id=?",
                    [Hash::make($new, ['rounds' => 12]), admin_id()]
                );
                adminLog('change_password', 'Admin changed their password');
                $msg = 'Password changed successfully.';
            }
        }

        return redirect('/admin/settings')->with('msg', $msg)->with('msgType', $msgType);
    }
}
