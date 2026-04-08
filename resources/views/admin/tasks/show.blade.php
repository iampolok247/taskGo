@extends('layouts.admin')

@section('title', 'Task: ' . $task->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tasks.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                <p class="text-gray-500">{{ ucfirst($task->category) }} Task</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tasks.edit', $task) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.tasks.toggle-status', $task) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 {{ $task->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-xl transition-all">
                    {{ $task->status === 'active' ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Reward</p>
            <p class="text-xl font-bold text-green-600">{{ format_currency($task->reward, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Submissions</p>
            <p class="text-xl font-bold text-gray-900">{{ $task->submissions_count }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Approved</p>
            <p class="text-xl font-bold text-green-600">{{ $task->approved_submissions_count }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Pending</p>
            <p class="text-xl font-bold text-yellow-600">{{ $task->pending_submissions_count }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Status</p>
            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full {{ $task->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($task->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Task Details</h3>
                
                @if($task->thumbnail)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $task->thumbnail) }}" alt="{{ $task->title }}" class="w-full h-48 object-cover rounded-xl">
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Description</h4>
                        <p class="text-gray-900">{{ $task->description }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Instructions</h4>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($task->instructions)) !!}
                        </div>
                    </div>

                    @if($task->task_url)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Task URL</h4>
                        <a href="{{ $task->task_url }}" target="_blank" class="text-primary-600 hover:underline break-all">{{ $task->task_url }}</a>
                    </div>
                    @endif

                    @if($task->proof_instructions)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Proof Instructions</h4>
                        <p class="text-gray-700">{{ $task->proof_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Submissions -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Recent Submissions</h3>
                    <a href="{{ route('admin.submissions.index', ['task_id' => $task->id]) }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Submitted</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($recentSubmissions as $submission)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-xs font-bold">{{ strtoupper(substr($submission->user->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <span class="font-medium">{{ $submission->user->name ?? 'User' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $submission->status === 'approved' ? 'bg-green-100 text-green-700' : ($submission->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $submission->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.submissions.show', $submission) }}" class="text-primary-600 hover:text-primary-700 text-sm">Review</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">No submissions yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Task Info</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Category</span>
                        <span class="font-medium capitalize">{{ $task->category }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Difficulty</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $task->difficulty === 'easy' ? 'bg-green-100 text-green-700' : ($task->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($task->difficulty) }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Est. Time</span>
                        <span class="font-medium">{{ $task->estimated_time }} mins</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Proof Type</span>
                        <span class="font-medium capitalize">{{ $task->proof_type }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Daily Limit</span>
                        <span class="font-medium">{{ $task->daily_limit ?: 'Unlimited' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Total Limit</span>
                        <span class="font-medium">{{ $task->total_limit ?: 'Unlimited' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Priority</span>
                        <span class="font-medium">{{ $task->priority }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium">{{ $task->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($task->expires_at)
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Expires</span>
                        <span class="font-medium {{ $task->expires_at->isPast() ? 'text-red-600' : '' }}">{{ $task->expires_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Earnings Paid</h3>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                    <p class="text-sm opacity-90">Total Paid to Users</p>
                    <p class="text-2xl font-bold">{{ format_currency($task->approved_submissions_count * $task->reward, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
