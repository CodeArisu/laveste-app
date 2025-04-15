<?php

use App\Enum\{ConditionStatus, Measurement};
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
            $table->enum('measurement', array_column(Measurement::cases(), 'value'))->default(Measurement::M->value);
            $table->integer('length');
            $table->integer('width');
            $table->timestamps();
        });
        
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->string('condition_name');
            $table->timestamps();
        });

        Schema::create('garments', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->double('rent_price', 16, 2);
            $table->string('poster');
            $table->longText('additional_description')->nullable();
            $table->unsignedBigInteger('condition_id')->default(ConditionStatus::OK->value);
            $table->unsignedBigInteger('size_id')->nullable();
            $table->timestamps();

            $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');

            $table->foreign('condition_id')
            ->references('id')
            ->on('conditions')
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
        Schema::dropIfExists('garments');
    }
};
