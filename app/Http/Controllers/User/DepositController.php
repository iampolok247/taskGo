<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('user.deposits.index', compact('deposits'));
    }

    public function create()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        $paymentMethods = PaymentMethod::active()
            ->forDeposit()
            ->ordered()
            ->get();

        $recentDeposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get active currencies for conversion dropdown
        $currencies = Currency::where('is_active', true)->orderBy('name')->get();

        return view('user.deposits.create', compact('paymentMethods', 'wallet', 'recentDeposits', 'currencies'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Get valid method codes from database
        $validMethods = PaymentMethod::active()->forDeposit()->pluck('code')->toArray();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'deposit_currency' => 'nullable|string|max:10',
            'method' => 'required|in:' . implode(',', $validMethods),
            'transaction_id' => 'nullable|string|max:100',
            'sender_account' => 'nullable|string|max:100',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get payment method details
        $paymentMethod = PaymentMethod::where('code', $validated['method'])->first();

        if ($paymentMethod && !$paymentMethod->isValidAmount($validated['amount'])) {
            return back()->withErrors([
                'amount' => 'Amount must be between ' . number_format($paymentMethod->min_amount) . ' and ' . number_format($paymentMethod->max_amount ?? 999999),
            ])->withInput();
        }

        // Handle currency conversion
        $depositCurrency = $validated['deposit_currency'] ?? 'USD';
        $convertedAmount = null;
        $convertedCurrency = null;

        if ($depositCurrency !== 'USD') {
            $currency = Currency::getByCode($depositCurrency);
            if ($currency && $currency->exchange_rate > 0) {
                // Convert from user's currency to USD (base)
                $convertedAmount = $validated['amount'];
                $convertedCurrency = $depositCurrency;
                // The actual stored amount is in USD
                $usdAmount = $currency->toUSD($validated['amount']);
            } else {
                $usdAmount = $validated['amount'];
            }
        } else {
            $usdAmount = $validated['amount'];
        }

        // Upload proof image
        $proofPath = $request->file('proof_image')
            ->store('deposit-proofs/' . $user->id, 'public');

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $usdAmount,
            'currency' => 'USD',
            'converted_amount' => $convertedAmount,
            'converted_currency' => $convertedCurrency,
            'method' => $validated['method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'sender_account' => $validated['sender_account'] ?? null,
            'proof_image' => $proofPath,
            'notes' => $validated['notes'] ?? null,
            'receiver_account' => $paymentMethod?->account_number ?? null,
        ]);

        $displayAmount = $convertedAmount 
            ? number_format($convertedAmount, 2) . ' ' . $convertedCurrency 
            : format_currency($usdAmount);

        return redirect()->route('user.deposits.index')
            ->with('success', "Deposit request for {$displayAmount} submitted successfully! Waiting for approval.");
    }

    public function show(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.deposits.show', compact('deposit'));
    }
}
