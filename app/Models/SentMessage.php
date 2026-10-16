<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentMessage extends Model
{
    use SoftDeletes;

    protected $table = 'sent_messages';

    protected $guarded = ['id'];

    // The table uses sent_at instead of created_at / updated_at
    public $timestamps = false;
}
