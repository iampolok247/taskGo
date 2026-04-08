@extends('layouts.admin')

@section('title', 'User: ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 {{ $user->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-xl transition-all">
                    {{ $user->status === 'active' ? 'Block User' : 'Activate User' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Deposits</p>
            <p class="text-xl font-bold text-green-600">৳{{ number_format($stats['total_deposits'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Withdrawals</p>
            <p class="text-xl font-bold text-red-600">৳{{ number_format($stats['total_withdrawals'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Earned</p>
            <p class="text-xl font-bold text-primary-600">৳{{ number_format($stats['total_earned'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Tasks Completed</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['tasks_completed'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Referrals</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['referrals'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">User Information</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : ($user->status === 'blocked' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="border-t pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="font-medium">{{ $user->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Referral Code</span>
                        <span class="font-mono text-primary-600">{{ $user->referral_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Agent</span>
                        <span class="font-medium">{{ $user->agent->name ?? 'Direct' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Joined</span>
                        <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Wallet</h3>
                <button onclick="document.getElementById('adjustBalanceModal').classList.remove('hidden')" class="text-sm text-primary-600 hover:text-primary-700">Adjust Balance</button>
            </div>
            @if($user->wallet)
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-4 text-white">
                    <p class="text-sm opacity-90">Current Balance</p>
                    <p class="text-2xl font-bold">৳{{ number_format($user->wallet->balance, 2) }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500 text-sm">Total Earned</p>
                        <p class="font-bold text-green-600">৳{{ number_format($user->wallet->total_earned, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500 text-sm">Total Withdrawn</p>
                        <p class="font-bold text-red-600">৳{{ number_format($user->wallet->total_withdrawn, 2) }}</p>
                    </div>
                </div>
            </div>
            @else
            <p class="text-gray-500">No wallet found</p>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Recent Transactions</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($recentTransactions as $transaction)
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <div>
                        <p class="font-medium text-sm">{{ ucfirst($transaction->type) }}</p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, H:i') }}</p>
                    </div>
                    <span class="{{ $transaction->type === 'credit' || $transaction->type === 'earning' ? 'text-green-600' : 'text-red-600' }} font-medium">
                        {{ $transaction->type === 'credit' || $transaction->type === 'earning' ? '+' : '-' }}৳{{ number_format($transaction->amount, 2) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No transactions yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Deposits & Withdrawals Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Deposits -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-900">Recent Deposits</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Method</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($user->deposits->take(5) as $deposit)
                        <tr>
                            <td class="px-4 py-3 font-medium">৳{{ number_format($deposit->amount, 2) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $deposit->payment_method }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-700' : ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($deposit->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-sm">{{ $deposit->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No deposits</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-900">Recent Withdrawals</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Method</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($user->withdrawals->take(5) as $withdrawal)
                        <tr>
                            <td class="px-4 py-3 font-medium">৳{{ number_format($withdrawal->amount, 2) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $withdrawal->payment_method }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-700' : ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-sm">{{ $withdrawal->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No withdrawals</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Adjust Balance Modal -->
<div id="adjustBalanceModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Adjust Balance</h3>
                <button onclick="document.getElementById('adjustBalanceModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.users.adjust-balance', $user) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="add">Add Balance</option>
                    <option value="deduct">Deduct Balance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount (৳)</label>
                <input type="number" name="amount" step="0.01" min="1" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                <textarea name="reason" rows="2" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Reason for adjustment..."></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Adjust Balance
            </button>
        </form>
    </div>
</div>
@endsection
