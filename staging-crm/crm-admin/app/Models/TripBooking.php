<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'token', 'admin_id', 'spoc_id', 'booking_for', 'customer_id','tier_details', 'lead_source', 'sub_lead_source', 'expedition', 'trip_id', 'vehical_type', 'vehicle_type_other_cmt', 'vehical_seat', 'vehical_seat_amt', 'vehical_security_amt', 'vehical_security_amt_cmt', 'room_info', 'payment_from', 'payment_from_cmpny', 'payment_from_tax', 'is_multiple_payment', 'payment_all_done_by_this', 'payment_by_customer_id', 'sch_payment_list', 'payment_type', 'payment_amt', 'payment_date', 'trip_cost', 'trip_deviation' ,'extra_services', 'is_tds','tax_required', 'complete_paid', 'payable_amt', 'part_payment_list', 'trip_status', 'invoice_file', 'invoice_status', 'invoice_sent_date', 'redeem_points_verified', 'redeem_points_otp', 'redeem_points', 'form_submited', 'cancelation_reason', 'cancelation_amount', 'cancelation_date', 'correction_reason', 'no_of_rooms', 'redeem_credit_note_amt','is_form_submitted', 'created_at', 'updated_at'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function spoc(){
        return $this->belongsTo(User::class, 'spoc_id', 'id');
    }

    public function trip(){
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }
    public function invoices(){
        return $this->hasMany(TripInvoices::class, 'trip_booking_id', 'id');
    }
}