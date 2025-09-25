<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventory extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(InventoryCategory::class, 'category_id', 'id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function trip(){
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

}