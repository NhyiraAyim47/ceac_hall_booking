<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_logs';

    protected $guarded = ['id'];

    // Table only has created_at
    const UPDATED_AT = null;

    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }
}
