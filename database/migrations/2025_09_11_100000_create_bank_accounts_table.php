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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('routing_number')->nullable();
            $table->string('account_type')->default('checking'); // checking, savings
            $table->string('payment_method')->default('bank_transfer'); // bank_transfer, paypal, stripe, etc.
            $table->string('currency')->default('USD'); // Support for different currencies
            $table->string('country_code')->default('US'); // ISO country code for international support
            $table->text('payment_details')->nullable(); // JSON field for additional payment method details
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
