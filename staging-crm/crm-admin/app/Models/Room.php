<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function booking(){
        return $this->belongsTo(TripBooking::class, 'booking_id', 'id');
    }
}