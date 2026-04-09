<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        
        $deposits = Deposit::where('agent_id', $agent->id)
            ->latest()
            ->paginate(15);

        return view('agent.deposits.index', compact('deposits'));
    }

    public function create()
    {
        $agent = Auth::guard('agent')->user();
        $wallet = $agent->wallet;
        
        $paymentMethods = PaymentMethod::active()
            ->forDeposit()
            ->ordered()
            ->get();

        $recentDeposits = Deposit::where('agent_id', $agent->id)
            ->latest()
            ->take(5)
            ->get();

        return view('agent.deposits.create', compact('paymentMethods', 'wallet', 'recentDeposits'));
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        // Get valid method codes from database
        $validMethods = PaymentMethod::active()->forDeposit()->pluck('code')->toArray();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'method' => 'required|in:' . implode(',', $validMethods),
            'transaction_id' => 'nullable|string|max:100',
            'sender_account' => 'nullable|string|max:100',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get payment method details
        $paymentMethod = PaymentMethod::where('code', $validated['method'])->first();

        if ($paymentMethod && !$paymentMethod->isValidAmount($validated['amount'])) {
            return back()->withErrors([
                'amount' => 'Amount must be between ' . format_currency($paymentMethod->min_amount) . ' and ' . format_currency($paymentMethod->max_amount ?? 999999),
            ])->withInput();
        }

        // Upload proof image
        $proofPath = $request->file('proof_image')
            ->store('deposit-proofs/agents/' . $agent->id, 'public');

        $deposit = Deposit::create([
            'agent_id' => $agent->id,
            'amount' => $validated['amount'],
            'currency' => 'USD',
            'method' => $validated['method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'sender_account' => $validated['sender_account'] ?? null,
            'proof_image' => $proofPath,
            'notes' => $validated['notes'] ?? null,
            'receiver_account' => $paymentMethod?->account_number ?? null,
        ]);

        return redirect()->route('agent.deposits.index')
            ->with('success', 'Deposit request submitted successfully! Waiting for approval.');
    }

    public function show(Deposit $deposit)
    {
        $agent = Auth::guard('agent')->user();
        
        if ($deposit->agent_id !== $agent->id) {
            abort(403);
        }

        return view('agent.deposits.show', compact('deposit'));
    }
}
