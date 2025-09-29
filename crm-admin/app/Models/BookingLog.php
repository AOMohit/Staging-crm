<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    use HasFactory;

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}