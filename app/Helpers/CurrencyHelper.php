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
     * Format amount in current user's currency
     */
    function format_currency($amount, ?string $currencyCode = null): string
    {
        if ($currencyCode) {
            $currency = Currency::getByCode($currencyCode);
        } else {
            $currency = currency();
        }
        
        if (!$currency) {
            return '$' . number_format((float) $amount, 2);
        }
        
        return $currency->format($amount);
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
