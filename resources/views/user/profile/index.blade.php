@extends('layouts.user')

@section('title', 'Profile')

@section('content')
<div class="p-4 space-y-4">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl p-6 shadow-sm text-center">
        <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
            @else
                <span class="text-2xl font-bold text-primary-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
            @endif
        </div>
        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
        <p class="text-gray-500">{{ $user->email }}</p>
        <div class="mt-3">
            @if($user->email_verified_at)
                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Verified</span>
            @else
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">Unverified</span>
            @endif
        </div>
    </div>

    <!-- Account Stats -->
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl p-3 shadow-sm text-center">
            <p class="text-2xl font-bold text-primary-500">{{ $stats['tasks_completed'] }}</p>
            <p class="text-xs text-gray-500">Tasks Done</p>
        </div>
        <div class="bg-white rounded-xl p-3 shadow-sm text-center">
            <p class="text-2xl font-bold text-green-500">৳{{ number_format($stats['total_earned'], 0) }}</p>
            <p class="text-xs text-gray-500">Total Earned</p>
        </div>
        <div class="bg-white rounded-xl p-3 shadow-sm text-center">
            <p class="text-2xl font-bold text-purple-500">{{ $stats['total_referrals'] }}</p>
            <p class="text-xs text-gray-500">Referrals</p>
        </div>
    </div>

    <!-- Edit Profile -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Edit Profile</h3>
        
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <input type="file" name="avatar" accept="image/*"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('avatar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all">
                Update Profile
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Change Password</h3>
        
        <form action="{{ route('user.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <button type="submit" class="w-full py-3 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-900 transition-all">
                Change Password
            </button>
        </form>
    </div>

    <!-- Account Info -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Account Information</h3>
        
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Email</p>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
                <span class="text-xs text-gray-400">Cannot change</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Member Since</p>
                    <p class="text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">User ID</p>
                    <p class="text-xs text-gray-500">#{{ $user->id }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Referral Code</p>
                    <p class="text-xs text-gray-500">{{ $user->referral_code }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $user->referral_code }}')" class="text-primary-500 text-sm font-medium">Copy</button>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all">
            Logout
        </button>
    </form>
</div>
@endsection
