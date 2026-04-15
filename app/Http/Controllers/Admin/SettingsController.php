<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\PaymentMethod;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        // Get all settings as key => value pairs
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Get all settings keys from the request (flat format: name="site_name")
        $settingsKeys = [
            'site_name', 'support_email', 'support_phone',
            'daily_task_limit', 'default_task_reward',
            'min_deposit', 'max_deposit', 'min_withdrawal', 'max_withdrawal',
            'withdrawal_fee_percent',
            'referral_min_deposit', 'referral_signup_bonus', 'leader_direct_signup_bonus', 'referral_task_commission',
            'default_agent_commission', 'leader_referral_commission',
        ];

        foreach ($settingsKeys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                
                // Determine type
                $setting = Setting::where('key', $key)->first();
                if ($setting) {
                    $setting->update(['value' => $value ?? '']);
                    Cache::forget("setting.{$key}");
                } else {
                    // Create new setting if it doesn't exist
                    Setting::create([
                        'key' => $key,
                        'value' => $value ?? '',
                        'type' => is_numeric($value) ? 'number' : 'text',
                        'group' => 'general',
                    ]);
                }
            }
        }

        // Clear all settings cache
        $allSettings = Setting::all();
        foreach ($allSettings as $s) {
            Cache::forget("setting.{$s->key}");
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
