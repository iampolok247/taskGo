@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Payment Methods</h1>
            <p class="text-gray-600 text-sm mt-1">Manage deposit and withdrawal payment methods</p>
        </div>
        <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Payment Method
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Limits</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paymentMethods as $method)
                    @php
                        $category = $method->account_details['category'] ?? 'other';
                        $categoryLabels = [
                            'mobile_wallet' => ['label' => 'Mobile Wallet', 'color' => 'bg-orange-100 text-orange-800'],
                            'bank' => ['label' => 'Bank Transfer', 'color' => 'bg-blue-100 text-blue-800'],
                            'crypto' => ['label' => 'Cryptocurrency', 'color' => 'bg-yellow-100 text-yellow-800'],
                            'other' => ['label' => 'Other', 'color' => 'bg-gray-100 text-gray-800'],
                        ];
                        $cat = $categoryLabels[$category] ?? $categoryLabels['other'];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($method->icon)
                                <img src="{{ asset('storage/' . $method->icon) }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $method->code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($method->type == 'deposit')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Deposit</span>
                            @elseif($method->type == 'withdrawal')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Withdrawal</span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Both</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $cat['color'] }}">{{ $cat['label'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>Min: {{ $method->currency }} {{ number_format($method->min_amount, 2) }}</div>
                            <div>Max: {{ $method->max_amount ? $method->currency . ' ' . number_format($method->max_amount, 2) : 'Unlimited' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($method->fee_fixed > 0 || $method->fee_percentage > 0)
                                @if($method->fee_fixed > 0)<div>{{ $method->currency }} {{ number_format($method->fee_fixed, 2) }} fixed</div>@endif
                                @if($method->fee_percentage > 0)<div>{{ $method->fee_percentage }}%</div>@endif
                            @else
                            <span class="text-green-600">No Fee</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.payment-methods.toggle-status', $method) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="relative inline-flex h-6 w-11 rounded-full {{ $method->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition {{ $method->is_active ? 'translate-x-5' : 'translate-x-0' }}" style="margin-top:2px;margin-left:2px;"></span>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.payment-methods.edit', $method) }}" class="text-indigo-600 hover:text-indigo-900 p-1.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No payment methods found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
