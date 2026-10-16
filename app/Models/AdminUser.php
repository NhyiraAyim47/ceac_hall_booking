<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Admin / staff account (table: admin_users).
 * The password column is called `password_hash`, so Laravel is told to use it.
 */
class AdminUser extends Authenticatable
{
    protected $table = 'admin_users';

    protected $guarded = ['id'];

    protected $hidden = ['password_hash', 'reset_token'];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'last_login'   => 'datetime',
            'locked_until' => 'datetime',
        ];
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    // This table has no remember_token column.
    public function getRememberTokenName()
    {
        return '';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function logs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }
}
