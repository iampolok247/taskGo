<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('converted_amount', 15, 2)->nullable()->after('currency');
            $table->string('converted_currency', 10)->nullable()->after('converted_amount');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['converted_amount', 'converted_currency']);
        });
    }
};
