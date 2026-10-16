<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hall extends Model
{
    use SoftDeletes;

    protected $table = 'halls';

    protected $guarded = ['id'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'hall_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'hall_id');
    }
}
