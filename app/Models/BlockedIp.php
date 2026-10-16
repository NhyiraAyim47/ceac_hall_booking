<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $table = 'blocked_ips';

    protected $guarded = ['id'];

    // Table only has created_at
    const UPDATED_AT = null;
}
