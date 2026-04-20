<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShutdownNoticeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('/') || $request->is('storage/*') || $request->is('up')) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Service is currently stopped.',
            ], 503);
        }

        return redirect('/');
    }
}