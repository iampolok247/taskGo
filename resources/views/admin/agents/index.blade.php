@extends('layouts.admin')

@section('title', 'Manage Leaders')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Leaders</h1>
            <p class="text-gray-500">View and manage all leaders</p>
        </div>
        <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Add Leader
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.agents.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or agent code..." 
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <select name="status" class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition-all">
                Filter
            </button>
        </form>
    </div>

    <!-- Agents Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leader</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freelancers</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center mr-3">
                                    <span class="font-bold text-white">{{ strtoupper(substr($agent->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $agent->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $agent->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $agent->agent_code }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-900">{{ $agent->users_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-900">{{ $agent->commission_rate }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $agent->status === 'active' ? 'bg-green-100 text-green-700' : ($agent->status === 'blocked' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($agent->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $agent->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.agents.show', $agent) }}" class="text-primary-600 hover:text-primary-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.agents.edit', $agent) }}" class="text-gray-600 hover:text-gray-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.agents.toggle-status', $agent) }}" method="POST" class="inline"
                                    onsubmit="return confirm('{{ $agent->status === "active" ? "Block" : "Activate" }} leader {{ $agent->name }}?')">
                                    @csrf
                                    <button type="submit" class="{{ $agent->status === 'active' ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' }}" title="{{ $agent->status === 'active' ? 'Block Leader' : 'Activate Leader' }}">
                                        @if($agent->status === 'active')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete leader {{ $agent->name }}? Their freelancers will be unlinked.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="font-medium">No leaders found</p>
                                <p class="text-sm">Get started by creating a new leader.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($agents->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $agents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
