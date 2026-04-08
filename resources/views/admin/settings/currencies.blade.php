@extends('layouts.admin')

@section('title', 'Currency Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Currency Management</h1>
            <p class="text-gray-500">Manage exchange rates and active currencies</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div x-data="{ search: '' }">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" x-model="search" placeholder="Search currencies..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Currencies Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exchange Rate (to USD)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($currencies as $currency)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-lg font-medium">
                                        {{ $currency->symbol }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $currency->code }}</div>
                                        <div class="text-sm text-gray-500">{{ $currency->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $currency->symbol }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.settings.currencies.update', $currency) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="exchange_rate" value="{{ $currency->exchange_rate }}" step="0.00000001"
                                        class="w-32 px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <input type="hidden" name="is_active" value="{{ $currency->is_active ? '1' : '0' }}">
                                    <button type="submit" class="px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700">
                                        Save
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($currency->is_default)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Default</span>
                                    @endif
                                    @if($currency->is_active)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$currency->is_default)
                                        <form action="{{ route('admin.settings.currencies.update', $currency) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="exchange_rate" value="{{ $currency->exchange_rate }}">
                                            <input type="hidden" name="is_active" value="{{ $currency->is_active ? '1' : '0' }}">
                                            <input type="hidden" name="is_default" value="1">
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">Set Default</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.settings.currencies.update', $currency) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="exchange_rate" value="{{ $currency->exchange_rate }}">
                                        <input type="hidden" name="is_active" value="{{ $currency->is_active ? '0' : '1' }}">
                                        <button type="submit" class="{{ $currency->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} text-sm">
                                            {{ $currency->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
