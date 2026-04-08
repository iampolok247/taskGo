<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('instructions');
            $table->string('task_url')->nullable();
            $table->decimal('reward', 15, 2);
            $table->string('currency', 10)->default('BDT');
            $table->enum('category', ['social', 'survey', 'video', 'app', 'website', 'other'])->default('other');
            $table->integer('daily_limit')->default(0);
            $table->integer('total_limit')->default(0);
            $table->integer('completed_count')->default(0);
            $table->integer('today_completed')->default(0);
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->integer('estimated_time')->default(5); // in minutes
            $table->string('proof_type')->default('screenshot'); // screenshot, url, text
            $table->text('proof_instructions')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'category']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
