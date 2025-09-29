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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('expedition')->nullable();
            $table->integer('adult')->nullable();
            $table->integer('minor')->nullable();
            $table->text('traveler')->nullable();
            $table->integer('redeem_points_status')->nullable();
            $table->integer('redeem_points')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};