@extends('layouts.agent')

@section('title', 'Create Freelancer')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('agent.users.index') }}" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Freelancer</h1>
            <p class="text-gray-500">Register a new freelancer under your leadership</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <form action="{{ route('agent.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter full name">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="user@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="01XXXXXXXXX">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NID Number</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Enter NID number">
                    @error('nid_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Min 8 characters">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Confirm password">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                    placeholder="Enter full address">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="bg-emerald-50 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Commission Information</p>
                        <p class="text-sm text-emerald-600 mt-1">You will earn {{ auth('agent')->user()->commission_rate }}% commission on every task completed by this freelancer.</p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all">
                    Create Freelancer
                </button>
                <a href="{{ route('agent.users.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
