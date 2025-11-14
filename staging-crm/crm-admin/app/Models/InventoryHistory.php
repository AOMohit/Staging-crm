<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;

    public function inventory(){
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function trip(){
        return $this->belongsTo(Trip::class, 'stock_for', 'id');
    }

    public function given(){
        return $this->belongsTo(User::class, 'given_to', 'id');
    }
}