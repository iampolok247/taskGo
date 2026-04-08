@extends('layouts.user')

@section('title', 'Wallet')

@section('content')
<div class="p-4 space-y-4">
    <!-- Main Wallet Balance Card -->
    <div class="bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 rounded-2xl p-5 text-white shadow-xl relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-primary-100 text-xs uppercase tracking-wide">💰 Main Balance</p>
                    <h2 class="text-3xl font-bold mt-1">{{ format_currency($wallet->main_balance ?? 0) }}</h2>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <span class="text-2xl">💳</span>
                </div>
            </div>
            
            <!-- Sub Balances -->
            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                    <p class="text-primary-200 text-[10px] uppercase tracking-wide">⏳ Pending</p>
                    <p class="font-bold text-lg mt-1">{{ format_currency($wallet->pending_balance ?? 0) }}</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                    <p class="text-primary-200 text-[10px] uppercase tracking-wide">💸 Withdrawable</p>
                    <p class="font-bold text-lg mt-1">{{ format_currency($wallet->main_balance ?? 0) }}</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                    <p class="text-primary-200 text-[10px] uppercase tracking-wide">🎁 Total Deposited</p>
                    <p class="font-bold text-lg mt-1">{{ format_currency($wallet->total_deposited ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('user.deposits.create') }}" class="flex items-center gap-3 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition-transform">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-sm">
                <span class="text-xl">📥</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900">Deposit</p>
                <p class="text-xs text-gray-500">Add funds</p>
            </div>
        </a>
        <a href="{{ route('user.withdrawals.create') }}" class="flex items-center gap-3 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition-transform">
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center shadow-sm">
                <span class="text-xl">📤</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900">Withdraw</p>
                <p class="text-xs text-gray-500">Cash out</p>
            </div>
        </a>
    </div>

    <!-- Payment Methods Section -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <span>🏦</span> Payment Methods
        </h3>
        
        <div class="space-y-3">
            <!-- Binance USDT -->
            <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">₿</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Binance USDT</p>
                    <p class="text-xs text-gray-500">TRC20/BEP20 Network</p>
                </div>
                <div class="px-3 py-1 bg-yellow-200 rounded-full">
                    <span class="text-xs font-medium text-yellow-800">Crypto</span>
                </div>
            </div>
            
            <!-- Bank Transfer -->
            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🏦</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Bank Transfer</p>
                    <p class="text-xs text-gray-500">Any Bangladesh Bank</p>
                </div>
                <div class="px-3 py-1 bg-blue-200 rounded-full">
                    <span class="text-xs font-medium text-blue-800">Bank</span>
                </div>
            </div>
            
            <!-- Mobile Banking -->
            <div class="flex items-center gap-3 p-3 bg-pink-50 rounded-xl border border-pink-100">
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📱</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Mobile Banking</p>
                    <p class="text-xs text-gray-500">bKash, Nagad, Rocket</p>
                </div>
                <div class="px-3 py-1 bg-pink-200 rounded-full">
                    <span class="text-xs font-medium text-pink-800">Mobile</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg">📈</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Deposits</p>
                    <p class="text-lg font-bold text-gray-900">{{ format_currency($totalDeposits ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg">📉</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Withdrawn</p>
                    <p class="text-lg font-bold text-gray-900">{{ format_currency($totalWithdrawals ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Earned Card -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-4 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-xs uppercase tracking-wide">🏆 Total Lifetime Earnings</p>
                <h3 class="text-2xl font-bold mt-1">{{ format_currency($wallet->total_earned ?? 0) }}</h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <span class="text-2xl">💰</span>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <span>📜</span> Recent Transactions
            </h3>
            <a href="{{ route('user.wallet.transactions') }}" class="text-sm text-primary-500 font-medium">View All →</a>
        </div>
        
        @if(isset($transactions) && $transactions->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                @foreach($transactions as $transaction)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $transaction->amount > 0 ? 'bg-green-100' : 'bg-red-100' }}">
                            @if($transaction->amount > 0)
                                <span class="text-lg">📥</span>
                            @else
                                <span class="text-lg">📤</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $transaction->description }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->amount > 0 ? '+' : '' }}{{ format_currency(abs($transaction->amount)) }}
                            </p>
                            <span class="text-[10px] px-2 py-0.5 rounded-full
                                @if($transaction->status == 'completed') bg-green-100 text-green-700
                                @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">📋</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Transactions Yet</h3>
                <p class="text-gray-500 text-sm">Complete tasks or deposit funds to get started!</p>
                <a href="{{ route('user.tasks.index') }}" class="inline-block mt-4 px-6 py-2 bg-primary-500 text-white rounded-xl font-medium text-sm hover:bg-primary-600 transition-colors">
                    Browse Tasks
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
