@extends('layouts.user')

@section('title', 'Transactions')

@section('content')
<div class="p-4 space-y-4">
    <!-- Filter Tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <a href="{{ route('user.wallet.transactions') }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ !request('type') ? 'bg-primary-500 text-white' : 'bg-white text-gray-600' }}">
            All
        </a>
        <a href="{{ route('user.wallet.transactions', ['type' => 'task_reward']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('type') == 'task_reward' ? 'bg-green-500 text-white' : 'bg-white text-gray-600' }}">
            Task Rewards
        </a>
        <a href="{{ route('user.wallet.transactions', ['type' => 'deposit']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('type') == 'deposit' ? 'bg-blue-500 text-white' : 'bg-white text-gray-600' }}">
            Deposits
        </a>
        <a href="{{ route('user.wallet.transactions', ['type' => 'withdrawal']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('type') == 'withdrawal' ? 'bg-orange-500 text-white' : 'bg-white text-gray-600' }}">
            Withdrawals
        </a>
        <a href="{{ route('user.wallet.transactions', ['type' => 'referral_bonus']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('type') == 'referral_bonus' ? 'bg-purple-500 text-white' : 'bg-white text-gray-600' }}">
            Referral
        </a>
    </div>

    <!-- Transaction List -->
    @if($transactions->count() > 0)
        <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
            @foreach($transactions as $transaction)
                <div class="p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                        @if($transaction->type == 'task_reward') bg-green-100
                        @elseif($transaction->type == 'deposit') bg-blue-100
                        @elseif($transaction->type == 'withdrawal') bg-orange-100
                        @elseif($transaction->type == 'referral_bonus') bg-purple-100
                        @else bg-gray-100 @endif">
                        @if($transaction->type == 'task_reward')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($transaction->type == 'deposit')
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0-16l-4 4m4-4l4 4"></path>
                            </svg>
                        @elseif($transaction->type == 'withdrawal')
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m0 16l4-4m-4 4l-4-4"></path>
                            </svg>
                        @elseif($transaction->type == 'referral_bonus')
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $transaction->description }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</span>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->amount > 0 ? '+' : '' }}{{ format_currency(abs($transaction->amount)) }}
                        </p>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            @if($transaction->status == 'completed') bg-green-100 text-green-700
                            @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-8 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Transactions Found</h3>
            <p class="text-gray-500">No transactions match your filter criteria.</p>
        </div>
    @endif
</div>
@endsection
