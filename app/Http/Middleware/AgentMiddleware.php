<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('agent')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('agent.login');
        }

        $agent = Auth::guard('agent')->user();

        if ($agent->status !== 'active') {
            Auth::guard('agent')->logout();
            $message = $agent->status === 'blocked' 
                ? 'Your account has been blocked.' 
                : 'Your account is inactive.';
            return redirect()->route('agent.login')
                ->with('error', $message);
        }

        return $next($request);
    }
}
