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
        Schema::create('bookings_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->integer('invoice_sent_by');
            $table->integer('invoice_verified_by')->nullable();
            $table->integer('invoice_status')->nullable();
            $table->text('comment')->nullable();
            $table->integer('invoice_reuploaded')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings_invoice_details');
    }
};