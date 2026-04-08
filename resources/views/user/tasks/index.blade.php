@extends('layouts.user')

@section('title', 'Tasks')

@section('content')
<div class="p-4 space-y-4">
    <!-- Today's Progress -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-sm text-gray-500">Today's Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ $todayCompleted }}/{{ $dailyLimit }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Today's Earnings</p>
                <p class="text-xl font-bold text-green-600">৳{{ number_format($todayEarnings, 2) }}</p>
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" style="width: {{ min(($todayCompleted / max($dailyLimit, 1)) * 100, 100) }}%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">
            @if($todayCompleted >= $dailyLimit)
                Daily limit reached! Come back tomorrow.
            @else
                {{ $dailyLimit - $todayCompleted }} tasks remaining today
            @endif
        </p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <a href="{{ route('user.tasks.index') }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ !request('status') ? 'bg-primary-500 text-white' : 'bg-white text-gray-600' }}">
            All Tasks
        </a>
        <a href="{{ route('user.tasks.index', ['status' => 'available']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') == 'available' ? 'bg-primary-500 text-white' : 'bg-white text-gray-600' }}">
            Available
        </a>
        <a href="{{ route('user.tasks.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600' }}">
            Pending
        </a>
        <a href="{{ route('user.tasks.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') == 'completed' ? 'bg-green-500 text-white' : 'bg-white text-gray-600' }}">
            Completed
        </a>
    </div>

    <!-- Tasks List -->
    @if($tasks->count() > 0)
        <div class="space-y-3">
            @foreach($tasks as $task)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover">
                    @if($task->thumbnail)
                        <img src="{{ asset('storage/' . $task->thumbnail) }}" alt="{{ $task->title }}" class="w-full h-32 object-cover">
                    @endif
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 80) }}</p>
                            </div>
                            <div class="ml-3 text-right">
                                <p class="text-lg font-bold text-green-600">৳{{ number_format($task->reward, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 text-xs text-gray-500 mt-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ ucfirst($task->category) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $task->estimated_time }} min
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $task->completed_count }}/{{ $task->total_limit ?: '∞' }}
                            </span>
                        </div>
                        
                        @php
                            $userSubmission = $task->submissions->where('user_id', auth()->id())->first();
                        @endphp
                        
                        <div class="mt-4">
                            @if($userSubmission)
                                @if($userSubmission->status == 'pending')
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">Pending Review</span>
                                        <a href="{{ route('user.tasks.show', $task) }}" class="text-primary-500 text-sm font-medium">View Details</a>
                                    </div>
                                @elseif($userSubmission->status == 'approved')
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Completed</span>
                                        <a href="{{ route('user.tasks.show', $task) }}" class="text-primary-500 text-sm font-medium">View Details</a>
                                    </div>
                                @elseif($userSubmission->status == 'rejected')
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">Rejected</span>
                                        <a href="{{ route('user.tasks.show', $task) }}" class="text-primary-500 text-sm font-medium">Try Again</a>
                                    </div>
                                @endif
                            @else
                                @if($task->isAvailable() && $todayCompleted < $dailyLimit)
                                    <a href="{{ route('user.tasks.show', $task) }}" class="block w-full py-2.5 bg-primary-500 text-white text-center font-medium rounded-xl hover:bg-primary-600 transition-all">
                                        Start Task
                                    </a>
                                @elseif($todayCompleted >= $dailyLimit)
                                    <button disabled class="block w-full py-2.5 bg-gray-300 text-gray-500 text-center font-medium rounded-xl cursor-not-allowed">
                                        Daily Limit Reached
                                    </button>
                                @else
                                    <button disabled class="block w-full py-2.5 bg-gray-300 text-gray-500 text-center font-medium rounded-xl cursor-not-allowed">
                                        Not Available
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
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
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Tasks Available</h3>
            <p class="text-gray-500">Check back later for new tasks!</p>
        </div>
    @endif
</div>
@endsection
