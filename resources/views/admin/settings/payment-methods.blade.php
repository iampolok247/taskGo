@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Methods</h1>
            <p class="text-gray-500">Manage deposit and withdrawal payment methods</p>
        </div>
        <button onclick="document.getElementById('addMethodModal').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Method
        </button>
    </div>

    <!-- Payment Methods List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($methods ?? [] as $method)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r {{ $method->type === 'mobile_banking' ? 'from-pink-500 to-pink-600' : ($method->type === 'bank' ? 'from-blue-500 to-blue-600' : 'from-gray-500 to-gray-600') }} flex items-center justify-center">
                            <span class="text-white font-bold">{{ strtoupper(substr($method->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $method->name }}</h3>
                            <p class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $method->type) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $method->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $method->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Min Amount</span>
                        <span class="font-medium">{{ format_currency($method->min_amount) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Max Amount</span>
                        <span class="font-medium">{{ $method->max_amount ? format_currency($method->max_amount) : 'Unlimited' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Fixed Fee</span>
                        <span class="font-medium">{{ format_currency($method->fee_fixed) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Fee %</span>
                        <span class="font-medium">{{ $method->fee_percentage }}%</span>
                    </div>
                </div>

                @if($method->instructions)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">{{ $method->instructions }}</p>
                </div>
                @endif

                <div class="mt-4 flex items-center gap-2">
                    <button onclick="editMethod({{ $method->id }}, '{{ $method->name }}', {{ $method->min_amount }}, {{ $method->max_amount ?? 'null' }}, {{ $method->fee_fixed }}, {{ $method->fee_percentage }}, '{{ addslashes($method->instructions ?? '') }}', {{ $method->is_active ? 'true' : 'false' }})" class="flex-1 py-2 text-center border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                        Edit
                    </button>
                    <form action="{{ route('admin.settings.payment-methods.toggle', $method) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 text-center {{ $method->is_active ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} font-medium rounded-xl hover:opacity-80 transition-all">
                            {{ $method->is_active ? 'Disable' : 'Enable' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white rounded-xl p-12 shadow-sm text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <p class="font-medium text-gray-900 mb-1">No payment methods</p>
            <p class="text-gray-500 text-sm">Add your first payment method to get started.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Method Modal -->
<div id="addMethodModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Add Payment Method</h3>
            <button onclick="document.getElementById('addMethodModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.settings.payment-methods.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="e.g., bKash">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="mobile_banking">Mobile Banking</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="crypto">Cryptocurrency</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount</label>
                    <input type="number" name="min_amount" value="100" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label>
                    <input type="number" name="max_amount" step="0.01" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Leave empty for unlimited">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Fee ({{ currency_symbol() }})</label>
                    <input type="number" name="fee_fixed" value="0" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee %</label>
                    <input type="number" name="fee_percentage" value="0" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                <textarea name="instructions" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Payment instructions for users..."></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Add Payment Method
            </button>
        </form>
    </div>
</div>

<!-- Edit Method Modal -->
<div id="editMethodModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Edit Payment Method</h3>
            <button onclick="document.getElementById('editMethodModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editMethodForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" id="editName" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount</label>
                    <input type="number" name="min_amount" id="editMinAmount" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label>
                    <input type="number" name="max_amount" id="editMaxAmount" step="0.01" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Fee ({{ currency_symbol() }})</label>
                    <input type="number" name="fee_fixed" id="editFeeFixed" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee %</label>
                    <input type="number" name="fee_percentage" id="editFeePercentage" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                <textarea name="instructions" id="editInstructions" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="editIsActive" value="1" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <label for="editIsActive" class="text-sm text-gray-700">Active</label>
            </div>
            <button type="submit" class="w-full py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Update Payment Method
            </button>
        </form>
    </div>
</div>

<script>
function editMethod(id, name, minAmount, maxAmount, feeFixed, feePercentage, instructions, isActive) {
    document.getElementById('editMethodForm').action = '/admin/settings/payment-methods/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editMinAmount').value = minAmount;
    document.getElementById('editMaxAmount').value = maxAmount;
    document.getElementById('editFeeFixed').value = feeFixed;
    document.getElementById('editFeePercentage').value = feePercentage;
    document.getElementById('editInstructions').value = instructions;
    document.getElementById('editIsActive').checked = isActive;
    document.getElementById('editMethodModal').classList.remove('hidden');
}
</script>
@endsection
