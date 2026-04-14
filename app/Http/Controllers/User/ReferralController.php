<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get referred users
        $referrals = Referral::with('referred')
            ->where('referrer_id', $user->id)
            ->latest()
            ->paginate(15);

        // Statistics
        $totalReferrals = $user->total_referrals ?? Referral::where('referrer_id', $user->id)->count();
        $activeReferrals = Referral::where('referrer_id', $user->id)->where('status', '!=', 'pending')->count();
        $totalEarnings = Referral::where('referrer_id', $user->id)->sum('bonus_amount') ?? 0;

        // Referral link
        $referralLink = route('register', ['ref' => $user->referral_code]);
        $signupBonus = \App\Models\Setting::getValue('referral_signup_bonus', 500);
        $taskCommissionPercent = \App\Models\Setting::getValue('referral_task_commission', 5);
        $minDeposit = \App\Models\Setting::getValue('referral_min_deposit', 500);

        return view('user.referrals.index', compact('user', 'referrals', 'totalReferrals', 'activeReferrals', 'totalEarnings', 'referralLink', 'signupBonus', 'taskCommissionPercent', 'minDeposit'));
    }
}
