@extends('layouts.admin')

@section('title', 'Edit Payment Method')

@section('content')
@php
    $accountDetails = $paymentMethod->account_details ?? [];
    
    // Smart category detection: new data has 'category' key, old data doesn't
    if (isset($accountDetails['category'])) {
        $category = $accountDetails['category'];
    } elseif (isset($accountDetails['number']) || isset($accountDetails['type'])) {
        $category = 'mobile_wallet';
    } elseif (isset($accountDetails['address']) || isset($accountDetails['wallet_address'])) {
        $category = 'crypto';
    } elseif (isset($accountDetails['bank_name'])) {
        $category = 'bank';
    } else {
        $category = 'other';
    }
    
    // Fallback values for both old and new key formats
    $accountNumber  = $accountDetails['account_number'] ?? $accountDetails['number'] ?? '';
    $accountType    = $accountDetails['account_type'] ?? $accountDetails['type'] ?? '';
    $accountName    = $accountDetails['account_name'] ?? '';
    $bankName       = $accountDetails['bank_name'] ?? '';
    $branchName     = $accountDetails['branch_name'] ?? $accountDetails['branch'] ?? '';
    $routingNumber  = $accountDetails['routing_number'] ?? $accountDetails['routing'] ?? '';
    $swiftCode      = $accountDetails['swift_code'] ?? '';
    $walletAddress  = $accountDetails['wallet_address'] ?? $accountDetails['address'] ?? '';
    $network        = $accountDetails['network'] ?? '';
    $accountInfo    = $accountDetails['account_info'] ?? '';
@endphp

<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Payment Method</h2>
        <a href="{{ route('admin.payment-methods.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            ← Back
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        {{-- Method Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Method Name</label>
            <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        {{-- Method Code --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
            <input type="text" name="code" value="{{ old('code', $paymentMethod->code) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" class="w-full border rounded-lg px-3 py-2" required>
                <option value="deposit" {{ ($paymentMethod->type ?? 'both') == 'deposit' ? 'selected' : '' }}>Deposit Only</option>
                <option value="withdrawal" {{ ($paymentMethod->type ?? 'both') == 'withdrawal' ? 'selected' : '' }}>Withdrawal Only</option>
                <option value="both" {{ ($paymentMethod->type ?? 'both') == 'both' ? 'selected' : '' }}>Both</option>
            </select>
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category" id="category" class="w-full border rounded-lg px-3 py-2" required onchange="toggleFields()">
                <option value="mobile_wallet" {{ $category == 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
                <option value="bank" {{ $category == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="crypto" {{ $category == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                <option value="other" {{ $category == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        {{-- Mobile Wallet Fields --}}
        <div id="mobile_wallet_fields" class="space-y-4" style="display: none;">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                <select name="account_type" class="w-full border rounded-lg px-3 py-2">
                    <option value="Personal" {{ strtolower($accountType) == 'personal' ? 'selected' : '' }}>Personal</option>
                    <option value="Agent" {{ strtolower($accountType) == 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="Merchant" {{ strtolower($accountType) == 'merchant' ? 'selected' : '' }}>Merchant</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                <input type="text" name="account_number" value="{{ old('account_number', $accountNumber) }}" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 01XXXXXXXXX">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Name (Optional)</label>
                <input type="text" name="account_name" value="{{ old('account_name', $accountName) }}" class="w-full border rounded-lg px-3 py-2" placeholder="Account holder name">
            </div>
        </div>

        {{-- Bank Fields --}}
        <div id="bank_fields" class="space-y-4" style="display: none;">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $bankName) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Branch Name</label>
                <input type="text" name="branch_name" value="{{ old('branch_name', $branchName) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                <input type="text" name="account_name" value="{{ old('account_name', $accountName) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                <input type="text" name="account_number" value="{{ old('account_number', $accountNumber) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Routing Number</label>
                <input type="text" name="routing_number" value="{{ old('routing_number', $routingNumber) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SWIFT Code</label>
                <input type="text" name="swift_code" value="{{ old('swift_code', $swiftCode) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Crypto Fields --}}
        <div id="crypto_fields" class="space-y-4" style="display: none;">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Network</label>
                <input type="text" name="network" value="{{ old('network', $network) }}" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. TRC20, ERC20, BEP20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Wallet Address</label>
                <input type="text" name="wallet_address" value="{{ old('wallet_address', $walletAddress) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Other Fields --}}
        <div id="other_fields" class="space-y-4" style="display: none;">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Info</label>
                <textarea name="account_info" class="w-full border rounded-lg px-3 py-2" rows="3">{{ old('account_info', $accountInfo) }}</textarea>
            </div>
        </div>

        {{-- Currency --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
            <input type="text" name="currency" value="{{ old('currency', $paymentMethod->currency) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        {{-- Min / Max --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min Amount</label>
                <input type="number" step="0.01" name="min_amount" value="{{ old('min_amount', $paymentMethod->min_amount) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Amount</label>
                <input type="number" step="0.01" name="max_amount" value="{{ old('max_amount', $paymentMethod->max_amount) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Fees --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fixed Fee</label>
                <input type="number" step="0.01" name="fee_fixed" value="{{ old('fee_fixed', $paymentMethod->fee_fixed) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fee Percentage (%)</label>
                <input type="number" step="0.01" name="fee_percentage" value="{{ old('fee_percentage', $paymentMethod->fee_percentage) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Instructions --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
            <textarea name="instructions" class="w-full border rounded-lg px-3 py-2" rows="3">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
        </div>

        {{-- Active --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ $paymentMethod->is_active ? 'checked' : '' }} class="rounded">
            <label class="text-sm text-gray-700">Active</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold">
            Update Payment Method
        </button>
    </form>
</div>

<script>
    function toggleFields() {
        const cat = document.getElementById('category').value;
        document.getElementById('mobile_wallet_fields').style.display = cat === 'mobile_wallet' ? 'block' : 'none';
        document.getElementById('bank_fields').style.display = cat === 'bank' ? 'block' : 'none';
        document.getElementById('crypto_fields').style.display = cat === 'crypto' ? 'block' : 'none';
        document.getElementById('other_fields').style.display = cat === 'other' ? 'block' : 'none';
    }
    // Run on page load to show the correct fields
    toggleFields();
</script>
@endsection
