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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('method_name');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_rented_id');
            $table->double('total_amount', 12, 2);
            $table->boolean('has_discount')->default(false);
            $table->double('discount_amount', 12, 2)->nullable();
            $table->double('vat', 12, 2);
            $table->unsignedBigInteger('payment_method_id');

            $table->foreign('customer_rented_id')
            ->references('id')
            ->on('customer_rented')
            ->onDelete('cascade');

            $table->foreign('payment_method_id')
            ->references('id')
            ->on('payment_methods')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('payment_methods');
    }
};
