@extends('layouts.admin')

@section('title', 'Edit: ' . $agent->name)

@section('content')
<div class="space-y-6 max-w-2xl">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.agents.show', $agent) }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Leader</h1>
            <p class="text-gray-500">{{ $agent->email }}</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <form action="{{ route('admin.agents.update', $agent) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $agent->name) }}" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $agent->email) }}" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="active" {{ $agent->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $agent->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="blocked" {{ $agent->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%)</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $agent->commission_rate) }}" step="0.01" min="0" max="100" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('commission_rate') border-red-500 @enderror">
                    @error('commission_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3" 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-500 @enderror">{{ old('address', $agent->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.agents.show', $agent) }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Reset Password -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Reset Password</h3>
            <form action="{{ route('admin.agents.reset-password', $agent) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" required 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition-all">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl shadow-sm border border-red-200">
        <div class="p-6">
            <h3 class="font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-gray-500 text-sm mb-4">Once you block a leader, they will not be able to access their account.</p>
            <form action="{{ route('admin.agents.toggle-status', $agent) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 {{ $agent->status === 'blocked' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white font-medium rounded-xl transition-all">
                    {{ $agent->status === 'blocked' ? 'Unblock Leader' : 'Block Leader' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
