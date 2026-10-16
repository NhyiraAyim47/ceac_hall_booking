<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** Admin > Admin Users (super admin only). */
class UserController extends Controller
{
    private const ROLES = ['super_admin', 'admin', 'viewer'];

    public function index()
    {
        if (!is_super_admin()) {
            return view('admin.denied', ['pageTitle' => 'Admin Users', 'message' => 'Access denied — Super Admin only.']);
        }

        return view('admin.users', [
            'pageTitle' => 'Admin Users',
            'msg'       => session('msg', ''),
            'msgType'   => session('msgType', 'success'),
            'users'     => Sql::fetchAll("SELECT * FROM admin_users ORDER BY created_at ASC"),
        ]);
    }

    public function handle(Request $request)
    {
        if (!is_super_admin()) {
            return view('admin.denied', ['pageTitle' => 'Admin Users', 'message' => 'Access denied — Super Admin only.']);
        }

        $msg     = '';
        $msgType = 'success';
        $action  = (string) $request->input('action', '');

        // ── Create new admin user ───────────────────────────────
        if ($action === 'create') {
            $uname = Security::sanitize($request->input('username'));
            $email = Security::sanitize($request->input('email'));
            $full  = Security::sanitize($request->input('full_name'));
            $role  = $this->role($request->input('role'));
            $pass  = (string) $request->input('password', '');

            if (!$uname || !$email || !$full || strlen($pass) < 8) {
                $msg = 'All fields required. Password must be at least 8 characters.';
                $msgType = 'danger';
            } elseif (Sql::fetch("SELECT id FROM admin_users WHERE username=? OR email=?", [$uname, $email])) {
                $msg = 'Username or email already exists.';
                $msgType = 'danger';
            } else {
                Sql::execute(
                    "INSERT INTO admin_users (username,email,password_hash,full_name,role) VALUES (?,?,?,?,?)",
                    [$uname, $email, Hash::make($pass, ['rounds' => 12]), $full, $role]
                );
                adminLog('create_admin', "Created admin: $uname");
                $msg = "Admin user '$uname' created successfully.";
            }
        }

        // ── Toggle active / inactive ────────────────────────────
        if ($action === 'toggle') {
            $uid = (int) $request->input('user_id', 0);
            if ($uid && $uid !== admin_id()) {
                Sql::execute("UPDATE admin_users SET is_active = 1 - is_active WHERE id=?", [$uid]);
                $msg = 'User status updated.';
            }
        }

        // ── Edit user details ───────────────────────────────────
        if ($action === 'edit_user') {
            $uid     = (int) $request->input('user_id', 0);
            $full    = Security::sanitize($request->input('full_name'));
            $uname   = Security::sanitize($request->input('username'));
            $email   = Security::sanitize($request->input('email'));
            $role    = $this->role($request->input('role'));
            $newPass = (string) $request->input('new_password', '');

            if (!$uid || !$full || !$uname || !$email) {
                $msg = 'All fields are required.';
                $msgType = 'danger';
            } elseif (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $uname)) {
                $msg = 'Username must be at least 3 characters (letters, numbers, underscores only).';
                $msgType = 'danger';
            } elseif (!Security::validateEmail($email)) {
                $msg = 'Please enter a valid email address.';
                $msgType = 'danger';
            } elseif (Sql::fetch("SELECT id FROM admin_users WHERE (username=? OR email=?) AND id != ?", [$uname, $email, $uid])) {
                $msg = 'That username or email is already used by another admin.';
                $msgType = 'danger';
            } else {
                Sql::execute(
                    "UPDATE admin_users SET full_name=?,username=?,email=?,role=?,updated_at=NOW() WHERE id=?",
                    [$full, $uname, $email, $role, $uid]
                );

                if (!empty($newPass)) {
                    if (strlen($newPass) < 8) {
                        $msg = 'New password must be at least 8 characters.';
                        $msgType = 'danger';
                        return redirect('/admin/users')->with('msg', $msg)->with('msgType', $msgType);
                    }
                    Sql::execute(
                        "UPDATE admin_users SET password_hash=?,login_attempts=0,locked_until=NULL WHERE id=?",
                        [Hash::make($newPass, ['rounds' => 12]), $uid]
                    );
                }

                adminLog('edit_admin', "Edited admin ID $uid");
                $msg = 'Admin user updated successfully.';
            }
        }

        return redirect('/admin/users')->with('msg', $msg)->with('msgType', $msgType);
    }

    /** Only the three real roles are accepted (the form value is never trusted as-is). */
    private function role($value): string
    {
        $role = Security::sanitize($value ?: 'admin');
        return in_array($role, self::ROLES, true) ? $role : 'admin';
    }
}
