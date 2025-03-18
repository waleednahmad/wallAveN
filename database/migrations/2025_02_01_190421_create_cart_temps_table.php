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
            $table->unsignedBigInteger('admin_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();;
            $table->unsignedBigInteger('variant_id')->index()->nullable();
            $table->enum('item_type', ['product', 'variant'])->default('variant');
            $table->string('name')->nullable();
            $table->longText('image')->nullable();
            $table->string('vendor')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->json('attributes')->nullable();
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
