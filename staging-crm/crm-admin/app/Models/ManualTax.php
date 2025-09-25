<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ManualTax
{
    use HasFactory;

    protected $fillable = [
        'trip_booking_id',
        'customer_id',
        'amount_1',
        'tcs_1',
        'amount_2',
        'tcs_2',
    ];

    public function tripBooking()
    {
        return $this->belongsTo(TripBooking::class);
    }
}
