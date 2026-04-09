@extends('layouts.admin')
@section('title', 'Edit Payment Method')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.payment-methods.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div><h1 class="text-2xl font-bold text-gray-800">Edit {{ $paymentMethod->name }}</h1></div>
    </div>

    @php
        $accountDetails = $paymentMethod->account_details ?? [];
        $category = $accountDetails['category'] ?? 'other';
    @endphp

    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Method Name *</label>
                    <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Method Type *</label>
                    <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="both" {{ $paymentMethod->type == 'both' ? 'selected' : '' }}>Both</option>
                        <option value="deposit" {{ $paymentMethod->type == 'deposit' ? 'selected' : '' }}>Deposit Only</option>
                        <option value="withdrawal" {{ $paymentMethod->type == 'withdrawal' ? 'selected' : '' }}>Withdrawal Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" id="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="mobile_wallet" {{ $category == 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
                        <option value="bank" {{ $category == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="crypto" {{ $category == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                        <option value="other" {{ $category == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                    <select name="currency" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="BDT" {{ $paymentMethod->currency == 'BDT' ? 'selected' : '' }}>BDT</option>
                        <option value="USD" {{ $paymentMethod->currency == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="USDT" {{ $paymentMethod->currency == 'USDT' ? 'selected' : '' }}>USDT</option>
                    </select>
                </div>
                @if($paymentMethod->icon)
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2">Current Icon</label><img src="{{ asset('storage/' . $paymentMethod->icon) }}" class="w-20 h-20 rounded-lg object-cover"></div>
                @endif
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2">Change Icon</label><input type="file" name="icon" accept="image/*" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Mobile Wallet -->
        <div id="mobile_wallet_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 {{ $category != 'mobile_wallet' ? 'hidden' : '' }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Mobile Wallet Account</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                    <select name="account_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="Personal" {{ ($accountDetails['account_type'] ?? '') == 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Agent" {{ ($accountDetails['account_type'] ?? '') == 'Agent' ? 'selected' : '' }}>Agent</option>
                        <option value="Merchant" {{ ($accountDetails['account_type'] ?? '') == 'Merchant' ? 'selected' : '' }}>Merchant</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label><input type="text" name="account_number" value="{{ $accountDetails['account_number'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label><input type="text" name="account_name" value="{{ $accountDetails['account_name'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Bank -->
        <div id="bank_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 {{ $category != 'bank' ? 'hidden' : '' }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Bank Account</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label><input type="text" name="bank_name" value="{{ $accountDetails['bank_name'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Branch Name</label><input type="text" name="branch_name" value="{{ $accountDetails['branch_name'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label><input type="text" name="account_name" value="{{ $accountDetails['account_name'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label><input type="text" name="account_number" value="{{ $accountDetails['account_number'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Routing Number</label><input type="text" name="routing_number" value="{{ $accountDetails['routing_number'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">SWIFT Code</label><input type="text" name="swift_code" value="{{ $accountDetails['swift_code'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Crypto -->
        <div id="crypto_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 {{ $category != 'crypto' ? 'hidden' : '' }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Crypto Wallet</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Network</label>
                    <select name="network" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="TRC20" {{ ($accountDetails['network'] ?? '') == 'TRC20' ? 'selected' : '' }}>TRC20</option>
                        <option value="ERC20" {{ ($accountDetails['network'] ?? '') == 'ERC20' ? 'selected' : '' }}>ERC20</option>
                        <option value="BEP20" {{ ($accountDetails['network'] ?? '') == 'BEP20' ? 'selected' : '' }}>BEP20</option>
                        <option value="Bitcoin" {{ ($accountDetails['network'] ?? '') == 'Bitcoin' ? 'selected' : '' }}>Bitcoin</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Wallet Address</label><input type="text" name="wallet_address" value="{{ $accountDetails['wallet_address'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Other -->
        <div id="other_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 {{ $category != 'other' ? 'hidden' : '' }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Account Info</h2>
            <textarea name="account_info" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">{{ $accountDetails['account_info'] ?? '' }}</textarea>
        </div>

        <!-- Limits & Fees -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Limits & Fees</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Min Amount *</label><input type="number" name="min_amount" value="{{ $paymentMethod->min_amount }}" step="0.01" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label><input type="number" name="max_amount" value="{{ $paymentMethod->max_amount }}" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Fixed Fee</label><input type="number" name="fee_fixed" value="{{ $paymentMethod->fee_fixed }}" step="0.01" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Percentage Fee (%)</label><input type="number" name="fee_percentage" value="{{ $paymentMethod->fee_percentage }}" step="0.01" max="100" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Instructions</h2>
            <textarea name="instructions" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">{{ $paymentMethod->instructions }}</textarea>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label><input type="number" name="sort_order" value="{{ $paymentMethod->sort_order }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div class="flex items-center pt-8">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" {{ $paymentMethod->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.payment-methods.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Update</button>
        </div>
    </form>
</div>

<script>
document.getElementById('category').addEventListener('change', function() {
    document.getElementById('mobile_wallet_fields').classList.add('hidden');
    document.getElementById('bank_fields').classList.add('hidden');
    document.getElementById('crypto_fields').classList.add('hidden');
    document.getElementById('other_fields').classList.add('hidden');
    if (this.value) document.getElementById(this.value + '_fields').classList.remove('hidden');
});
</script>
@endsection
