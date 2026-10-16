<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    protected $table = 'booking_payments';

    protected $guarded = ['id'];

    // Table only has created_at
    const UPDATED_AT = null;

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function recorder()
    {
        return $this->belongsTo(AdminUser::class, 'recorded_by');
    }
}
