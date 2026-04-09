@extends('layouts.agent')

@section('title', 'Deposit Details')
@section('header-title', 'Deposit Details')

@section('content')
<div class="p-4 space-y-4">
    <!-- Status Card -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center 
                    @if($deposit->status == 'approved') bg-green-100
                    @elseif($deposit->status == 'rejected') bg-red-100
                    @else bg-yellow-100 @endif">
                    @if($deposit->status == 'approved')
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @elseif($deposit->status == 'rejected')
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Deposit #{{ $deposit->id }}</p>
                    <span class="text-xs px-2 py-1 rounded-full 
                        @if($deposit->status == 'approved') bg-green-100 text-green-700
                        @elseif($deposit->status == 'rejected') bg-red-100 text-red-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ ucfirst($deposit->status) }}
                    </span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ format_currency($deposit->amount) }}</p>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Deposit Information</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Method</span>
                <span class="font-medium text-gray-900">{{ strtoupper($deposit->method) }}</span>
            </div>
            
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Amount</span>
                <span class="font-medium text-gray-900">{{ format_currency($deposit->amount) }}</span>
            </div>
            
            @if($deposit->transaction_id)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Transaction ID</span>
                <span class="font-medium text-gray-900">{{ $deposit->transaction_id }}</span>
            </div>
            @endif
            
            @if($deposit->sender_account)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Sender Account</span>
                <span class="font-medium text-gray-900">{{ $deposit->sender_account }}</span>
            </div>
            @endif
            
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Submitted</span>
                <span class="font-medium text-gray-900">{{ $deposit->created_at->format('M d, Y H:i') }}</span>
            </div>
            
            @if($deposit->status != 'pending' && $deposit->updated_at != $deposit->created_at)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Processed</span>
                <span class="font-medium text-gray-900">{{ $deposit->updated_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Proof Image -->
    @if($deposit->proof_image)
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Payment Proof</h3>
        <img src="{{ route('storage.serve', ['path' => $deposit->proof_image]) }}" 
            alt="Payment Proof" 
            class="w-full rounded-lg">
    </div>
    @endif

    <!-- Rejection Note -->
    @if($deposit->status == 'rejected' && $deposit->admin_note)
    <div class="bg-red-50 rounded-xl p-4">
        <h4 class="font-semibold text-red-800 mb-2">Rejection Reason</h4>
        <p class="text-sm text-red-700">{{ $deposit->admin_note }}</p>
    </div>
    @endif

    <!-- Back Button -->
    <a href="{{ route('agent.deposits.index') }}" 
        class="flex items-center justify-center gap-2 w-full py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Deposits
    </a>
</div>
@endsection
