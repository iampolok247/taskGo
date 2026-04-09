@extends('layouts.agent')

@section('title', 'Deposits')
@section('header-title', 'Deposit History')

@section('content')
<div class="p-4 space-y-4">
    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl p-4 text-white">
        <p class="text-emerald-100 text-sm">Current Balance</p>
        <p class="text-3xl font-bold mt-1">{{ format_currency($wallet->main_balance ?? 0) }}</p>
        <div class="flex items-center gap-4 mt-3 text-sm">
            <div>
                <p class="text-emerald-200">Total Earnings</p>
                <p class="font-semibold">{{ format_currency($agent->total_earnings ?? 0) }}</p>
            </div>
            <div>
                <p class="text-emerald-200">Pending</p>
                <p class="font-semibold">{{ format_currency($agent->pending_earnings ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Deposit Button -->
    <a href="{{ route('agent.deposits.create') }}" 
        class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Make Deposit
    </a>

    <!-- Deposit History -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Deposit History</h3>
        
        @forelse($deposits as $deposit)
            <a href="{{ route('agent.deposits.show', $deposit->id) }}" 
                class="flex items-center justify-between p-4 border-b last:border-0 hover:bg-gray-50 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center 
                        @if($deposit->status == 'approved') bg-green-100
                        @elseif($deposit->status == 'rejected') bg-red-100
                        @else bg-yellow-100 @endif">
                        @if($deposit->status == 'approved')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($deposit->status == 'rejected')
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ strtoupper($deposit->method) }}</p>
                        <p class="text-sm text-gray-500">{{ $deposit->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">{{ format_currency($deposit->amount) }}</p>
                    <span class="text-xs px-2 py-1 rounded-full 
                        @if($deposit->status == 'approved') bg-green-100 text-green-700
                        @elseif($deposit->status == 'rejected') bg-red-100 text-red-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ ucfirst($deposit->status) }}
                    </span>
                </div>
            </a>
        @empty
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">No deposits yet</p>
                <a href="{{ route('agent.deposits.create') }}" class="text-emerald-500 font-medium mt-2 inline-block">Make your first deposit</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($deposits->hasPages())
        <div class="mt-4">
            {{ $deposits->links() }}
        </div>
    @endif
</div>
@endsection
