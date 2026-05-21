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
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('status')->default('pending'); // pending|processing|completed|failed
            $table->string('layout')->default('2x3');
            $table->string('footer_text')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->json('subcategory_ids')->nullable();
            $table->string('front_cover_path')->nullable();
            $table->string('back_cover_path')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedInteger('products_count')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
