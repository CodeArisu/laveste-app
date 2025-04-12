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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name')->nullable();
            $table->string('company_name')->nullable();
            $table->text('address', 255);
            $table->string('contact', 11);
            $table->timestamps();
        });
        
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name')->nullable();
            $table->timestamps();
        });

        Schema::create('subtypes', function (Blueprint $table) {
            $table->id();
            $table->string('subtype_name')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 50);

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')
            ->references('id')
            ->on('suppliers')
            ->onDelete('cascade');
            
            $table->double('original_price', 16, 2);
            $table->text('description', 255);
            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')
            ->references('id')
            ->on('types')
            ->onDelete('cascade');

            $table->unsignedBigInteger('subtype_id');
            $table->foreign('subtype_id')
            ->references('id')
            ->on('subtypes')
            ->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('types');
        Schema::dropIfExists('subtypes');
        Schema::dropIfExists('product_types');
    }
};
