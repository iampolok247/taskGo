@extends('layouts.admin')
@section('title', 'Add Payment Method')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.payment-methods.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div><h1 class="text-2xl font-bold text-gray-800">Add Payment Method</h1></div>
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

    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Method Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="e.g., bKash, Nagad">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Method Type *</label>
                    <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Both (Deposit & Withdrawal)</option>
                        <option value="deposit" {{ old('type') == 'deposit' ? 'selected' : '' }}>Deposit Only</option>
                        <option value="withdrawal" {{ old('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" id="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Category</option>
                        <option value="mobile_wallet" {{ old('category') == 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet (bKash, Nagad)</option>
                        <option value="bank" {{ old('category') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="crypto" {{ old('category') == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                    <select name="currency" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="BDT" {{ old('currency') == 'BDT' ? 'selected' : '' }}>BDT - Bangladeshi Taka</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="USDT" {{ old('currency') == 'USDT' ? 'selected' : '' }}>USDT - Tether</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icon/Logo</label>
                    <input type="file" name="icon" accept="image/*" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                </div>
            </div>
        </div>

        <!-- Mobile Wallet -->
        <div id="mobile_wallet_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 hidden">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Mobile Wallet Account</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                    <select name="mw_account_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="Personal" {{ old('mw_account_type') == 'Personal' ? 'selected' : '' }}>Personal</option>
                        <option value="Agent" {{ old('mw_account_type') == 'Agent' ? 'selected' : '' }}>Agent</option>
                        <option value="Merchant" {{ old('mw_account_type') == 'Merchant' ? 'selected' : '' }}>Merchant</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                    <input type="text" name="mw_account_number" value="{{ old('mw_account_number') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="01XXXXXXXXX">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                    <input type="text" name="mw_account_name" value="{{ old('mw_account_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                </div>
            </div>
        </div>

        <!-- Bank -->
        <div id="bank_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 hidden">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Bank Account</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label><input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Branch Name</label><input type="text" name="branch_name" value="{{ old('branch_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label><input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label><input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Routing Number</label><input type="text" name="routing_number" value="{{ old('routing_number') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">SWIFT Code</label><input type="text" name="swift_code" value="{{ old('swift_code') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Crypto -->
        <div id="crypto_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 hidden">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Crypto Wallet</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Network</label>
                    <select name="network" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="TRC20" {{ old('network') == 'TRC20' ? 'selected' : '' }}>TRC20 (Tron)</option>
                        <option value="ERC20" {{ old('network') == 'ERC20' ? 'selected' : '' }}>ERC20 (Ethereum)</option>
                        <option value="BEP20" {{ old('network') == 'BEP20' ? 'selected' : '' }}>BEP20 (BSC)</option>
                        <option value="Bitcoin" {{ old('network') == 'Bitcoin' ? 'selected' : '' }}>Bitcoin</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Wallet Address</label><input type="text" name="wallet_address" value="{{ old('wallet_address') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Other -->
        <div id="other_fields" class="bg-white rounded-xl shadow-sm p-6 mb-6 hidden">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Account Info</h2>
            <textarea name="account_info" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">{{ old('account_info') }}</textarea>
        </div>

        <!-- Limits & Fees -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Limits & Fees</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Min Amount *</label><input type="number" name="min_amount" value="{{ old('min_amount', 100) }}" step="0.01" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label><input type="number" name="max_amount" value="{{ old('max_amount') }}" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Leave empty for unlimited"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Fixed Fee</label><input type="number" name="fee_fixed" value="{{ old('fee_fixed', 0) }}" step="0.01" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Percentage Fee (%)</label><input type="number" name="fee_percentage" value="{{ old('fee_percentage', 0) }}" step="0.01" max="100" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Instructions</h2>
            <textarea name="instructions" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Payment instructions for users...">{{ old('instructions') }}</textarea>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div>
                <div class="flex items-center pt-8">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.payment-methods.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Create Payment Method</button>
        </div>
    </form>
</div>

<script>
function toggleCategoryFields() {
    var cat = document.getElementById('category').value;
    document.getElementById('mobile_wallet_fields').classList.toggle('hidden', cat !== 'mobile_wallet');
    document.getElementById('bank_fields').classList.toggle('hidden', cat !== 'bank');
    document.getElementById('crypto_fields').classList.toggle('hidden', cat !== 'crypto');
    document.getElementById('other_fields').classList.toggle('hidden', cat !== 'other');
}
document.getElementById('category').addEventListener('change', toggleCategoryFields);
// Run on load for old() values
toggleCategoryFields();
</script>
@endsection
