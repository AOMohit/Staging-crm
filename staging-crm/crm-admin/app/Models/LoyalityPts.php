<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyalityPts extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}