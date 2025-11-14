<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferModel extends Model
{
    protected $table = 'transfer_pts';
    use HasFactory;
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
