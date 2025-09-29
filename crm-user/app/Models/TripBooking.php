<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'token', 'admin_id', 'spoc_id', 'booking_for', 'customer_id', 'lead_source', 'sub_lead_source', 'expedition', 'trip_id', 'vehical_type', 'vehical_seat', 'vehical_seat_amt', 'vehical_security_amt', 'vehical_security_amt_cmt', 'room_type', 'room_type_amt', 'room_cat', 'payment_from', 'payment_from_cmpny', 'payment_from_tax', 'payment_all_done_by_this', 'payment_by_customer_id', 'payment_type', 'payment_amt', 'payment_date', 'trip_cost', 'extra_services', 'tax_required', 'complete_paid', 'payable_amt', 'part_payment_list', 'trip_status', 'invoice_file', 'invoice_status', 'invoice_sent_date', 'redeem_points_verified', 'redeem_points_otp', 'redeem_points', 'form_submited', 'cancelation_reason', 'cancelation_amount', 'cancelation_date', 'created_at', 'updated_at'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function spoc()
    {
        return $this->belongsTo(Admin::class, 'spoc_id', 'id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }
}
