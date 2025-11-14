<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonInfo extends Model
{
    use HasFactory;
     protected $fillable = [
        'trip_id',
        'trip_name',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'no_of_trees',
        'total_distance',
        'carbon_emission',
        'car_sequence_number',
        'car_name',

       
    ];
}
