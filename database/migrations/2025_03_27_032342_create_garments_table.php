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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->enum('measurement', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);
            $table->integer('length');
            $table->integer('width');
            $table->timestamps();
        });
        
        Schema::create('condition_status', function (Blueprint $table) {
            $table->id();
            $table->string('condition', 50);
            $table->timestamps();
        });

        Schema::create('garments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->double('rent_price', 16, 2);
            $table->string('poster');
            $table->unsignedBigInteger('condition_id');
            $table->unsignedBigInteger('size_id');
            $table->unsignedBigInteger('images_id');
            $table->timestamps();

            $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');

            $table->foreign('condition_id')
            ->references('id')
            ->on('condition_status')
            ->onDelete('cascade');

            $table->foreign('size_id')
            ->references('id')
            ->on('sizes')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('condition_status');
        Schema::dropIfExists('images');
        Schema::dropIfExists('garments');
    }
};
