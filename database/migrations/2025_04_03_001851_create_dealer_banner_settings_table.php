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
        Schema::create('dealer_banner_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id')->index();
            $table->longText('text')->default('Golden Rugs – Exclusive Deals Just for You');
            $table->string('text_color')->default('#000000');
            $table->string('bg_color')->default('#f1c55e');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_banner_settings');
    }
};
