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
        Schema::create('carbon_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->string('trip_name');
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_email');
            $table->integer('no_of_trees')->nullable();
            $table->float('total_distance')->nullable();
            $table->float('carbon_emission')->nullable();
            $table->integer('car_sequence_number')->nullable();
            $table->string('car_name')->nullable();

            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carbon_infos');
    }
};
