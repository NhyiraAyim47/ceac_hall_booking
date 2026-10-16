<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Creates an admin account from the command line, e.g. the very first
 * super admin on a fresh database:
 *
 *     php artisan ceac:make-admin
 */
class MakeAdmin extends Command
{
    protected $signature = 'ceac:make-admin
                            {--username= : Login username}
                            {--email= : Email address}
                            {--name= : Full name}
                            {--role=super_admin : super_admin, admin or viewer}';

    protected $description = 'Create an admin user for the CEAC admin panel';

    public function handle(): int
    {
        $username = $this->option('username') ?: $this->ask('Username (letters, numbers, underscores)');
        $email    = $this->option('email') ?: $this->ask('Email address');
        $name     = $this->option('name') ?: $this->ask('Full name');
        $role     = $this->option('role');

        if (!in_array($role, ['super_admin', 'admin', 'viewer'], true)) {
            $this->error('Role must be super_admin, admin or viewer.');
            return self::FAILURE;
        }
        if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', (string) $username)) {
            $this->error('Username must be at least 3 characters: letters, numbers and underscores only.');
            return self::FAILURE;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('That email address is not valid.');
            return self::FAILURE;
        }
        if (AdminUser::where('username', $username)->orWhere('email', $email)->exists()) {
            $this->error('An admin with that username or email already exists.');
            return self::FAILURE;
        }

        $password = $this->secret('Password (minimum 8 characters)');
        if (strlen((string) $password) < 8) {
            $this->error('The password must be at least 8 characters.');
            return self::FAILURE;
        }
        if ($password !== $this->secret('Confirm password')) {
            $this->error('The passwords do not match.');
            return self::FAILURE;
        }

        AdminUser::create([
            'username'      => $username,
            'email'         => $email,
            'full_name'     => $name,
            'role'          => $role,
            'password_hash' => Hash::make($password, ['rounds' => 12]),
        ]);

        $this->info("Admin '{$username}' created ({$role}). You can now log in at /admin/login");
        return self::SUCCESS;
    }
}
