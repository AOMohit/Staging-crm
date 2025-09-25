<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function trip(){
        return $this->belongsTo(Trip::class, 'expedition', 'id');
    }
}