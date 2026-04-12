@extends('layouts.admin')

@section('title', 'Add Leader')

@section('content')
<div class="space-y-6 max-w-2xl">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.agents.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Leader</h1>
            <p class="text-gray-500">Create a new leader account</p>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <form action="{{ route('admin.agents.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                        placeholder="Enter full name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror"
                        placeholder="agent@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-500 @enderror"
                    placeholder="+880 1XXX-XXXXXX">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror"
                        placeholder="Minimum 8 characters">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Confirm password">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%) <span class="text-red-500">*</span></label>
                <input type="number" name="commission_rate" value="{{ old('commission_rate', 5) }}" step="0.01" min="0" max="100" required 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('commission_rate') border-red-500 @enderror"
                    placeholder="e.g., 5">
                <p class="mt-1 text-sm text-gray-500">Percentage of freelancer deposits the leader will earn as commission</p>
                @error('commission_rate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3" 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-500 @enderror"
                    placeholder="Enter address (optional)">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.agents.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Create Leader
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
