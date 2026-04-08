@extends('layouts.app')

@section('title', 'Register')

@section('body')
<div class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-primary-500 to-primary-700 px-4 py-8 safe-area-top safe-area-bottom">
    <div class="w-full max-w-md mx-auto">
        <!-- Logo -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-3 p-2">
                <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-full w-auto object-contain">
            </div>
            <h1 class="text-xl font-bold text-white">Join Task Go</h1>
            <p class="text-primary-100 text-sm mt-1">Start earning rewards today</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Create Account</h2>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Enter your name">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Enter your email">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-gray-400">(Optional)</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Enter your phone">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Create a password">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Confirm your password">
                </div>

                <div>
                    <label for="referral_code" class="block text-sm font-medium text-gray-700 mb-1">Referral Code <span class="text-gray-400">(Optional)</span></label>
                    <input type="text" id="referral_code" name="referral_code" value="{{ old('referral_code', $referralCode ?? '') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                        placeholder="Enter referral code">
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/30 active:scale-[0.98]">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary-500 font-medium hover:underline">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
