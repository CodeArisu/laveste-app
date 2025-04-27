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
        Schema::create('product_status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name', 50);
            $table->timestamps();
        });

        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('garment_id');
            $table->unsignedBigInteger('product_status_id');

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('garment_id')
            ->references('id')
            ->on('garments')
            ->onDelete('cascade');

            $table->foreign('product_status_id')
            ->references('id')
            ->on('product_status')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_status');
        Schema::dropIfExists('display_products');
    }
};
