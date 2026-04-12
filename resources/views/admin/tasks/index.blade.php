@extends('layouts.admin')

@section('title', 'Manage Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Tasks</h1>
            <p class="text-gray-500">Create and manage tasks for freelancers</p>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Task
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Tasks</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Inactive</p>
            <p class="text-2xl font-bold text-gray-400">{{ $stats['inactive'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Paid Out</p>
            <p class="text-2xl font-bold text-blue-600">{{ format_currency($stats['total_paid']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="{{ route('admin.tasks.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Search tasks...">
            </div>
            <select name="category" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">All Categories</option>
                <option value="social" {{ request('category') == 'social' ? 'selected' : '' }}>Social Media</option>
                <option value="survey" {{ request('category') == 'survey' ? 'selected' : '' }}>Survey</option>
                <option value="video" {{ request('category') == 'video' ? 'selected' : '' }}>Video</option>
                <option value="download" {{ request('category') == 'download' ? 'selected' : '' }}>Download</option>
                <option value="signup" {{ request('category') == 'signup' ? 'selected' : '' }}>Signup</option>
                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                Filter
            </button>
        </form>
    </div>

    <!-- Tasks Table -->
    @if($tasks->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($task->thumbnail)
                                            <img src="{{ asset('storage/' . $task->thumbnail) }}" alt="{{ $task->title }}" class="w-12 h-12 rounded-lg object-cover mr-4">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($task->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">{{ format_currency($task->reward, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $task->approved_submissions_count ?? 0 }}/{{ $task->total_limit ?: '∞' }}</div>
                                    @if($task->total_limit > 0)
                                    <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1">
                                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min(100, (($task->approved_submissions_count ?? 0) / max($task->total_limit, 1)) * 100) }}%"></div>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.tasks.toggle-status', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 text-xs font-medium rounded-full {{ $task->status === 'active' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }} transition-all">
                                            {{ ucfirst($task->status) }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-8 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Tasks Found</h3>
            <p class="text-gray-500 mb-4">Create your first task to get started.</p>
            <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Create Task
            </a>
        </div>
    @endif
</div>
@endsection
