@extends('layouts.user')

@section('title', 'Withdraw')

@section('content')
<div class="p-4 space-y-4">
    <!-- Withdrawal Balance Info -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Withdrawable Earnings</p>
                <p class="text-2xl font-bold text-green-600">{{ format_currency($wallet->withdrawable_balance) }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-3 grid grid-cols-3 gap-2 text-center">
            <div class="bg-blue-50 rounded-lg p-2">
                <p class="text-xs text-blue-600">Total Earned</p>
                <p class="text-sm font-bold text-blue-700">{{ format_currency($wallet->total_earned) }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-2">
                <p class="text-xs text-red-600">Already Withdrawn</p>
                <p class="text-sm font-bold text-red-700">{{ format_currency($wallet->total_withdrawn) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-xs text-gray-600">Total Balance</p>
                <p class="text-sm font-bold text-gray-700">{{ format_currency($wallet->main_balance) }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Min: {{ format_currency($minWithdrawal) }} | Max: {{ format_currency($maxWithdrawal) }}</p>
        <div class="mt-2 p-2 bg-yellow-50 rounded-lg">
            <p class="text-xs text-yellow-700"><strong>Note:</strong> You can only withdraw from your task earnings. Deposited funds cannot be withdrawn.</p>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Request Withdrawal</h3>
        
        <form action="{{ route('user.withdrawals.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Currency Selection -->
            @if(isset($currencies) && $currencies->count() > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Currency</label>
                <select name="withdrawal_currency" id="withdrawal_currency" onchange="updateCurrencyInfo()"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @foreach($currencies as $curr)
                        <option value="{{ $curr->code }}" 
                            data-symbol="{{ $curr->symbol }}" 
                            data-rate="{{ $curr->exchange_rate }}"
                            {{ old('withdrawal_currency', 'BDT') == $curr->code ? 'selected' : '' }}>
                            {{ $curr->code }} - {{ $curr->name }} ({{ $curr->symbol }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Select the currency you want to receive</p>
            </div>
            @endif
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" id="currency-symbol">{{ currency_symbol() }}</span>
                    <input type="number" name="amount" id="withdraw-amount" value="{{ old('amount') }}" 
                        min="{{ $minWithdrawal }}" 
                        step="0.01"
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Enter amount" oninput="updateCurrencyInfo()">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Conversion Preview -->
            <div id="conversion-preview" class="hidden p-3 bg-blue-50 rounded-xl border border-blue-200">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-800">Currency Conversion</span>
                </div>
                <p class="text-sm text-blue-700">
                    <span id="original-amount"></span> = <strong id="converted-amount"></strong>
                </p>
                <p class="text-xs text-blue-600 mt-1">Rate: 1 USD = <span id="exchange-rate"></span></p>
            </div>
            
            <!-- Quick Amounts -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach([500, 1000, 2000, 5000] as $quickAmount)
                        <button type="button" onclick="setAmount({{ $quickAmount }})" 
                            class="quick-btn py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            <span class="q-sym">{{ currency_symbol() }}</span>{{ number_format($quickAmount) }}
                        </button>
                    @endforeach
                </div>
            </div>
            
            <!-- Withdraw All Earnings -->
            @if($wallet->withdrawable_balance > 0)
            <button type="button" onclick="setAllEarnings()" 
                class="w-full py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100 transition-all border border-green-200">
                Withdraw All Earnings ({{ format_currency($wallet->withdrawable_balance) }})
            </button>
            @endif
            
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
                    <span class="text-sm font-medium text-gray-900" id="withdrawal-amount">$0.00</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Processing Fee</span>
                    <span class="text-sm font-medium text-gray-900" id="processing-fee">$0.00</span>
                </div>
                <div class="border-t border-gray-200 my-2"></div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900">You Will Receive</span>
                    <span class="text-lg font-bold text-green-600" id="receive-amount">$0.00</span>
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all active:scale-[0.98]"
                {{ $wallet->withdrawable_balance <= 0 ? 'disabled' : '' }}>
                {{ $wallet->withdrawable_balance <= 0 ? 'No Earnings to Withdraw' : 'Request Withdrawal' }}
            </button>
        </form>
    </div>

    <!-- Important Notes -->
    <div class="bg-yellow-50 rounded-xl p-4">
        <h4 class="font-medium text-yellow-800 mb-2">Important Notes</h4>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Only your <strong>task earnings</strong> can be withdrawn</li>
            <li>• Deposited funds are for task purchases only and cannot be withdrawn</li>
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
                            @if($withdrawal->status == 'approved' || $withdrawal->status == 'completed') bg-green-100
                            @elseif($withdrawal->status == 'pending') bg-yellow-100
                            @else bg-red-100 @endif">
                            @if($withdrawal->status == 'approved' || $withdrawal->status == 'completed')
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
                            <p class="text-sm font-medium text-gray-900">{{ $withdrawal->paymentMethod->name ?? ucfirst($withdrawal->method) }} - {{ $withdrawal->account_number }}</p>
                            <p class="text-xs text-gray-500">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ format_currency($withdrawal->amount) }}</p>
                            @if($withdrawal->converted_amount)
                                <p class="text-xs text-gray-500">{{ number_format($withdrawal->converted_amount, 2) }} {{ $withdrawal->converted_currency }}</p>
                            @endif
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($withdrawal->status == 'approved' || $withdrawal->status == 'completed') bg-green-100 text-green-700
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
const maxWithdrawableUSD = {{ $wallet->withdrawable_balance }};

function getSelectedCurrency() {
    var sel = document.getElementById('withdrawal_currency');
    if (!sel) return { code: 'USD', symbol: '$', rate: 1 };
    var opt = sel.options[sel.selectedIndex];
    return {
        code: opt.value,
        symbol: opt.dataset.symbol || '$',
        rate: parseFloat(opt.dataset.rate) || 1
    };
}

function number_format(num, decimals) {
    return parseFloat(num).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function setAmount(amount) {
    document.getElementById('withdraw-amount').value = amount;
    updateCurrencyInfo();
}

function setAllEarnings() {
    var c = getSelectedCurrency();
    // Convert USD withdrawable to selected currency
    var amount = maxWithdrawableUSD * c.rate;
    document.getElementById('withdraw-amount').value = Math.floor(amount * 100) / 100;
    updateCurrencyInfo();
}

function updateCurrencyInfo() {
    var c = getSelectedCurrency();
    var amount = parseFloat(document.getElementById('withdraw-amount').value) || 0;
    
    // Update currency symbol
    document.getElementById('currency-symbol').textContent = c.symbol;
    
    // Update quick buttons
    document.querySelectorAll('.q-sym').forEach(function(el) { el.textContent = c.symbol; });

    // Calculate USD equivalent
    var usdAmount = (c.code !== 'USD' && c.rate > 0) ? amount / c.rate : amount;

    // Show conversion preview
    var preview = document.getElementById('conversion-preview');
    if (amount > 0 && c.code !== 'USD') {
        document.getElementById('original-amount').textContent = c.symbol + number_format(amount, 2) + ' ' + c.code;
        document.getElementById('converted-amount').textContent = '$' + number_format(usdAmount, 2) + ' USD';
        document.getElementById('exchange-rate').textContent = c.symbol + number_format(c.rate, 4) + ' ' + c.code;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }

    // Update summary
    var fee = (usdAmount * feePercent / 100);
    var receive = usdAmount - fee;
    
    document.getElementById('withdrawal-amount').textContent = '$' + number_format(usdAmount, 2);
    document.getElementById('processing-fee').textContent = '$' + number_format(fee, 2);
    document.getElementById('receive-amount').textContent = '$' + number_format(receive, 2);
}

document.addEventListener('DOMContentLoaded', function() {
    updateCurrencyInfo();
});
</script>
@endsection
