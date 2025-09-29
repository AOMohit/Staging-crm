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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->int('added_by');
            $table->string('trip_type');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('price');
            $table->integer('duration_nights');
            $table->string('continent');
            $table->string('landscape');
            $table->string('style');
            $table->string('activity');
            $table->text('overview')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default("Approved");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};