@extends('layouts.user')

@section('title', 'Deposit Funds')

@section('content')
<div class="p-4 space-y-4">
    <!-- Current Balance -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="text-2xl font-bold text-gray-900">৳{{ number_format($wallet->main_balance, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Deposit Form -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Make a Deposit</h3>
        
        <form action="{{ route('user.deposits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount (BDT)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">৳</span>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="100" step="0.01"
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Enter amount">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum deposit: ৳100</p>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Quick Amounts -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Select</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach([100, 500, 1000, 2000] as $quickAmount)
                        <button type="button" onclick="setAmount({{ $quickAmount }})" 
                            class="py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            ৳{{ number_format($quickAmount) }}
                        </button>
                    @endforeach
                </div>
            </div>
            
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="space-y-2">
                    @forelse($paymentMethods as $method)
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all payment-method-option">
                            <input type="radio" name="method" value="{{ $method->code }}" 
                                class="w-4 h-4 text-primary-500 focus:ring-primary-500"
                                data-account="{{ $method->account_number }}"
                                data-min="{{ $method->min_amount }}"
                                data-max="{{ $method->max_amount }}"
                                {{ old('method') == $method->code ? 'checked' : '' }}>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $method->name }}</p>
                                <p class="text-sm text-gray-500">{{ $method->account_number }}</p>
                                @if($method->min_amount || $method->max_amount)
                                    <p class="text-xs text-gray-400">Min: ৳{{ number_format($method->min_amount ?? 0) }} | Max: ৳{{ number_format($method->max_amount ?? 999999) }}</p>
                                @endif
                            </div>
                            @if($method->icon)
                                <img src="{{ asset('storage/' . $method->icon) }}" alt="{{ $method->name }}" class="w-10 h-10 object-contain">
                            @endif
                        </label>
                    @empty
                        <div class="p-4 bg-yellow-50 rounded-xl text-center">
                            <p class="text-yellow-700">No payment methods available. Please contact admin.</p>
                        </div>
                    @endforelse
                </div>
                @error('method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Selected Account Info -->
            <div id="account-info" class="hidden p-4 bg-blue-50 rounded-xl">
                <p class="text-sm font-medium text-blue-800 mb-1">Send payment to:</p>
                <p class="text-lg font-bold text-blue-900" id="selected-account"></p>
                <p class="text-xs text-blue-600 mt-1">Copy this number and send your payment</p>
            </div>

            <!-- Sender Account -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Sender Number/Account</label>
                <input type="text" name="sender_account" value="{{ old('sender_account') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Enter your account/number from which you sent payment">
                @error('sender_account')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Transaction ID -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID / Reference</label>
                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Enter transaction ID from payment">
                @error('transaction_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Payment Proof -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Screenshot</label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center" id="dropzone">
                    <input type="file" name="proof_image" id="proof_image" accept="image/*" class="hidden">
                    <div id="preview-container" class="hidden">
                        <img id="preview-image" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg mb-3">
                        <button type="button" onclick="removeImage()" class="text-red-500 text-sm">Remove</button>
                    </div>
                    <div id="upload-prompt">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 mb-2">Tap to upload payment screenshot</p>
                        <p class="text-xs text-gray-400">PNG, JPG up to 5MB</p>
                    </div>
                </div>
                @error('proof_image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all active:scale-[0.98]">
                Submit Deposit Request
            </button>
        </form>
    </div>

    <!-- Recent Deposits -->
    @if($recentDeposits->count() > 0)
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Deposits</h3>
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
                @foreach($recentDeposits as $deposit)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($deposit->status == 'approved') bg-green-100
                            @elseif($deposit->status == 'pending') bg-yellow-100
                            @else bg-red-100 @endif">
                            @if($deposit->status == 'approved')
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($deposit->status == 'pending')
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
                            <p class="text-sm font-medium text-gray-900">Deposit via {{ $deposit->paymentMethod->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $deposit->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">৳{{ number_format($deposit->amount, 2) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($deposit->status == 'approved') bg-green-100 text-green-700
                                @elseif($deposit->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function setAmount(amount) {
    document.querySelector('input[name="amount"]').value = amount;
}

// Payment method selection
document.querySelectorAll('input[name="method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const accountInfo = document.getElementById('account-info');
        const selectedAccount = document.getElementById('selected-account');
        
        if (this.dataset.account) {
            selectedAccount.textContent = this.dataset.account;
            accountInfo.classList.remove('hidden');
        }
    });
});

// Check if any payment method is pre-selected
const checkedMethod = document.querySelector('input[name="method"]:checked');
if (checkedMethod && checkedMethod.dataset.account) {
    document.getElementById('selected-account').textContent = checkedMethod.dataset.account;
    document.getElementById('account-info').classList.remove('hidden');
}

const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('proof_image');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const uploadPrompt = document.getElementById('upload-prompt');

dropzone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});

function removeImage() {
    fileInput.value = '';
    previewContainer.classList.add('hidden');
    uploadPrompt.classList.remove('hidden');
}
</script>
@endsection
