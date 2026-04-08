<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('agent_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('total_earnings', 15, 2)->default(0);
            $table->decimal('pending_earnings', 15, 2)->default(0);
            $table->decimal('withdrawn_earnings', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
