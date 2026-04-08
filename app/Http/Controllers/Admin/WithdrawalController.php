<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('user');

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

        $withdrawals = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => Withdrawal::sum('amount'),
            'pending' => Withdrawal::pending()->count(),
            'pending_amount' => Withdrawal::pending()->sum('amount'),
            'approved' => Withdrawal::approved()->count(),
            'completed_today' => Withdrawal::completed()->whereDate('processed_at', today())->sum('final_amount'),
            'total_amount' => Withdrawal::completed()->sum('final_amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['user', 'processor']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if (!$withdrawal->isPending()) {
            return back()->with('error', 'This withdrawal has already been processed.');
        }

        $admin = Auth::guard('admin')->user();
        $withdrawal->approve($admin);

        return back()->with('success', 'Withdrawal approved!');
    }

    public function complete(Request $request, Withdrawal $withdrawal)
    {
        if (!$withdrawal->isApproved()) {
            return back()->with('error', 'This withdrawal must be approved first.');
        }

        $validated = $request->validate([
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')
                ->store('withdrawal-proofs', 'public');
        }

        $admin = Auth::guard('admin')->user();
        $withdrawal->complete($admin, $proofPath);

        return back()->with('success', 'Withdrawal marked as completed!');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->isCompleted()) {
            return back()->with('error', 'Cannot reject a completed withdrawal.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();
        $withdrawal->reject($admin, $validated['rejection_reason']);

        return back()->with('success', 'Withdrawal rejected and amount refunded.');
    }
}
