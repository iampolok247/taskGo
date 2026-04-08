<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

        return view('user.wallet.index', compact(
            'wallet', 
            'transactions', 
            'totalDeposits',
            'totalWithdrawals'
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
}
