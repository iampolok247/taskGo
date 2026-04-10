@extends('layouts.agent')

@section('title', 'Request Withdrawal')
@section('header-title', 'Request Withdrawal')

@section('content')
<div class="p-4 space-y-4">
    <!-- Withdrawal Balance Info -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Withdrawable Earnings</p>
                <p class="text-2xl font-bold text-emerald-600">{{ format_currency($wallet->withdrawable_balance ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-3 grid grid-cols-3 gap-2 text-center">
            <div class="bg-blue-50 rounded-lg p-2">
                <p class="text-xs text-blue-600">Total Earned</p>
                <p class="text-sm font-bold text-blue-700">{{ format_currency($wallet->total_earned ?? 0) }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-2">
                <p class="text-xs text-red-600">Already Withdrawn</p>
                <p class="text-sm font-bold text-red-700">{{ format_currency($wallet->total_withdrawn ?? 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-xs text-gray-600">Total Balance</p>
                <p class="text-sm font-bold text-gray-700">{{ format_currency($wallet->main_balance ?? 0) }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Min: {{ format_currency($minWithdrawal) }} | Max: {{ format_currency($maxWithdrawal) }}</p>
        <div class="mt-2 p-2 bg-yellow-50 rounded-lg">
            <p class="text-xs text-yellow-700"><strong>Note:</strong> You can only withdraw from your commission earnings. Deposited funds cannot be withdrawn.</p>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Request Withdrawal</h3>
        
        <form action="{{ route('agent.withdrawals.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Currency Selection -->
            @if(isset($currencies) && $currencies->count() > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Currency</label>
                <select name="withdrawal_currency" id="withdrawal_currency" onchange="updateCurrencyInfo()"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
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
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter amount" oninput="updateCurrencyInfo()">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Conversion Preview -->
            <div id="conversion-preview" class="hidden p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span class="text-sm font-medium text-emerald-800">Currency Conversion</span>
                </div>
                <p class="text-sm text-emerald-700">
                    <span id="original-amount"></span> = <strong id="converted-amount"></strong>
                </p>
                <p class="text-xs text-emerald-600 mt-1">Rate: 1 USD = <span id="exchange-rate"></span></p>
            </div>
            
            <!-- Quick Amounts -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
                <div class="grid grid-cols-4 gap-2">
                    @php $wBalance = $wallet->withdrawable_balance ?? 0; @endphp
                    @foreach([50, 100, 500, 1000] as $quickAmount)
                        <button type="button" onclick="setAmount({{ $quickAmount }})" 
                            class="quick-btn py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            <span class="q-sym">{{ currency_symbol() }}</span>{{ number_format($quickAmount) }}
                        </button>
                    @endforeach
                </div>
                @if($wBalance > 0)
                <button type="button" onclick="setAllEarnings()" 
                    class="mt-2 w-full py-2 border border-emerald-500 text-emerald-500 rounded-lg text-sm font-medium hover:bg-emerald-50 transition-all">
                    Withdraw All Earnings ({{ format_currency($wBalance) }})
                </button>
                @endif
            </div>
            
            <!-- Withdrawal Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Method</label>
                <select name="method" id="method" onchange="toggleBankFields()"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Select Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->code }}" {{ old('method') == $method->code ? 'selected' : '' }}>
                            {{ $method->name }}
                        </option>
                    @endforeach
                    <option value="bank" {{ old('method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
                @error('method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Account Number (for mobile methods) -->
            <div id="account-field">
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                <input type="text" name="account_number" value="{{ old('account_number') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    placeholder="Enter your account number">
                @error('account_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Name -->
            <div id="account-name-field">
                <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                <input type="text" name="account_name" value="{{ old('account_name') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    placeholder="Enter account holder name">
                @error('account_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Bank Details -->
            <div id="bank-fields" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter bank name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name</label>
                    <input type="text" name="branch_name" value="{{ old('branch_name') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter branch name (optional)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Routing Number (Optional)</label>
                    <input type="text" name="routing_number" value="{{ old('routing_number') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter routing number">
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all"
                {{ ($wallet->withdrawable_balance ?? 0) <= 0 ? 'disabled' : '' }}>
                {{ ($wallet->withdrawable_balance ?? 0) <= 0 ? 'No Earnings to Withdraw' : 'Submit Withdrawal Request' }}
            </button>
        </form>
    </div>
    
    <!-- Important Notes -->
    <div class="bg-yellow-50 rounded-xl p-4">
        <h4 class="font-semibold text-yellow-800 mb-2">Important Notes</h4>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Only your <strong>commission earnings</strong> can be withdrawn</li>
            <li>• Deposited funds cannot be withdrawn</li>
            <li>• Withdrawals are processed within 24-48 hours</li>
            <li>• Minimum withdrawal amount is {{ format_currency($minWithdrawal) }}</li>
            <li>• Make sure your account details are correct</li>
        </ul>
    </div>
</div>

<script>
const maxWithdrawableUSD = {{ $wallet->withdrawable_balance ?? 0 }};

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
    var amount = maxWithdrawableUSD * c.rate;
    document.getElementById('withdraw-amount').value = Math.floor(amount * 100) / 100;
    updateCurrencyInfo();
}

function updateCurrencyInfo() {
    var c = getSelectedCurrency();
    var amount = parseFloat(document.getElementById('withdraw-amount').value) || 0;
    
    document.getElementById('currency-symbol').textContent = c.symbol;
    document.querySelectorAll('.q-sym').forEach(function(el) { el.textContent = c.symbol; });

    var usdAmount = (c.code !== 'USD' && c.rate > 0) ? amount / c.rate : amount;

    var preview = document.getElementById('conversion-preview');
    if (amount > 0 && c.code !== 'USD') {
        document.getElementById('original-amount').textContent = c.symbol + number_format(amount, 2) + ' ' + c.code;
        document.getElementById('converted-amount').textContent = '$' + number_format(usdAmount, 2) + ' USD';
        document.getElementById('exchange-rate').textContent = c.symbol + number_format(c.rate, 4) + ' ' + c.code;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

function toggleBankFields() {
    var method = document.getElementById('method').value;
    var bankFields = document.getElementById('bank-fields');
    
    if (method === 'bank' || method === 'bank_transfer') {
        bankFields.classList.remove('hidden');
    } else {
        bankFields.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleBankFields();
    updateCurrencyInfo();
});
</script>
@endsection
