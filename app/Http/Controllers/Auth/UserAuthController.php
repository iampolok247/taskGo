<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.user.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status === 'blocked') {
            return back()->withErrors([
                'email' => 'Your account has been blocked. Please contact support.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(Request $request)
    {
        $referralCode = $request->query('ref');
        return view('auth.user.register', compact('referralCode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'referral_code' => 'nullable|string',
        ]);

        // Determine referrer: check users first, then agents (leaders)
        $referrerId = null;
        $referrerAgentId = null;
        $referrerType = null;
        $agentId = null;

        if (!empty($validated['referral_code'])) {
            $code = $validated['referral_code'];

            // Check if referral code belongs to a User (Freelancer)
            $referrerUser = User::where('referral_code', $code)->first();
            if ($referrerUser) {
                $referrerId = $referrerUser->id;
                $referrerType = 'user';
            } else {
                // Check if referral code belongs to an Agent (Leader)
                $referrerAgent = Agent::where('referral_code', $code)->first();
                if ($referrerAgent) {
                    $referrerAgentId = $referrerAgent->id;
                    $referrerType = 'agent';
                    $agentId = $referrerAgent->id; // Also assign this user under the leader
                }
            }

            // If code doesn't match any user or agent, fail validation
            if (!$referrerId && !$referrerAgentId) {
                return back()->withErrors(['referral_code' => 'Invalid referral code.'])->withInput();
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'referred_by' => $referrerId,
            'agent_id' => $agentId,
        ]);

        // Create referral record if referred by someone (bonus will be paid after minimum deposit)
        if ($referrerType) {
            Referral::create([
                'referrer_id' => $referrerId,
                'referrer_type' => $referrerType,
                'referrer_agent_id' => $referrerAgentId,
                'referred_id' => $user->id,
                'status' => 'pending',
            ]);

            // Increment referral count for the referrer
            if ($referrerType === 'user' && $referrerId) {
                User::where('id', $referrerId)->increment('total_referrals');
            }
            if ($referrerType === 'agent' && $referrerAgentId) {
                Agent::where('id', $referrerAgentId)->increment('total_referrals');
            }
        }

        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Welcome to Task Go! Your account has been created.');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
