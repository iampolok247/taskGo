<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $query = User::where('agent_id', $agent->id)->with('wallet');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);

        $stats = [
            'total' => $agent->users()->count(),
            'active' => $agent->users()->where('status', 'active')->count(),
            'inactive' => $agent->users()->where('status', '!=', 'active')->count(),
            'this_month' => $agent->users()->whereMonth('created_at', now()->month)->count()
        ];

        return view('agent.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('agent.users.create');
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'agent_id' => $agent->id,
        ]);

        return redirect()->route('agent.users.index')
            ->with('success', 'Freelancer created successfully!');
    }

    public function show(User $user)
    {
        $agent = Auth::guard('agent')->user();

        if ($user->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $user->load(['wallet', 'deposits', 'withdrawals']);

        // User statistics
        $stats = [
            'total_deposits' => $user->deposits()->approved()->sum('amount'),
            'total_withdrawals' => $user->withdrawals()->completed()->sum('amount'),
            'tasks_completed' => $user->total_tasks_completed,
            'main_balance' => $user->wallet->main_balance ?? 0,
        ];

        // Commission earned from this user
        $commissionEarned = Commission::where('agent_id', $agent->id)
            ->where('user_id', $user->id)
            ->sum('commission_amount');

        return view('agent.users.show', compact('user', 'stats', 'commissionEarned'));
    }
}
