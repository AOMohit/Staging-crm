<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->integer('spoc_id');
            $table->string('booking_for')->nullable();
            $table->json('customer_id')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('sub_lead_source')->nullable();
            $table->string('expedition')->nullable();
            $table->integer('trip_id')->nullable();
            $table->string('vehical_type')->nullable();
            $table->string('vehical_seat')->nullable();
            $table->string('vehical_seat_amt')->nullable();
            $table->string('vehical_security_amt')->nullable();
            $table->string('vehical_security_amt_cmt')->nullable();
            $table->string('room_type')->nullable();
            $table->string('room_type_amt')->nullable();
            $table->string('room_cat')->nullable();
            $table->string('payment_from')->nullable();
            $table->string('payment_from_cmpny')->nullable();
            $table->string('payment_from_tax')->nullable();
            $table->integer('payment_all_done_by_this')->nullable();
            $table->json('trip_cost')->nullable();
            $table->json('extra_services')->nullable();
            $table->integer('tax_required')->nullable();
            $table->json('trip_cost_cmt')->nullable();
            $table->integer('complete_paid')->nullable();
            $table->double('payable_amt')->nullable();
            $table->double('remaining_amt_1')->nullable();
            $table->double('remaining_amt_2')->nullable();
            $table->double('remaining_amt_3')->nullable();
            $table->double('remaining_amt_4')->nullable();
            $table->double('remaining_amt_5')->nullable();
            $table->string('trip_status')->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('invoice_sent_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_bookings');
    }
};