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
        Schema::create('email_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('trip_id');
            $table->integer('booking_id');
            $table->integer('customer_id');
            $table->integer('admin_id');
            $table->date('mail_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_activities');
    }
};