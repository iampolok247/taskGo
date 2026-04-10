<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('user.withdrawals.index', compact('withdrawals'));
    }

    public function create()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $paymentMethods = PaymentMethod::active()
            ->forWithdrawal()
            ->ordered()
            ->get();

        $minWithdrawal = Setting::getValue('min_withdrawal_amount', 500);
        $maxWithdrawal = Setting::getValue('max_withdrawal_amount', 50000);
        $withdrawalFee = Setting::getValue('withdrawal_fee_percent', 0);

        $recentWithdrawals = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get active currencies for conversion dropdown
        $currencies = Currency::where('is_active', true)->orderBy('name')->get();

        return view('user.withdrawals.create', compact('wallet', 'paymentMethods', 'minWithdrawal', 'maxWithdrawal', 'recentWithdrawals', 'withdrawalFee', 'currencies'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $minWithdraw = Setting::getValue('min_withdrawal_amount', 500);
        $maxWithdraw = Setting::getValue('max_withdrawal_amount', 50000);

        $validated = $request->validate([
            'amount' => "required|numeric|min:{$minWithdraw}|max:{$maxWithdraw}",
            'withdrawal_currency' => 'nullable|string|max:10',
            'method' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'bank_name' => 'nullable|required_if:method,bank|string|max:100',
            'branch_name' => 'nullable|string|max:100',
            'routing_number' => 'nullable|string|max:50',
            'additional_info' => 'nullable|string|max:500',
        ]);

        // Handle currency conversion - amount entered is in selected currency, convert to USD
        $withdrawCurrency = $validated['withdrawal_currency'] ?? 'USD';
        $enteredAmount = $validated['amount'];
        $usdAmount = $enteredAmount;

        if ($withdrawCurrency !== 'USD') {
            $currency = Currency::getByCode($withdrawCurrency);
            if ($currency && $currency->exchange_rate > 0) {
                $usdAmount = $currency->toUSD($enteredAmount);
            }
        }

        // Check withdrawable balance (earnings only, NOT deposits)
        $withdrawableBalance = $wallet->withdrawable_balance;
        if ($withdrawableBalance < $usdAmount) {
            $displayBalance = $withdrawableBalance;
            if ($withdrawCurrency !== 'USD') {
                $currency = Currency::getByCode($withdrawCurrency);
                if ($currency) {
                    $displayBalance = $currency->fromUSD($withdrawableBalance);
                }
            }
            return back()->withErrors([
                'amount' => 'Insufficient earnings balance. Your withdrawable earnings: ' . format_currency($withdrawableBalance) . ($withdrawCurrency !== 'USD' ? ' (≈ ' . number_format($displayBalance, 2) . ' ' . $withdrawCurrency . ')' : '') . '. Deposited funds cannot be withdrawn.',
            ])->withInput();
        }

        // Also check main_balance has enough funds
        if ($wallet->main_balance < $usdAmount) {
            return back()->withErrors([
                'amount' => 'Insufficient balance. Your available balance is ' . format_currency($wallet->main_balance),
            ])->withInput();
        }

        // Get payment method and calculate fee
        $paymentMethod = PaymentMethod::where('code', $validated['method'])->first();
        $fee = $paymentMethod ? $paymentMethod->calculateFee($usdAmount) : 0;
        $finalAmount = $usdAmount - $fee;

        // Deduct from wallet (uses processWithdrawal which checks withdrawable_balance)
        if (!$wallet->processWithdrawal($usdAmount)) {
            return back()->withErrors([
                'amount' => 'Unable to process withdrawal. Please try again.',
            ])->withInput();
        }

        // Create withdrawal
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $usdAmount,
            'currency' => 'USD',
            'converted_amount' => $withdrawCurrency !== 'USD' ? $enteredAmount : null,
            'converted_currency' => $withdrawCurrency !== 'USD' ? $withdrawCurrency : null,
            'fee' => $fee,
            'final_amount' => $finalAmount,
            'method' => $validated['method'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'bank_name' => $validated['bank_name'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'routing_number' => $validated['routing_number'] ?? null,
            'additional_info' => $validated['additional_info'] ?? null,
        ]);

        // Create transaction record
        Transaction::create([
            'user_id' => $user->id,
            'transaction_id' => 'WTH' . strtoupper(uniqid()),
            'type' => 'withdrawal',
            'amount' => $usdAmount,
            'currency' => 'USD',
            'status' => 'pending',
            'description' => 'Withdrawal request via ' . strtoupper($validated['method']),
            'transactionable_type' => Withdrawal::class,
            'transactionable_id' => $withdrawal->id,
            'balance_before' => $wallet->main_balance + $usdAmount,
            'balance_after' => $wallet->main_balance,
        ]);

        // Display amount
        $displayAmount = ($withdrawCurrency !== 'USD')
            ? number_format($enteredAmount, 2) . ' ' . $withdrawCurrency . ' (' . format_currency($usdAmount) . ')'
            : format_currency($usdAmount);

        // Notify user
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'title' => 'Withdrawal Request Submitted',
            'message' => 'Your withdrawal request for ' . $displayAmount . ' has been submitted and is pending approval.',
            'type' => 'info',
            'icon' => 'clock',
        ]);

        return redirect()->route('user.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully! Amount: ' . format_currency($finalAmount) . ' (Fee: ' . format_currency($fee) . ')');
    }

    public function show(Withdrawal $withdrawal)
    {
        if ($withdrawal->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.withdrawals.show', compact('withdrawal'));
    }
}
