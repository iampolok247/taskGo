<?php

use App\Models\Currency;
use Illuminate\Support\Facades\Auth;

if (!function_exists('currency')) {
    /**
     * Get the current user's currency or default currency
     */
    function currency(): ?Currency
    {
        // Check for authenticated user (any guard)
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            return Currency::getByCode($user->currency_code ?? 'USD');
        }
        
        if (Auth::guard('agent')->check()) {
            $user = Auth::guard('agent')->user();
            return Currency::getByCode($user->currency_code ?? 'USD');
        }
        
        if (Auth::check()) {
            $user = Auth::user();
            return Currency::getByCode($user->currency_code ?? 'USD');
        }
        
        return Currency::getDefault();
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format amount in current user's currency with conversion
     * Amount is assumed to be in USD (base currency)
     */
    function format_currency($amount, ?string $currencyCode = null, bool $convert = true): string
    {
        if ($currencyCode) {
            $currency = Currency::getByCode($currencyCode);
        } else {
            $currency = currency();
        }
        
        if (!$currency) {
            return '$' . number_format((float) $amount, 2);
        }
        
        // Convert from USD to user's currency if needed
        $displayAmount = $convert ? $currency->fromUSD($amount) : $amount;
        
        return $currency->format($displayAmount);
    }
}

if (!function_exists('convert_currency')) {
    /**
     * Convert amount from one currency to another
     */
    function convert_currency($amount, string $fromCode, string $toCode): float
    {
        if ($fromCode === $toCode) {
            return (float) $amount;
        }
        
        $fromCurrency = Currency::getByCode($fromCode);
        $toCurrency = Currency::getByCode($toCode);
        
        if (!$fromCurrency || !$toCurrency) {
            return (float) $amount;
        }
        
        // Convert to USD first, then to target currency
        $usdAmount = $fromCurrency->toUSD($amount);
        return $toCurrency->fromUSD($usdAmount);
    }
}

if (!function_exists('to_usd')) {
    /**
     * Convert amount from user's currency to USD
     */
    function to_usd($amount, ?string $currencyCode = null): float
    {
        if ($currencyCode) {
            $currency = Currency::getByCode($currencyCode);
        } else {
            $currency = currency();
        }
        
        if (!$currency) {
            return (float) $amount;
        }
        
        return $currency->toUSD($amount);
    }
}

if (!function_exists('from_usd')) {
    /**
     * Convert amount from USD to user's currency
     */
    function from_usd($amount, ?string $currencyCode = null): float
    {
        if ($currencyCode) {
            $currency = Currency::getByCode($currencyCode);
        } else {
            $currency = currency();
        }
        
        if (!$currency) {
            return (float) $amount;
        }
        
        return $currency->fromUSD($amount);
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get the current user's currency symbol
     */
    function currency_symbol(?string $currencyCode = null): string
    {
        if ($currencyCode) {
            $currency = Currency::getByCode($currencyCode);
        } else {
            $currency = currency();
        }
        
        return $currency?->symbol ?? '$';
    }
}

if (!function_exists('all_currencies')) {
    /**
     * Get all active currencies for dropdown
     */
    function all_currencies(): \Illuminate\Support\Collection
    {
        return Currency::getActive();
    }
}

if (!function_exists('currencies_for_dropdown')) {
    /**
     * Get currencies formatted for searchable dropdown
     */
    function currencies_for_dropdown(): \Illuminate\Support\Collection
    {
        return Currency::getForDropdown();
    }
}
