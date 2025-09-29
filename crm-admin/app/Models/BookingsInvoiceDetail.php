<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingsInvoiceDetail extends Model
{
    use HasFactory;

    public function staffVerifiedBy(){
        return $this->belongsTo(User::class, 'invoice_verified_by', 'id');
    }

    public function staffSentBy(){
        return $this->belongsTo(User::class, 'invoice_sent_by', 'id');
    }
}