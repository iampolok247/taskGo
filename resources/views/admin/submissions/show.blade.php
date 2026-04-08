@extends('layouts.admin')

@section('title', 'Review Submission')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.submissions.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Review Submission</h1>
                <p class="text-gray-500">{{ $submission->task->title ?? 'Task' }}</p>
            </div>
        </div>
        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $submission->status === 'approved' ? 'bg-green-100 text-green-700' : ($submission->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
            {{ ucfirst($submission->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Submission Details -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Submission Proof</h3>
                
                @if($submission->proof_image)
                <div class="mb-4">
                    <a href="{{ asset('storage/' . $submission->proof_image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $submission->proof_image) }}" alt="Proof Screenshot" class="w-full rounded-xl border">
                    </a>
                </div>
                @endif

                @if($submission->proof_url)
                <div class="mb-4 p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500 mb-1">Submitted URL:</p>
                    <a href="{{ $submission->proof_url }}" target="_blank" class="text-primary-600 hover:underline break-all">
                        {{ $submission->proof_url }}
                    </a>
                </div>
                @endif

                @if($submission->proof_text)
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-sm text-gray-500 mb-1">User Notes:</p>
                    <p class="text-gray-700">{{ $submission->proof_text }}</p>
                </div>
                @endif

                @if(!$submission->proof_image && !$submission->proof_url && !$submission->proof_text)
                <p class="text-gray-500">No proof submitted</p>
                @endif
            </div>

            <!-- Task Info -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Task Details</h3>
                @if($submission->task)
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $submission->task->title }}</h4>
                        <p class="text-gray-600 mt-1">{{ $submission->task->description }}</p>
                    </div>
                    
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-500 mb-2">Instructions:</p>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($submission->task->instructions)) !!}
                        </div>
                    </div>

                    @if($submission->task->proof_instructions)
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-500 mb-2">Proof Requirements:</p>
                        <p class="text-gray-700">{{ $submission->task->proof_instructions }}</p>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-gray-500">Task information not available</p>
                @endif
            </div>

            <!-- Review Actions -->
            @if($submission->status === 'pending')
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Review Actions</h3>
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('admin.submissions.approve', $submission) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve Submission
                        </button>
                    </form>
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="flex-1 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reject Submission
                    </button>
                </div>
            </div>
            @endif

            <!-- Review Result -->
            @if($submission->status !== 'pending')
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Review Result</h3>
                <div class="p-4 rounded-xl {{ $submission->status === 'approved' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-start gap-3">
                        @if($submission->status === 'approved')
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-green-800">Approved</p>
                            <p class="text-sm text-green-700">Reward of ৳{{ number_format($submission->reward_amount, 2) }} was credited to user's wallet.</p>
                        </div>
                        @else
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-red-800">Rejected</p>
                            @if($submission->rejection_reason)
                            <p class="text-sm text-red-700">{{ $submission->rejection_reason }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @if($submission->reviewer && $submission->reviewed_at)
                    <p class="text-sm text-gray-500 mt-3">
                        Reviewed by {{ $submission->reviewer->name }} on {{ $submission->reviewed_at->format('M d, Y H:i') }}
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">User Information</h3>
                @if($submission->user)
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ strtoupper(substr($submission->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $submission->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $submission->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $submission->user) }}" class="block text-center py-2 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    View User Profile
                </a>
                @else
                <p class="text-gray-500">User information not available</p>
                @endif
            </div>

            <!-- Submission Info -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Submission Info</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Reward</span>
                        <span class="font-medium text-green-600">৳{{ number_format($submission->reward_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Proof Type</span>
                        <span class="font-medium capitalize">
                            @if($submission->proof_image)
                                Screenshot
                            @elseif($submission->proof_url)
                                URL
                            @elseif($submission->proof_text)
                                Text
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Submitted</span>
                        <span class="font-medium">{{ $submission->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Time</span>
                        <span class="font-medium">{{ $submission->created_at->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Status</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $submission->status === 'approved' ? 'bg-green-100 text-green-700' : ($submission->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($submission->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Reject Submission</h3>
                <button onclick="document.getElementById('rejectModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.submissions.reject', $submission) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                <textarea name="rejection_reason" rows="4" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Explain why this submission is being rejected..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
