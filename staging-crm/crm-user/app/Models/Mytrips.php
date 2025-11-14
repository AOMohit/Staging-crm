<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mytrips extends Model
{
    protected $table = 'trip_bookings';
    use HasFactory;
    
    public function tripData()
    {
        return $this->hasOne(Trip::class, 'id', 'trip_id');
    }

    
}
