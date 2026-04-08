@extends('layouts.admin')

@section('title', 'Currency Rates')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Currency Rates</h1>
            <p class="text-gray-500">Manage currency exchange rates</p>
        </div>
        <button onclick="document.getElementById('addRateModal').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Rate
        </button>
    </div>

    <!-- Currency Rates Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee %</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rates ?? [] as $rate)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono font-medium text-gray-900">{{ $rate->from_currency }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono font-medium text-gray-900">{{ $rate->to_currency }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-green-600">{{ number_format($rate->rate, 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-900">{{ $rate->fee_percentage }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $rate->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $rate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $rate->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="editRate('{{ $rate->from_currency }}', '{{ $rate->to_currency }}', {{ $rate->rate }}, {{ $rate->fee_percentage }}, {{ $rate->is_active ? 'true' : 'false' }})" class="text-primary-600 hover:text-primary-900">
                                Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="font-medium">No currency rates</p>
                                <p class="text-sm">Add your first currency rate to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Reference -->
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Quick Reference</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">BDT → USD</p>
                <p class="text-lg font-bold text-gray-900">৳1 = $0.0091</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">USD → BDT</p>
                <p class="text-lg font-bold text-gray-900">$1 = ৳110</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">BDT → INR</p>
                <p class="text-lg font-bold text-gray-900">৳1 = ₹0.76</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">INR → BDT</p>
                <p class="text-lg font-bold text-gray-900">₹1 = ৳1.32</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Rate Modal -->
<div id="addRateModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Add Currency Rate</h3>
            <button onclick="document.getElementById('addRateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.settings.currency-rates.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Currency</label>
                    <input type="text" name="rates[0][from_currency]" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono uppercase" placeholder="BDT" maxlength="10">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Currency</label>
                    <input type="text" name="rates[0][to_currency]" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono uppercase" placeholder="USD" maxlength="10">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exchange Rate</label>
                    <input type="number" name="rates[0][rate]" step="0.0001" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0.0091">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee %</label>
                    <input type="number" name="rates[0][fee_percentage]" step="0.01" value="0" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="rates[0][is_active]" value="1" checked class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <label class="text-sm text-gray-700">Active</label>
            </div>
            <button type="submit" class="w-full py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Add Currency Rate
            </button>
        </form>
    </div>
</div>

<!-- Edit Rate Modal -->
<div id="editRateModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Edit Currency Rate</h3>
            <button onclick="document.getElementById('editRateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.settings.currency-rates.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Currency</label>
                    <input type="text" name="rates[0][from_currency]" id="editFromCurrency" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono uppercase bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Currency</label>
                    <input type="text" name="rates[0][to_currency]" id="editToCurrency" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono uppercase bg-gray-100" readonly>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exchange Rate</label>
                    <input type="number" name="rates[0][rate]" id="editRate" step="0.0001" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee %</label>
                    <input type="number" name="rates[0][fee_percentage]" id="editFeePercentage" step="0.01" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="rates[0][is_active]" id="editIsActive" value="1" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <label for="editIsActive" class="text-sm text-gray-700">Active</label>
            </div>
            <button type="submit" class="w-full py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Update Currency Rate
            </button>
        </form>
    </div>
</div>

<script>
function editRate(from, to, rate, fee, isActive) {
    document.getElementById('editFromCurrency').value = from;
    document.getElementById('editToCurrency').value = to;
    document.getElementById('editRate').value = rate;
    document.getElementById('editFeePercentage').value = fee;
    document.getElementById('editIsActive').checked = isActive;
    document.getElementById('editRateModal').classList.remove('hidden');
}
</script>
@endsection
