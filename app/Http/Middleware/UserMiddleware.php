<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            $message = $user->status === 'blocked' 
                ? 'Your account has been blocked. Please contact support.' 
                : 'Your account is inactive.';
            return redirect()->route('login')
                ->with('error', $message);
        }

        // Update last login
        if (!$user->last_login_at || $user->last_login_at->diffInMinutes(now()) > 5) {
            $user->update(['last_login_at' => now()]);
        }

        return $next($request);
    }
}
