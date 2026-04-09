@extends('layouts.agent')

@section('title', 'Request Withdrawal')
@section('header-title', 'Request Withdrawal')

@section('content')
<div class="p-4 space-y-4">
    <!-- Current Balance -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Available Balance</p>
                <p class="text-2xl font-bold text-gray-900">{{ format_currency($wallet->main_balance ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Request Withdrawal</h3>
        
        <form action="{{ route('agent.withdrawals.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="50" step="0.01"
                        max="{{ $wallet->main_balance ?? 0 }}"
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter amount">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum withdrawal: $50 | Available: {{ format_currency($wallet->main_balance ?? 0) }}</p>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Quick Amounts -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach([50, 100, 500, 1000] as $quickAmount)
                        <button type="button" onclick="setAmount({{ $quickAmount }})" 
                            class="py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all {{ ($wallet->main_balance ?? 0) < $quickAmount ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ ($wallet->main_balance ?? 0) < $quickAmount ? 'disabled' : '' }}>
                            ${{ number_format($quickAmount) }}
                        </button>
                    @endforeach
                </div>
                <button type="button" onclick="setMaxAmount()" 
                    class="mt-2 w-full py-2 border border-emerald-500 text-emerald-500 rounded-lg text-sm font-medium hover:bg-emerald-50 transition-all">
                    Withdraw All ({{ format_currency($wallet->main_balance ?? 0) }})
                </button>
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
            
            <!-- Bank Details -->
            <div id="bank-fields" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter bank name">
                    @error('bank_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                    <input type="text" name="account_holder" value="{{ old('account_holder') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter account holder name">
                    @error('account_holder')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter bank account number">
                    @error('bank_account_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name</label>
                    <input type="text" name="branch_name" value="{{ old('branch_name') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter branch name (optional)">
                    @error('branch_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Routing Number (Optional)</label>
                    <input type="text" name="routing_number" value="{{ old('routing_number') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter routing number if applicable">
                    @error('routing_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all">
                Submit Withdrawal Request
            </button>
        </form>
    </div>
    
    <!-- Important Notes -->
    <div class="bg-yellow-50 rounded-xl p-4">
        <h4 class="font-semibold text-yellow-800 mb-2">Important Notes</h4>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Withdrawals are processed within 24-48 hours</li>
            <li>• Minimum withdrawal amount is $50</li>
            <li>• Make sure your account details are correct</li>
            <li>• Contact support for any issues</li>
        </ul>
    </div>
</div>

<script>
function setAmount(amount) {
    document.querySelector('input[name="amount"]').value = amount;
}

function setMaxAmount() {
    document.querySelector('input[name="amount"]').value = {{ $wallet->main_balance ?? 0 }};
}

function toggleBankFields() {
    const method = document.getElementById('method').value;
    const bankFields = document.getElementById('bank-fields');
    const accountField = document.getElementById('account-field');
    
    if (method === 'bank') {
        bankFields.classList.remove('hidden');
        accountField.classList.add('hidden');
    } else {
        bankFields.classList.add('hidden');
        accountField.classList.remove('hidden');
    }
}

// Run on page load to handle old input
document.addEventListener('DOMContentLoaded', toggleBankFields);
</script>
@endsection
