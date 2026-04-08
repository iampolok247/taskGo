<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\PaymentMethod;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function currencies()
    {
        $currencies = Currency::orderBy('name')->get();
        
        return view('admin.settings.currencies', compact('currencies'));
    }

    public function updateCurrency(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'exchange_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // If setting as default, unset other defaults
        if ($request->boolean('is_default')) {
            Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
        }

        $currency->update($validated);
        Currency::clearCache();

        return back()->with('success', 'Currency updated successfully!');
    }

    public function paymentMethods()
    {
        $methods = PaymentMethod::ordered()->get();
        
        return view('admin.settings.payment-methods', compact('methods'));
    }

    public function updatePaymentMethod(Request $request, PaymentMethod $method)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'instructions' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'fee_fixed' => 'required|numeric|min:0',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'account_details' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $method->update($validated);

        return back()->with('success', 'Payment method updated successfully!');
    }

    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:payment_methods',
            'type' => 'required|in:deposit,withdrawal,both',
            'instructions' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'fee_fixed' => 'required|numeric|min:0',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'account_details' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        PaymentMethod::create($validated);

        return back()->with('success', 'Payment method created successfully!');
    }
}
