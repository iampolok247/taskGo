<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.agent.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('agent')->attempt($credentials, $request->boolean('remember'))) {
            $agent = Auth::guard('agent')->user();
            
            if ($agent->status !== 'active') {
                Auth::guard('agent')->logout();
                $message = $agent->status === 'blocked' 
                    ? 'Your account has been blocked.' 
                    : 'Your account is inactive.';
                return back()->withErrors(['email' => $message])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('agent.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agent.login');
    }
}
