<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:deposit,withdrawal,both',
            'category' => 'required|in:mobile_wallet,bank,crypto,other',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'fee_fixed' => 'required|numeric|min:0',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'instructions' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $code = Str::slug($request->name, '_');
        $originalCode = $code;
        $counter = 1;
        while (PaymentMethod::where('code', $code)->exists()) {
            $code = $originalCode . '_' . $counter;
            $counter++;
        }

        $accountDetails = $this->buildAccountDetails($request);
        $fields = $this->buildRequiredFields($request->category);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('payment-methods', 'public');
        }

        PaymentMethod::create([
            'name' => $request->name,
            'code' => $code,
            'type' => $request->type,
            'icon' => $iconPath,
            'instructions' => $request->instructions,
            'fields' => $fields,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'fee_fixed' => $request->fee_fixed,
            'fee_percentage' => $request->fee_percentage,
            'currency' => $request->currency,
            'account_details' => $accountDetails,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:deposit,withdrawal,both',
            'category' => 'required|in:mobile_wallet,bank,crypto,other',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'fee_fixed' => 'required|numeric|min:0',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'instructions' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $accountDetails = $this->buildAccountDetails($request);
        $fields = $this->buildRequiredFields($request->category);

        $iconPath = $paymentMethod->icon;
        if ($request->hasFile('icon')) {
            if ($paymentMethod->icon && \Storage::disk('public')->exists($paymentMethod->icon)) {
                \Storage::disk('public')->delete($paymentMethod->icon);
            }
            $iconPath = $request->file('icon')->store('payment-methods', 'public');
        }

        $paymentMethod->update([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $iconPath,
            'instructions' => $request->instructions,
            'fields' => $fields,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'fee_fixed' => $request->fee_fixed,
            'fee_percentage' => $request->fee_percentage,
            'currency' => $request->currency,
            'account_details' => $accountDetails,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->icon && \Storage::disk('public')->exists($paymentMethod->icon)) {
            \Storage::disk('public')->delete($paymentMethod->icon);
        }
        $paymentMethod->delete();
        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method status updated successfully.');
    }

    private function buildAccountDetails(Request $request): array
    {
        $details = ['category' => $request->category];

        switch ($request->category) {
            case 'mobile_wallet':
                // Form fields use mw_ prefix to avoid name collisions
                $details['account_type'] = $request->mw_account_type;
                $details['account_number'] = $request->mw_account_number;
                $details['account_name'] = $request->mw_account_name;
                break;
            case 'bank':
                // Form fields use bank_ prefix for account_number/account_name
                $details['bank_name'] = $request->bank_name;
                $details['branch_name'] = $request->branch_name;
                $details['account_name'] = $request->bank_account_name;
                $details['account_number'] = $request->bank_account_number;
                $details['routing_number'] = $request->routing_number;
                $details['swift_code'] = $request->swift_code;
                break;
            case 'crypto':
                $details['network'] = $request->network;
                $details['wallet_address'] = $request->wallet_address;
                break;
            case 'other':
                $details['account_info'] = $request->account_info;
                break;
        }
        return $details;
    }

    private function buildRequiredFields(string $category): array
    {
        switch ($category) {
            case 'mobile_wallet':
                return [
                    ['name' => 'sender_number', 'label' => 'Your Mobile Number', 'type' => 'text', 'required' => true],
                    ['name' => 'transaction_id', 'label' => 'Transaction ID', 'type' => 'text', 'required' => true],
                ];
            case 'bank':
                return [
                    ['name' => 'sender_bank', 'label' => 'Your Bank Name', 'type' => 'text', 'required' => true],
                    ['name' => 'sender_account', 'label' => 'Your Account Number', 'type' => 'text', 'required' => true],
                    ['name' => 'transaction_id', 'label' => 'Transaction ID/Reference', 'type' => 'text', 'required' => true],
                ];
            case 'crypto':
                return [
                    ['name' => 'sender_wallet', 'label' => 'Your Wallet Address', 'type' => 'text', 'required' => true],
                    ['name' => 'txn_hash', 'label' => 'Transaction Hash', 'type' => 'text', 'required' => true],
                ];
            default:
                return [['name' => 'transaction_id', 'label' => 'Transaction ID/Reference', 'type' => 'text', 'required' => true]];
        }
    }
}
