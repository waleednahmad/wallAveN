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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->startingValue(101);
            $table->string('po_number')->nullable()->comment('Purchase Order Number');
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('dealer_id')->nullable()->index();
            $table->unsignedBigInteger('representative_id')->nullable()->index();
            $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'canceled'])->default('pending');
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
