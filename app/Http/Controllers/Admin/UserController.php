<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['wallet', 'agent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        $users = $query->latest()->paginate(20);
        $agents = Agent::where('status', 'active')->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users.index', compact('users', 'agents', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['wallet', 'agent', 'deposits', 'withdrawals', 'taskSubmissions']);

        $stats = [
            'total_deposits' => $user->deposits()->approved()->sum('amount'),
            'total_withdrawals' => $user->withdrawals()->completed()->sum('amount'),
            'total_earned' => $user->wallet->total_earned ?? 0,
            'tasks_completed' => $user->total_tasks_completed,
            'referrals' => $user->total_referrals,
        ];

        $recentTransactions = $user->transactions()->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'stats', 'recentTransactions'));
    }

    public function edit(User $user)
    {
        $agents = Agent::where('status', 'active')->get();
        return view('admin.users.edit', compact('user', 'agents'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,blocked',
            'agent_id' => 'nullable|exists:agents,id',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'blocked' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', 'User status updated to ' . $newStatus);
    }

    public function adjustBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'type' => 'required|in:add,deduct',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $wallet = $user->wallet;
        $amount = $validated['amount'];

        if ($validated['type'] === 'add') {
            $wallet->addToMain($amount);
            $description = 'Admin adjustment (Add): ' . $validated['reason'];
        } else {
            if ($wallet->main_balance < $amount) {
                return back()->withErrors(['amount' => 'Insufficient balance for deduction.']);
            }
            $wallet->deductFromMain($amount);
            $amount = -$amount;
            $description = 'Admin adjustment (Deduct): ' . $validated['reason'];
        }

        // Create transaction record
        $user->transactions()->create([
            'transaction_id' => 'ADJ' . strtoupper(uniqid()),
            'type' => 'adjustment',
            'amount' => $amount,
            'currency' => 'BDT',
            'status' => 'completed',
            'description' => $description,
            'transactionable_type' => User::class,
            'transactionable_id' => $user->id,
            'balance_before' => $wallet->main_balance - $amount,
            'balance_after' => $wallet->main_balance,
        ]);

        return back()->with('success', 'Balance adjusted successfully!');
    }

    public function destroy(User $user)
    {
        $userName = $user->name;

        // Delete related records
        $user->deposits()->delete();
        $user->withdrawals()->delete();
        $user->transactions()->delete();
        $user->taskSubmissions()->delete();
        $user->notifications()->delete();

        // Delete wallet
        if ($user->wallet) {
            $user->wallet->delete();
        }

        // Delete referrals where user is referrer or referred
        \App\Models\Referral::where('referrer_id', $user->id)
            ->orWhere('referred_id', $user->id)
            ->delete();

        // Delete the user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' has been permanently deleted.");
    }
}
