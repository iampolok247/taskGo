<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'fee_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:6',
            'fee_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public static function getRate(string $from, string $to): ?float
    {
        if ($from === $to) {
            return 1.0;
        }

        $cacheKey = "currency_rate.{$from}.{$to}";

        return Cache::remember($cacheKey, 3600, function () use ($from, $to) {
            $rate = static::where('from_currency', $from)
                ->where('to_currency', $to)
                ->where('is_active', true)
                ->first();

            return $rate?->rate;
        });
    }

    public static function convert(float $amount, string $from, string $to, bool $includeFee = true): ?array
    {
        if ($from === $to) {
            return [
                'amount' => $amount,
                'converted' => $amount,
                'fee' => 0,
                'rate' => 1.0,
            ];
        }

        $rateRecord = static::where('from_currency', $from)
            ->where('to_currency', $to)
            ->where('is_active', true)
            ->first();

        if (!$rateRecord) {
            return null;
        }

        $converted = $amount * $rateRecord->rate;
        $fee = 0;

        if ($includeFee && $rateRecord->fee_percentage > 0) {
            $fee = ($converted * $rateRecord->fee_percentage) / 100;
            $converted = $converted - $fee;
        }

        return [
            'amount' => $amount,
            'converted' => round($converted, 2),
            'fee' => round($fee, 2),
            'rate' => $rateRecord->rate,
        ];
    }

    public static function clearCache(): void
    {
        $rates = static::all();
        foreach ($rates as $rate) {
            Cache::forget("currency_rate.{$rate->from_currency}.{$rate->to_currency}");
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
