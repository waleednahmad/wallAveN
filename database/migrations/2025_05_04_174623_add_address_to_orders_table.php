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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address')->nullable()->after('shipping')->comment('Shipping Address');
            $table->string('payment_method')->nullable()->after('shipping_address')->comment('Payment Method');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_address');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
        });
    }
};
