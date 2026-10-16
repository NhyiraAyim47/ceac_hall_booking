<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateLimit extends Model
{
    protected $table = 'rate_limits';

    protected $guarded = ['id'];

    public $timestamps = false;
}
