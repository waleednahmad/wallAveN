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
        Schema::create('cart_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id')->nullable()->index();
            $table->unsignedBigInteger('representative_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();;
            $table->string('variant_sku')->nullable();
            $table->string('variant_image')->nullable();
            $table->string('title')->nullable();
            $table->string('vendor')->nullable();
            $table->string('option1_name')->nullable();
            $table->string('option1_value')->nullable();
            $table->string('option2_name')->nullable();
            $table->string('option2_value')->nullable();
            $table->string('option3_name')->nullable();
            $table->string('option3_value')->nullable();
            $table->string('sku')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_temps');
    }
};
