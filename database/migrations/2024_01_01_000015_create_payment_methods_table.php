<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['deposit', 'withdrawal', 'both'])->default('both');
            $table->string('icon')->nullable();
            $table->text('instructions')->nullable();
            $table->json('fields')->nullable(); // Required fields for this method
            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->nullable();
            $table->decimal('fee_fixed', 15, 2)->default(0);
            $table->decimal('fee_percentage', 5, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->json('account_details')->nullable(); // Admin account details
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
