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
        Schema::create('appointment_status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name');
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_details_id');
            $table->dateTime('appointment_date');
            $table->time('appointment_time');
            $table->unsignedBigInteger('appointment_status_id');

            $table->foreign('customer_details_id')
            ->references('id')
            ->on('customer_details')
            ->onDelete('cascade');

            $table->foreign('appointment_status_id')
            ->references('id')
            ->on('appointment_status')
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('appointment_status');
    }
};
