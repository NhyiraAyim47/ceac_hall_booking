<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $guarded = ['id'];

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class, 'booking_id');
    }
}
