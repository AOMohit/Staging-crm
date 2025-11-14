<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarbonDonation extends Model
{
    protected $table = 'carbon_donations';
    protected $fillable = [
        'name', 'email', 'mobile', 'pan_card', 'address', 'co2', 'trees', 'donation', 'status','gateway_txn_id','gateway_status','gateway_payload','gateway_hash_ok'
    ];
    public $timestamps = true;
}
