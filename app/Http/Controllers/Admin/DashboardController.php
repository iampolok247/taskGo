<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Deposit;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Main statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'total_agents' => Agent::count(),
            'active_agents' => Agent::where('status', 'active')->count(),
            'total_tasks' => Task::count(),
            'active_tasks' => Task::where('status', 'active')->count(),
            'pending_deposits' => \App\Models\Deposit::pending()->sum('amount'),
            'pending_deposit_amount' => \App\Models\Deposit::pending()->sum('amount'),
            'pending_withdrawals' => \App\Models\Withdrawal::pending()->count(),
            'pending_withdrawal_amount' => \App\Models\Withdrawal::pending()->sum('amount'),
            'pending_submissions' => \App\Models\TaskSubmission::where('status', 'pending')->count(),
            'today_paid' => \App\Models\TaskSubmission::where('status', 'approved')->whereDate('updated_at', today())->sum('reward_amount'),
            'today_completed_tasks' => \App\Models\TaskSubmission::where('status', 'approved')->whereDate('updated_at', today())->count(),
        ];

        // Financial statistics
        $financials = [
            'total_deposits' => Deposit::approved()->sum('amount'),
            'pending_deposits' => Deposit::pending()->sum('amount'),
            'total_withdrawals' => Withdrawal::completed()->sum('amount'),
            'pending_withdrawals' => Withdrawal::pending()->sum('amount'),
        ];

        // Pending counts
        $pending = [
            'deposits' => Deposit::pending()->count(),
            'withdrawals' => Withdrawal::pending()->count(),
            'task_submissions' => TaskSubmission::pending()->count(),
        ];

        // Recent activities
        $recentDeposits = Deposit::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentWithdrawals = Withdrawal::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentSubmissions = TaskSubmission::with(['user', 'task'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        // Chart data - Last 7 days
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact(
            'admin',
            'stats',
            'financials',
            'pending',
            'recentDeposits',
            'recentWithdrawals',
            'recentSubmissions',
            'chartData'
        ));
    }

    protected function getChartData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return now()->subDays($day)->format('Y-m-d');
        });

        $deposits = Deposit::approved()
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        $withdrawals = Withdrawal::completed()
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        $users = User::whereDate('created_at', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'labels' => $days->map(fn($d) => date('M d', strtotime($d)))->toArray(),
            'deposits' => $days->map(fn($d) => $deposits[$d] ?? 0)->toArray(),
            'withdrawals' => $days->map(fn($d) => $withdrawals[$d] ?? 0)->toArray(),
            'users' => $days->map(fn($d) => $users[$d] ?? 0)->toArray(),
        ];
    }
}
