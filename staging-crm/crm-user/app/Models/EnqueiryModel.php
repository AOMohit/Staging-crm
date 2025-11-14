<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnqueiryModel extends Model
{
    protected $table = 'enquiries';
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'expedition', 'id');
    }
    use HasFactory;
}
