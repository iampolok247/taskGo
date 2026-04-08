@extends('layouts.user')

@section('title', 'Withdraw')

@section('content')
<div class="p-4 space-y-4">
    <!-- Available Balance -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Available for Withdrawal</p>
                <p class="text-2xl font-bold text-gray-900">{{ format_currency($wallet->main_balance) }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Minimum withdrawal: {{ format_currency($minWithdrawal) }} | Maximum: {{ format_currency($maxWithdrawal) }}</p>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Request Withdrawal</h3>
        
        <form action="{{ route('user.withdrawals.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount ({{ currency_symbol() }})</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ currency_symbol() }}</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="{{ $minWithdrawal }}" max="{{ $maxWithdrawal }}" step="0.01"
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Enter amount">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Quick Amounts -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach([500, 1000, 2000, 5000] as $quickAmount)
                        @if($quickAmount <= $wallet->main_balance)
                            <button type="button" onclick="setAmount({{ $quickAmount }})" 
                                class="py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                                {{ format_currency($quickAmount, null, 0) }}
                            </button>
                        @else
                            <button type="button" disabled
                                class="py-2 border border-gray-100 rounded-lg text-sm font-medium text-gray-400 cursor-not-allowed">
                                {{ format_currency($quickAmount, null, 0) }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Withdraw All -->
            <button type="button" onclick="setAmount({{ $wallet->main_balance }})" 
                class="w-full py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-all">
                Withdraw All ({{ format_currency($wallet->main_balance) }})
            </button>
            
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Method</label>
                <div class="space-y-2">
                    @foreach($paymentMethods as $method)
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
                            <input type="radio" name="method" value="{{ $method->code }}" 
                                class="w-4 h-4 text-primary-500 focus:ring-primary-500"
                                {{ old('method') == $method->code ? 'checked' : '' }}>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $method->name }}</p>
                            </div>
                            @if($method->icon)
                                <img src="{{ asset('storage/' . $method->icon) }}" alt="{{ $method->name }}" class="w-10 h-10 object-contain">
                            @endif
                        </label>
                    @endforeach
                </div>
                @error('method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Account Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Account Number</label>
                <input type="text" name="account_number" value="{{ old('account_number') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Enter your account number">
                @error('account_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Account Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                <input type="text" name="account_name" value="{{ old('account_name', auth()->user()->name) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Name on account">
                @error('account_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Summary -->
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Withdrawal Amount</span>
                    <span class="text-sm font-medium text-gray-900" id="withdrawal-amount">{{ currency_symbol() }}0.00</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Processing Fee</span>
                    <span class="text-sm font-medium text-gray-900" id="processing-fee">{{ currency_symbol() }}0.00</span>
                </div>
                <div class="border-t border-gray-200 my-2"></div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900">You Will Receive</span>
                    <span class="text-lg font-bold text-green-600" id="receive-amount">{{ currency_symbol() }}0.00</span>
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all active:scale-[0.98]">
                Request Withdrawal
            </button>
        </form>
    </div>

    <!-- Important Notes -->
    <div class="bg-yellow-50 rounded-xl p-4">
        <h4 class="font-medium text-yellow-800 mb-2">Important Notes</h4>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Withdrawals are processed within 24-48 hours</li>
            <li>• Make sure your account details are correct</li>
            <li>• Minimum withdrawal amount is {{ format_currency($minWithdrawal) }}</li>
            <li>• Maximum withdrawal amount is {{ format_currency($maxWithdrawal) }}</li>
        </ul>
    </div>

    <!-- Recent Withdrawals -->
    @if($recentWithdrawals->count() > 0)
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Withdrawals</h3>
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
                @foreach($recentWithdrawals as $withdrawal)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($withdrawal->status == 'approved') bg-green-100
                            @elseif($withdrawal->status == 'pending') bg-yellow-100
                            @else bg-red-100 @endif">
                            @if($withdrawal->status == 'approved')
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($withdrawal->status == 'pending')
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $withdrawal->paymentMethod->name ?? 'Unknown' }} - {{ $withdrawal->account_number }}</p>
                            <p class="text-xs text-gray-500">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ format_currency($withdrawal->amount) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($withdrawal->status == 'approved') bg-green-100 text-green-700
                                @elseif($withdrawal->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
const feePercent = {{ $withdrawalFee ?? 0 }};
const currencySymbol = '{{ currency_symbol() }}';

function setAmount(amount) {
    document.querySelector('input[name="amount"]').value = amount;
    updateSummary(amount);
}

document.querySelector('input[name="amount"]').addEventListener('input', function(e) {
    updateSummary(e.target.value);
});

function updateSummary(amount) {
    amount = parseFloat(amount) || 0;
    const fee = (amount * feePercent / 100);
    const receive = amount - fee;
    
    document.getElementById('withdrawal-amount').textContent = currencySymbol + amount.toFixed(2);
    document.getElementById('processing-fee').textContent = currencySymbol + fee.toFixed(2);
    document.getElementById('receive-amount').textContent = currencySymbol + receive.toFixed(2);
}
</script>
@endsection
