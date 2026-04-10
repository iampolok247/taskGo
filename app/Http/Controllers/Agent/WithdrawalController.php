<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        
        $withdrawals = Withdrawal::where('agent_id', $agent->id)
            ->latest()
            ->paginate(15);

        return view('agent.withdrawals.index', compact('withdrawals'));
    }

    public function create()
    {
        $agent = Auth::guard('agent')->user();
        $wallet = $agent->wallet;

        $paymentMethods = PaymentMethod::active()
            ->forWithdrawal()
            ->ordered()
            ->get();

        $minWithdrawal = Setting::getValue('min_withdrawal_amount', 500);
        $maxWithdrawal = Setting::getValue('max_withdrawal_amount', 50000);
        $withdrawalFee = Setting::getValue('withdrawal_fee_percent', 0);

        $recentWithdrawals = Withdrawal::where('agent_id', $agent->id)
            ->latest()
            ->take(5)
            ->get();

        // Get active currencies for conversion dropdown
        $currencies = Currency::where('is_active', true)->orderBy('name')->get();

        return view('agent.withdrawals.create', compact('wallet', 'paymentMethods', 'minWithdrawal', 'maxWithdrawal', 'recentWithdrawals', 'withdrawalFee', 'currencies'));
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $wallet = $agent->wallet;

        $minWithdraw = Setting::getValue('min_withdrawal_amount', 500);
        $maxWithdraw = Setting::getValue('max_withdrawal_amount', 50000);

        $validated = $request->validate([
            'amount' => "required|numeric|min:{$minWithdraw}|max:{$maxWithdraw}",
            'withdrawal_currency' => 'nullable|string|max:10',
            'method' => 'required|string|max:50',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'nullable|string|max:100',
            'bank_name' => 'nullable|required_if:method,bank|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'branch_name' => 'nullable|string|max:100',
            'routing_number' => 'nullable|string|max:50',
            'additional_info' => 'nullable|string|max:500',
        ]);

        // Handle currency conversion
        $withdrawCurrency = $validated['withdrawal_currency'] ?? 'USD';
        $enteredAmount = $validated['amount'];
        $usdAmount = $enteredAmount;

        if ($withdrawCurrency !== 'USD') {
            $currency = Currency::getByCode($withdrawCurrency);
            if ($currency && $currency->exchange_rate > 0) {
                $usdAmount = $currency->toUSD($enteredAmount);
            }
        }

        // Check withdrawable balance (earnings only)
        $withdrawableBalance = $wallet ? $wallet->withdrawable_balance : 0;
        if ($withdrawableBalance < $usdAmount) {
            return back()->withErrors([
                'amount' => 'Insufficient earnings balance. Your withdrawable earnings: ' . format_currency($withdrawableBalance) . '. Deposited funds cannot be withdrawn.',
            ])->withInput();
        }

        // Also check main_balance
        if (($wallet->main_balance ?? 0) < $usdAmount) {
            return back()->withErrors([
                'amount' => 'Insufficient balance. Your available balance is ' . format_currency($wallet->main_balance ?? 0),
            ])->withInput();
        }

        // Get payment method and calculate fee
        $paymentMethod = PaymentMethod::where('code', $validated['method'])->first();
        $fee = $paymentMethod ? $paymentMethod->calculateFee($usdAmount) : 0;
        $finalAmount = $usdAmount - $fee;

        // For bank method, use bank fields
        $accountNumber = $validated['account_number'];
        $accountName = $validated['account_name'] ?? $validated['account_holder'] ?? $agent->name;
        if ($validated['method'] === 'bank' && !empty($validated['bank_account_number'])) {
            $accountNumber = $validated['bank_account_number'];
        }

        // Deduct from wallet
        if (!$wallet->processWithdrawal($usdAmount)) {
            return back()->withErrors([
                'amount' => 'Unable to process withdrawal. Please try again.',
            ])->withInput();
        }

        // Create withdrawal
        $withdrawal = Withdrawal::create([
            'agent_id' => $agent->id,
            'amount' => $usdAmount,
            'currency' => 'USD',
            'converted_amount' => $withdrawCurrency !== 'USD' ? $enteredAmount : null,
            'converted_currency' => $withdrawCurrency !== 'USD' ? $withdrawCurrency : null,
            'fee' => $fee,
            'final_amount' => $finalAmount,
            'method' => $validated['method'],
            'account_name' => $accountName,
            'account_number' => $accountNumber,
            'bank_name' => $validated['bank_name'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'routing_number' => $validated['routing_number'] ?? null,
            'additional_info' => $validated['additional_info'] ?? null,
        ]);

        // Notify agent
        Notification::create([
            'notifiable_type' => Agent::class,
            'notifiable_id' => $agent->id,
            'title' => 'Withdrawal Request Submitted',
            'message' => 'Your withdrawal request for ' . format_currency($usdAmount) . ' has been submitted and is pending approval.',
            'type' => 'info',
            'icon' => 'clock',
        ]);

        return redirect()->route('agent.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully! Amount: ' . format_currency($finalAmount) . ' (Fee: ' . format_currency($fee) . ')');
    }

    public function show(Withdrawal $withdrawal)
    {
        $agent = Auth::guard('agent')->user();
        
        if ($withdrawal->agent_id !== $agent->id) {
            abort(403);
        }

        return view('agent.withdrawals.show', compact('withdrawal'));
    }
}
