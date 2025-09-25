<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public function service(){
        return $this->belongsTo(VendorCategory::class, 'extra_service_id', 'id');
    }

    public function vendorService(){
        return $this->belongsTo(VendorService::class, 'vendor_service_id', 'id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function trip(){
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}