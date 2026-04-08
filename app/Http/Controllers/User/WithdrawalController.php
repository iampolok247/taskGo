<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Setting;
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

        $recentWithdrawals = \App\Models\Withdrawal::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.withdrawals.create', compact('wallet', 'paymentMethods', 'minWithdrawal', 'maxWithdrawal', 'recentWithdrawals', 'withdrawalFee'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $minWithdraw = Setting::getValue('min_withdrawal_amount', 500);
        $maxWithdraw = Setting::getValue('max_withdrawal_amount', 50000);

        $validated = $request->validate([
            'amount' => "required|numeric|min:{$minWithdraw}|max:{$maxWithdraw}",
            'method' => 'required|in:usdt,bank,bkash,nagad,rocket',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'bank_name' => 'nullable|required_if:method,bank|string|max:100',
            'branch_name' => 'nullable|string|max:100',
            'routing_number' => 'nullable|string|max:50',
            'additional_info' => 'nullable|string|max:500',
        ]);

        // Check balance
        if ($wallet->main_balance < $validated['amount']) {
            return back()->withErrors([
                'amount' => 'Insufficient balance. Your available balance is ৳' . number_format($wallet->main_balance, 2),
            ])->withInput();
        }

        // Get payment method and calculate fee
        $paymentMethod = PaymentMethod::where('code', $validated['method'])->first();
        $fee = $paymentMethod ? $paymentMethod->calculateFee($validated['amount']) : 0;
        $finalAmount = $validated['amount'] - $fee;

        // Deduct from wallet
        $wallet->deductFromMain($validated['amount']);

        // Create withdrawal
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'currency' => 'BDT',
            'fee' => $fee,
            'final_amount' => $finalAmount,
            'method' => $validated['method'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'bank_name' => $validated['bank_name'],
            'branch_name' => $validated['branch_name'],
            'routing_number' => $validated['routing_number'],
            'additional_info' => $validated['additional_info'],
        ]);

        return redirect()->route('user.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully! Amount: ৳' . number_format($finalAmount, 2) . ' (Fee: ৳' . number_format($fee, 2) . ')');
    }

    public function show(Withdrawal $withdrawal)
    {
        $this->authorize('view', $withdrawal);

        return view('user.withdrawals.show', compact('withdrawal'));
    }
}
