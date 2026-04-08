<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = Agent::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('agent_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agents = $query->latest()->paginate(20);

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'commission_rate' => 'required|numeric|min:0|max:100',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $agent = Agent::create($validated);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent created successfully! Agent Code: ' . $agent->agent_code);
    }

    public function show(Agent $agent)
    {
        $agent->load('users');

        $stats = [
            'total_users' => $agent->users()->count(),
            'active_users' => $agent->users()->where('status', 'active')->count(),
            'total_earnings' => $agent->total_earnings,
            'pending_earnings' => $agent->pending_earnings,
            'withdrawn_earnings' => $agent->withdrawn_earnings,
        ];

        $commissions = Commission::with(['user', 'deposit'])
            ->where('agent_id', $agent->id)
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = $agent->users()->latest()->take(10)->get();

        return view('admin.agents.show', compact('agent', 'stats', 'commissions', 'recentUsers'));
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $agent->id,
            'phone' => 'nullable|string|max:20',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'address' => 'nullable|string|max:500',
        ]);

        $agent->update($validated);

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', 'Agent updated successfully!');
    }

    public function toggleStatus(Agent $agent)
    {
        $newStatus = $agent->status === 'active' ? 'blocked' : 'active';
        $agent->update(['status' => $newStatus]);

        return back()->with('success', 'Agent status updated to ' . $newStatus);
    }

    public function resetPassword(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $agent->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password reset successfully!');
    }
}
