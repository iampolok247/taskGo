@extends('layouts.agent')

@section('title', 'User: ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('agent.users.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($user->status) }}
        </span>
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
            <p class="text-gray-500 text-sm">Main Balance</p>
            <p class="text-xl font-bold text-primary-600">৳{{ number_format($stats['main_balance'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Tasks Completed</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['tasks_completed'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Your Commission</p>
            <p class="text-xl font-bold text-blue-600">৳{{ number_format($commissionEarned, 2) }}</p>
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
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
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
                        <span class="text-gray-500">Joined</span>
                        <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Wallet Details</h3>
            @if($user->wallet)
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-4 text-white">
                    <p class="text-sm opacity-90">Current Balance</p>
                    <p class="text-2xl font-bold">৳{{ number_format($user->wallet->main_balance ?? 0, 2) }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500 text-sm">Main Balance</p>
                        <p class="font-bold text-gray-900">৳{{ number_format($user->wallet->main_balance ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500 text-sm">Pending Balance</p>
                        <p class="font-bold text-gray-900">৳{{ number_format($user->wallet->pending_balance ?? 0, 2) }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500 text-sm">Total Earned</p>
                        <p class="font-bold text-green-600">৳{{ number_format($user->wallet->total_earned ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500 text-sm">Total Withdrawn</p>
                        <p class="font-bold text-red-600">৳{{ number_format($user->wallet->total_withdrawn ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No wallet information</p>
            @endif
        </div>

        <!-- Commission Earned -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Your Earnings from User</h3>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white text-center">
                <p class="text-sm opacity-90 mb-1">Total Commission Earned</p>
                <p class="text-3xl font-bold">৳{{ number_format($commissionEarned, 2) }}</p>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Commission is earned when this user makes deposits.</p>
            </div>
        </div>
    </div>

    <!-- Recent Deposits & Withdrawals -->
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
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No deposits yet</td>
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
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No withdrawals yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
