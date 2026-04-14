<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Commission;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();

        // Statistics
        $stats = [
            'total_users' => $agent->users()->count(),
            'active_users' => $agent->users()->where('status', 'active')->count(),
            'month_users' => $agent->users()->whereMonth('created_at', now()->month)->count(),
            'total_earnings' => $agent->total_earnings,
            'pending_earnings' => $agent->pending_earnings,
            'pending_commission' => \App\Models\Commission::where('agent_id', $agent->id)->where('status', 'pending')->sum('commission_amount'),
            'month_commission' => \App\Models\Commission::where('agent_id', $agent->id)->whereMonth('created_at', now()->month)->sum('commission_amount'),
            'withdrawn_earnings' => $agent->withdrawn_earnings,
            'commission_rate' => $agent->commission_rate,
            'total_commission' => \App\Models\Commission::where('agent_id', $agent->id)->sum('commission_amount'),
        ];

        // Recent commissions
        $recentCommissions = Commission::with(['user', 'deposit'])
            ->where('agent_id', $agent->id)
            ->latest()
            ->take(5)
            ->get();

        // Recent users
        $recentUsers = $agent->users()
            ->latest()
            ->take(5)
            ->get();

        // This month's earnings
        $monthlyEarnings = Commission::where('agent_id', $agent->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('commission_amount');

        // Announcements
        $announcements = Announcement::visible()
            ->forAgents()
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Referral stats for leader
        $referralLink = route('register', ['ref' => $agent->referral_code]);
        $totalReferrals = $agent->total_referrals ?? Referral::where('referrer_agent_id', $agent->id)->count();
        $referralEarnings = Referral::where('referrer_agent_id', $agent->id)->sum('bonus_amount') ?? 0;

        return view('agent.dashboard', compact(
            'agent',
            'stats',
            'recentCommissions',
            'recentUsers',
            'monthlyEarnings',
            'announcements',
            'referralLink',
            'totalReferrals',
            'referralEarnings'
        ));
    }
}
