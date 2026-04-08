<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // USD, EUR, GBP, USDT, etc.
            $table->string('name'); // US Dollar, Euro, etc.
            $table->string('symbol', 10); // $, €, £, etc.
            $table->string('symbol_position', 10)->default('before'); // before or after
            $table->integer('decimal_places')->default(2);
            $table->string('decimal_separator', 1)->default('.');
            $table->string('thousand_separator', 1)->default(',');
            $table->decimal('exchange_rate', 20, 8)->default(1); // Rate relative to USD
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Add currency_code to users, agents, and admins
        if (!Schema::hasColumn('users', 'currency_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('currency_code', 3)->default('USD');
            });
        }

        if (!Schema::hasColumn('agents', 'currency_code')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->string('currency_code', 3)->default('USD');
            });
        }

        if (!Schema::hasColumn('admins', 'currency_code')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->string('currency_code', 3)->default('USD');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'currency_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('currency_code');
            });
        }

        if (Schema::hasColumn('agents', 'currency_code')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->dropColumn('currency_code');
            });
        }

        if (Schema::hasColumn('admins', 'currency_code')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('currency_code');
            });
        }

        Schema::dropIfExists('currencies');
    }
};
