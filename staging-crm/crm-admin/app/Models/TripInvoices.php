<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripInvoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'trip_booking_id', 'invoice_path','created_at', 'updated_at'
    ];

    public function tripBooking(){
        return $this->belongsTo(TripBooking::class, 'trip_booking_id', 'id');
    }
}
