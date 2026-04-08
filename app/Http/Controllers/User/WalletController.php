<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // Get recent transactions
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Total deposits and withdrawals
        $totalDeposits = Deposit::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawals = Withdrawal::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Get USDT Rate
        $usdtRate = CurrencyRate::where('from_currency', 'USD')
            ->where('to_currency', 'BDT')
            ->value('rate') ?? 120;

        // Currency conversions
        $conversions = [];
        $currencies = ['USD', 'USDT'];
        
        foreach ($currencies as $currency) {
            $result = CurrencyRate::convert($wallet->main_balance ?? 0, 'BDT', $currency, false);
            if ($result) {
                $conversions[$currency] = $result['converted'];
            }
        }

        return view('user.wallet.index', compact(
            'wallet', 
            'transactions', 
            'conversions',
            'totalDeposits',
            'totalWithdrawals',
            'usdtRate'
        ));
    }

    public function transactions(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::where('user_id', $user->id)->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        $types = [
            'deposit' => 'Deposits',
            'withdrawal' => 'Withdrawals',
            'task_earning' => 'Task Earnings',
            'referral_bonus' => 'Referral Bonuses',
        ];

        return view('user.wallet.transactions', compact('transactions', 'types'));
    }

    public function convert()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // Get all currency rates
        $rates = CurrencyRate::where('is_active', true)->get();

        // Get USDT Rate
        $usdtRate = CurrencyRate::where('from_currency', 'USD')
            ->where('to_currency', 'BDT')
            ->value('rate') ?? 120;

        return view('user.wallet.convert', compact('wallet', 'rates', 'usdtRate'));
    }
}
