@extends('layouts.agent')

@section('title', 'My Freelancers')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Freelancers</h1>
            <p class="text-gray-500">Manage freelancers registered under your leadership</p>
        </div>
        <a href="{{ route('agent.users.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Create Freelancer
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Freelancers</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Active Freelancers</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Inactive Freelancers</p>
            <p class="text-2xl font-bold text-gray-400">{{ $stats['inactive'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">This Month</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['this_month'] }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('agent.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    placeholder="Search by name, email or phone...">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-all">
                Search
            </button>
        </form>
    </div>

    <!-- Users List -->
    @if($users->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                            <span class="text-emerald-600 font-bold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">#{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->phone ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">{{ format_currency($user->wallet->total_earned ?? 0, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->taskSubmissions->where('status', 'approved')->count() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('agent.users.show', $user) }}" class="text-emerald-600 hover:text-emerald-900">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($users as $user)
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                                <span class="text-emerald-600 font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                            @if($user->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Earnings:</span>
                                <span class="font-medium text-green-600">{{ format_currency($user->wallet->total_earned ?? 0, 2) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Tasks:</span>
                                <span class="font-medium">{{ $user->taskSubmissions->where('status', 'approved')->count() }}</span>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Joined {{ $user->created_at->format('M d, Y') }}</span>
                            <a href="{{ route('agent.users.show', $user) }}" class="text-emerald-600 text-sm font-medium">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-8 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Freelancers Found</h3>
            <p class="text-gray-500 mb-4">Start by creating your first freelancer.</p>
            <a href="{{ route('agent.users.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition-all">
                Create First Freelancer
            </a>
        </div>
    @endif
</div>
@endsection
