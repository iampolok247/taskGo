@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
        <p class="text-gray-500">Configure your Task Go platform settings</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.settings.currencies') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="text-lg">💱</span>
            </div>
            <div>
                <p class="font-medium text-gray-900">Currencies</p>
                <p class="text-xs text-gray-500">Manage exchange rates</p>
            </div>
        </a>
        <a href="{{ route('admin.settings.payment-methods') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="text-lg">💳</span>
            </div>
            <div>
                <p class="font-medium text-gray-900">Payment Methods</p>
                <p class="text-xs text-gray-500">Configure payment options</p>
            </div>
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="text-lg">📢</span>
            </div>
            <div>
                <p class="font-medium text-gray-900">Announcements</p>
                <p class="text-xs text-gray-500">Manage notifications</p>
            </div>
        </a>
    </div>

    <!-- General Settings -->
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Task Go' }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Support Phone</label>
                    <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '' }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                    <p class="text-sm text-gray-500">Managed in <a href="{{ route('admin.settings.currencies') }}" class="text-primary-600 hover:underline">Currency Settings</a></p>
                </div>
            </div>
        </div>

        <!-- Task Settings -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Task Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Task Limit</label>
                    <input type="number" name="daily_task_limit" value="{{ $settings['daily_task_limit'] ?? 10 }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Maximum tasks a user can complete per day</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Task Reward (BDT)</label>
                    <input type="number" name="default_task_reward" value="{{ $settings['default_task_reward'] ?? 10 }}" min="1" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Financial Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Deposit (BDT)</label>
                    <input type="number" name="min_deposit" value="{{ $settings['min_deposit'] ?? 100 }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Deposit (BDT)</label>
                    <input type="number" name="max_deposit" value="{{ $settings['max_deposit'] ?? 50000 }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Withdrawal (BDT)</label>
                    <input type="number" name="min_withdrawal" value="{{ $settings['min_withdrawal'] ?? 500 }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Withdrawal (BDT)</label>
                    <input type="number" name="max_withdrawal" value="{{ $settings['max_withdrawal'] ?? 25000 }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Fee (%)</label>
                    <input type="number" name="withdrawal_fee_percent" value="{{ $settings['withdrawal_fee_percent'] ?? 0 }}" min="0" max="100" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Referral Settings -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Referral Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Deposit for Referral Bonus (BDT)</label>
                    <input type="number" name="referral_min_deposit" value="{{ $settings['referral_min_deposit'] ?? 500 }}" min="0" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Referred freelancer must deposit at least this amount before referrer gets the bonus</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Freelancer Referral Bonus (BDT)</label>
                    <input type="number" name="referral_signup_bonus" value="{{ $settings['referral_signup_bonus'] ?? 500 }}" min="0" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Bonus a freelancer earns when their referred freelancer makes the minimum deposit</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leader Direct Referral Bonus (BDT)</label>
                    <input type="number" name="leader_direct_signup_bonus" value="{{ $settings['leader_direct_signup_bonus'] ?? 500 }}" min="0" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Bonus a leader earns when their referred freelancer makes the minimum deposit</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leader Deposit Commission (%)</label>
                    <input type="number" name="referral_task_commission" value="{{ $settings['referral_task_commission'] ?? 5 }}" min="0" max="100" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Commission (%) leader earns from referred freelancer's every deposit</p>
                </div>
            </div>
        </div>

        <!-- Leader Settings -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900">Leader Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Leader Commission (%)</label>
                    <input type="number" name="default_agent_commission" value="{{ $settings['default_agent_commission'] ?? 10 }}" min="0" max="100" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Default commission rate for new leaders (from freelancer deposits)</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-all">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
