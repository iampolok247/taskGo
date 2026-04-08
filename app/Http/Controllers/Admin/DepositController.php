<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $query = Deposit::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deposits = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => Deposit::sum('amount'),
            'total_amount' => Deposit::sum('amount'),
            'pending' => Deposit::pending()->count(),
            'pending_amount' => Deposit::pending()->sum('amount'),
            'approved_today' => Deposit::approved()->whereDate('reviewed_at', today())->sum('amount'),
            'approved' => Deposit::approved()->sum('amount'),
        ];

        return view('admin.deposits.index', compact('deposits', 'stats'));
    }

    public function show(Deposit $deposit)
    {
        $deposit->load(['user', 'reviewer']);

        return view('admin.deposits.show', compact('deposit'));
    }

    public function approve(Deposit $deposit)
    {
        if (!$deposit->isPending()) {
            return back()->with('error', 'This deposit has already been reviewed.');
        }

        $admin = Auth::guard('admin')->user();
        $deposit->approve($admin);

        return back()->with('success', 'Deposit approved successfully!');
    }

    public function reject(Request $request, Deposit $deposit)
    {
        if (!$deposit->isPending()) {
            return back()->with('error', 'This deposit has already been reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();
        $deposit->reject($admin, $validated['rejection_reason']);

        return back()->with('success', 'Deposit rejected.');
    }
}
