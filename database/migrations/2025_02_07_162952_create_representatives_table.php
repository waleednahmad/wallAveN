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
        Schema::create('representatives', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('bussiness_name')->nullable();
            $table->enum('faderal_tax_classification', ['individual', 'c_corporation', 's_corporation', 'partnership', 'trust', 'limited_liability_company', 'other'])->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->enum('taxpayer_identification_number', ['social_security_number', 'employer_identification_number'])->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('employer_identification_number')->nullable();
            // ---------------- Payments Data ----------------  
            $table->enum('bank_account_type', ['checking', 'savings'])->nullable();
            $table->string('bank_routing_number')->nullable()->comment('ABA Routing Number');
            $table->string('bank_account_number')->nullable();
            $table->longText('signature')->nullable();
            // --------------- Settings -----------------
            $table->boolean('status')->default(true);
            $table->longText('code')->nullable();
            $table->longText('other_info')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representatives');
    }
};
