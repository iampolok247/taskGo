@extends('layouts.user')

@section('title', 'Referrals')

@section('content')
<div class="p-4 space-y-4">
    <!-- Referral Stats -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-purple-100 text-sm">Total Referral Earnings</p>
                <h2 class="text-3xl font-bold">{{ format_currency($totalEarnings, 2) }}</h2>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <div>
                <p class="text-purple-200">Total Referrals</p>
                <p class="font-semibold text-xl">{{ $totalReferrals }}</p>
            </div>
            <div class="w-px h-8 bg-white/20"></div>
            <div>
                <p class="text-purple-200">Active Freelancers</p>
                <p class="font-semibold text-xl">{{ $activeReferrals }}</p>
            </div>
        </div>
    </div>

    <!-- Referral Code & Link -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Your Referral Code</h3>
        
        <div class="bg-gray-50 rounded-xl p-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Referral Code</p>
                    <p class="text-2xl font-bold text-primary-500">{{ $user->referral_code }}</p>
                </div>
                <button onclick="copyCode()" class="px-4 py-2 bg-primary-500 text-white font-medium text-sm rounded-lg hover:bg-primary-600 transition-all">
                    Copy
                </button>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-2">Referral Link</p>
            <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ $referralLink }}" class="flex-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600">
                <button onclick="copyLink()" class="px-4 py-2 bg-primary-500 text-white font-medium text-sm rounded-lg hover:bg-primary-600 transition-all">
                    Copy
                </button>
            </div>
        </div>
        
        <!-- Share Buttons -->
        <div class="mt-4">
            <p class="text-sm text-gray-500 mb-3">Share via</p>
            <div class="flex gap-3">
                <a href="https://wa.me/?text={{ urlencode('Join Task Go and start earning! Use my referral code: ' . $user->referral_code . ' ' . $referralLink) }}" 
                    target="_blank" class="flex-1 py-3 bg-green-500 text-white text-center font-medium rounded-xl hover:bg-green-600 transition-all">
                    WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}" 
                    target="_blank" class="flex-1 py-3 bg-blue-600 text-white text-center font-medium rounded-xl hover:bg-blue-700 transition-all">
                    Facebook
                </a>
            </div>
        </div>
    </div>

    <!-- Commission Info -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-3">Referral Commission</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-green-600 font-bold">1</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Sign-up Bonus</p>
                    <p class="text-xs text-gray-500">Earn when your referral signs up</p>
                </div>
                <p class="font-bold text-green-600">{{ format_currency($signupBonus, 2) }}</p>
            </div>
            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-bold">2</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Task Commission</p>
                    <p class="text-xs text-gray-500">Earn {{ $taskCommissionPercent }}% of their task earnings</p>
                </div>
                <p class="font-bold text-blue-600">{{ $taskCommissionPercent }}%</p>
            </div>
        </div>
    </div>

    <!-- Referred Users -->
    <div>
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Your Referrals</h3>
        
        @if($referrals->count() > 0)
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
                @foreach($referrals as $referral)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-bold text-sm">{{ strtoupper(substr($referral->referredUser->name ?? 'U', 0, 2)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $referral->referredUser->name ?? 'Unknown Freelancer' }}</p>
                            <p class="text-xs text-gray-500">Joined {{ $referral->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($referral->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded-full">Inactive</span>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ format_currency($referral->total_earned, 2) }} earned</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $referrals->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Referrals Yet</h3>
                <p class="text-gray-500 mb-4">Share your code to start earning!</p>
                <button onclick="copyLink()" class="px-6 py-2 bg-purple-500 text-white font-medium rounded-xl hover:bg-purple-600 transition-all">
                    Share Now
                </button>
            </div>
        @endif
    </div>
</div>

<script>
function copyCode() {
    const code = '{{ $user->referral_code }}';
    navigator.clipboard.writeText(code).then(() => {
        alert('Referral code copied!');
    });
}

function copyLink() {
    const link = '{{ $referralLink }}';
    navigator.clipboard.writeText(link).then(() => {
        alert('Referral link copied!');
    });
}
</script>
@endsection
