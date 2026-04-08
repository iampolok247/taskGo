<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CurrencyRate;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // Dashboard statistics
        $stats = [
            'main_balance' => $wallet->main_balance ?? 0,
            'pending_balance' => $wallet->pending_balance ?? 0,
            'total_earned' => $wallet->total_earned ?? 0,
            'total_withdrawn' => $wallet->total_withdrawn ?? 0,
            'tasks_completed' => $user->total_tasks_completed ?? 0,
            'total_referrals' => $user->total_referrals ?? 0,
        ];

        // Today's earnings
        $todayEarnings = TaskSubmission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('reviewed_at', today())
            ->sum('reward_amount');

        // Available tasks count
        $availableTasks = Task::available()->count();

        // Pending submissions
        $pendingSubmissions = TaskSubmission::where('user_id', $user->id)
            ->pending()
            ->count();

        // Recent transactions
        $recentTransactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        // Announcements
        $announcements = Announcement::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('target')
                  ->orWhere('target', 'all')
                  ->orWhere('target', 'users');
            })
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Get USDT Rate
        $usdtRate = CurrencyRate::where('from_currency', 'USD')
            ->where('to_currency', 'BDT')
            ->value('rate') ?? 120;

        // Get Referral Bonus
        $referralBonus = Setting::where('key', 'referral_bonus')->value('value') ?? 50;

        return view('user.dashboard', compact(
            'user',
            'wallet',
            'stats',
            'todayEarnings',
            'availableTasks',
            'pendingSubmissions',
            'recentTransactions',
            'announcements',
            'usdtRate',
            'referralBonus'
        ));
    }
}
