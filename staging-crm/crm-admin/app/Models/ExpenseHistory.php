<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseHistory extends Model
{
    use HasFactory;
    
    public function expense(){
        return $this->belongsTo(Expense::class, 'expense_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}