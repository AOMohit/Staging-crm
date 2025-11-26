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
        Schema::create('loyality_pts', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('admin_id');
            $table->string('reason')->nullable();
            $table->string('trip_name')->nullable();
            $table->double('cost')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('trans_type')->nullable();
            $table->double('trans_amt')->nullable();
            $table->double('balance')->nullable();
            $table->double('trans_page')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyality_pts');
    }
};