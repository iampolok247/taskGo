@extends('layouts.user')

@section('title', 'Change Password')

@section('content')
<div class="p-4 space-y-4">
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                <p class="text-sm text-gray-500">Update your security credentials</p>
            </div>
        </div>

        <form method="POST" action="{{ route('user.profile.password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" id="current_password" name="current_password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                    placeholder="Enter your current password">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                    placeholder="Enter new password">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                    placeholder="Confirm new password">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/30">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Password Tips -->
    <div class="bg-blue-50 rounded-xl p-4">
        <h3 class="font-medium text-blue-900 mb-2">Password Tips</h3>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• Use at least 8 characters</li>
            <li>• Include uppercase and lowercase letters</li>
            <li>• Add numbers and special characters</li>
            <li>• Don't use personal information</li>
        </ul>
    </div>
</div>
@endsection
