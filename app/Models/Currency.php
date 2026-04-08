<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'symbol_position',
        'decimal_places',
        'decimal_separator',
        'thousand_separator',
        'exchange_rate',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:8',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get all active currencies
     */
    public static function getActive()
    {
        return Cache::remember('currencies.active', 3600, function () {
            return static::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return Cache::remember('currency.default', 3600, function () {
            return static::where('is_default', true)->first() 
                ?? static::where('code', 'USD')->first()
                ?? static::first();
        });
    }

    /**
     * Get currency by code
     */
    public static function getByCode(string $code)
    {
        return Cache::remember("currency.{$code}", 3600, function () use ($code) {
            return static::where('code', $code)->first();
        });
    }

    /**
     * Format amount with this currency
     */
    public function format($amount): string
    {
        $formatted = number_format(
            (float) $amount,
            $this->decimal_places,
            $this->decimal_separator,
            $this->thousand_separator
        );

        if ($this->symbol_position === 'after') {
            return $formatted . $this->symbol;
        }

        return $this->symbol . $formatted;
    }

    /**
     * Convert amount from USD to this currency
     */
    public function fromUSD($amount): float
    {
        return (float) $amount * (float) $this->exchange_rate;
    }

    /**
     * Convert amount from this currency to USD
     */
    public function toUSD($amount): float
    {
        if ($this->exchange_rate == 0) {
            return 0;
        }
        return (float) $amount / (float) $this->exchange_rate;
    }

    /**
     * Clear currency cache
     */
    public static function clearCache(): void
    {
        Cache::forget('currencies.active');
        Cache::forget('currency.default');
        
        foreach (static::pluck('code') as $code) {
            Cache::forget("currency.{$code}");
        }
    }

    /**
     * Scope for active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get searchable currencies for dropdown
     */
    public static function getForDropdown()
    {
        return static::active()
            ->orderBy('name')
            ->get()
            ->map(function ($currency) {
                return [
                    'code' => $currency->code,
                    'name' => $currency->name,
                    'symbol' => $currency->symbol,
                    'label' => "{$currency->code} - {$currency->name} ({$currency->symbol})",
                ];
            });
    }
}
