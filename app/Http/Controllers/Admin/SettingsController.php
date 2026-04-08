<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function currencyRates()
    {
        $rates = CurrencyRate::all();
        
        return view('admin.settings.currency-rates', compact('rates'));
    }

    public function updateCurrencyRates(Request $request)
    {
        $validated = $request->validate([
            'rates' => 'required|array',
            'rates.*.from_currency' => 'required|string|max:10',
            'rates.*.to_currency' => 'required|string|max:10',
            'rates.*.rate' => 'required|numeric|min:0',
            'rates.*.fee_percentage' => 'required|numeric|min:0|max:100',
            'rates.*.is_active' => 'boolean',
        ]);

        foreach ($validated['rates'] as $rateData) {
            CurrencyRate::updateOrCreate(
                [
                    'from_currency' => $rateData['from_currency'],
                    'to_currency' => $rateData['to_currency'],
                ],
                [
                    'rate' => $rateData['rate'],
                    'fee_percentage' => $rateData['fee_percentage'],
                    'is_active' => $rateData['is_active'] ?? true,
                ]
            );
        }

        CurrencyRate::clearCache();

        return back()->with('success', 'Currency rates updated successfully!');
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
