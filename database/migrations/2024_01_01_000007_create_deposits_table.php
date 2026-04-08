<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('BDT');
            $table->decimal('converted_amount', 15, 2)->nullable();
            $table->string('converted_currency', 10)->nullable();
            $table->string('method'); // bkash, nagad, rocket, bank_transfer, binance_usdt, etc.
            $table->string('transaction_id')->nullable();
            $table->string('sender_account')->nullable();
            $table->string('receiver_account')->nullable();
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
