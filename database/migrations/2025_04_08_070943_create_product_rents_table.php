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
        Schema::create('customer_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact', 11);
            $table->string('address', 255);
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_rents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_details_id');
            $table->dateTime('pickup_date');
            $table->dateTime('rented_date');
            $table->dateTime('return_date');
            $table->timestamps();

            $table->foreign('customer_details_id')
            ->references('id')
            ->on('customer_details')
            ->onDelete('cascade');
        });

        Schema::create('rent_details', function (Blueprint $table) {
            $table->id();
            $table->string('venue')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->longText('reason_for_renting')->nullable();
            $table->timestamps();
        });

        Schema::create('product_rented_status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name');
            $table->timestamps();
        });

        Schema::create('product_rents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_rented_id');
            $table->unsignedBigInteger('rent_details_id');
            $table->unsignedBigInteger('catalog_id');
            $table->unsignedBigInteger('product_rented_status_id');

            $table->foreign('customer_rented_id')
            ->references('id')
            ->on('customer_rents')
            ->onDelete('cascade');

            $table->foreign('rent_details_id')
            ->references('id')
            ->on('rent_details')
            ->onDelete('cascade');

            $table->foreign('catalog_id')
            ->references('id')
            ->on('catalogs')
            ->onDelete('cascade');

            $table->foreign('product_rented_status_id')
            ->references('id')
            ->on('product_rented_status')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_rents');
        Schema::dropIfExists('product_rented_status');
        Schema::dropIfExists('rent_details');
        Schema::dropIfExists('customer_rents');
        Schema::dropIfExists('customer_details');
    }
};
