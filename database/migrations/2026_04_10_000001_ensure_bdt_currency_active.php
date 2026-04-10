<?php

use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Currency::updateOrCreate(
            ['code' => 'BDT'],
            [
                'name' => 'Bangladeshi Taka',
                'symbol' => '৳',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ',',
                'exchange_rate' => 110.25,
                'is_active' => true,
                'is_default' => false,
            ]
        );

        // Clear currency cache so the change takes effect immediately
        Currency::clearCache();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't remove BDT on rollback, just leave it
    }
};
