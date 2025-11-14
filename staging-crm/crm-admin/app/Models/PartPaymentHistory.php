<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartPaymentHistory extends Model
{
    use HasFactory;
    protected $casts = [
        'details' => 'array',
    ];

    protected $fillable = ['booking_id', 'trip_id', 'details'];

  public function booking() {
        return $this->belongsTo(TripBooking::class, 'booking_id');
    }
}
