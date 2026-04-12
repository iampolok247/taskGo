@extends('layouts.admin')

@section('title', 'Leader: ' . $agent->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.agents.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $agent->name }}</h1>
                <p class="text-gray-500">Leader Code: <span class="font-mono text-primary-600">{{ $agent->agent_code }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.agents.edit', $agent) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.agents.toggle-status', $agent) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 {{ $agent->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-xl transition-all">
                    {{ $agent->status === 'active' ? 'Block Leader' : 'Activate Leader' }}
                </button>
            </form>
            <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to DELETE leader {{ $agent->name }}? This action cannot be undone. Their freelancers will be unlinked.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 font-medium rounded-xl hover:bg-red-200 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Leader
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Freelancers</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Active Freelancers</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Earnings</p>
            <p class="text-xl font-bold text-primary-600">{{ format_currency($stats['total_earnings'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Pending</p>
            <p class="text-xl font-bold text-yellow-600">{{ format_currency($stats['pending_earnings'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Withdrawn</p>
            <p class="text-xl font-bold text-blue-600">{{ format_currency($stats['withdrawn_earnings'], 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Agent Info Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Leader Information</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($agent->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $agent->name }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $agent->status === 'active' ? 'bg-green-100 text-green-700' : ($agent->status === 'blocked' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($agent->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="border-t pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium">{{ $agent->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="font-medium">{{ $agent->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Leader Code</span>
                        <span class="font-mono text-primary-600">{{ $agent->agent_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Commission Rate</span>
                        <span class="font-medium">{{ $agent->commission_rate }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Joined</span>
                        <span class="font-medium">{{ $agent->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Recent Commissions</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($commissions as $commission)
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <div>
                        <p class="font-medium text-sm">{{ $commission->user->name ?? 'Freelancer' }}</p>
                        <p class="text-xs text-gray-500">{{ $commission->created_at->format('M d, H:i') }}</p>
                    </div>
                    <span class="text-green-600 font-medium">+{{ format_currency($commission->amount, 2) }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No commissions yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Recent Freelancers</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->format('M d') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No freelancers yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- All Freelancers Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">All Freelancers ({{ $stats['total_users'] }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Freelancer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($agent->users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                                    <span class="font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No freelancers under this leader</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
