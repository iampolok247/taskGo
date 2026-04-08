<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $query = Commission::with(['user', 'deposit'])
            ->where('agent_id', $agent->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $commissions = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => $agent->total_earnings,
            'total_earnings' => $agent->total_earnings,
            'pending' => $agent->pending_earnings,
            'paid' => Commission::where('agent_id', $agent->id)->paid()->sum('commission_amount'),
            'this_month' => Commission::where('agent_id', $agent->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('commission_amount'),
        ];

        return view('agent.commissions.index', compact('commissions', 'stats'));
    }
}
