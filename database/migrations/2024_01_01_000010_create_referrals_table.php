<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('bonus_amount', 15, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->enum('status', ['pending', 'qualified', 'paid'])->default('pending');
            $table->boolean('first_deposit_made')->default(false);
            $table->decimal('first_deposit_amount', 15, 2)->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->unique(['referrer_id', 'referred_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
