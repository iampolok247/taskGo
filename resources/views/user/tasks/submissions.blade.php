@extends('layouts.user')

@section('title', 'My Submissions')

@section('content')
<div class="p-4 space-y-4">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">My Task Submissions</h3>

        @if($submissions->count() > 0)
            <div class="space-y-4">
                @foreach($submissions as $submission)
                    <div class="border border-gray-200 rounded-xl p-4 transition hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $submission->task->title ?? 'Unknown Task' }}</h4>
                                <p class="text-xs text-gray-500">Submitted: {{ $submission->created_at->format('d M, Y h:i A') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($submission->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($submission->status == 'approved') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif
                            ">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>

                        <div class="mt-3 text-sm text-gray-700">
                            <strong>Reward:</strong> ৳{{ number_format($submission->reward_amount ?? 0, 2) }}
                        </div>

                        @if($submission->status === 'rejected' && $submission->rejection_reason)
                            <div class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded-lg">
                                <strong>Reason:</strong> {{ $submission->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">You haven't submitted any tasks yet.</p>
                <a href="{{ route('user.tasks.index') }}" class="inline-block mt-4 text-primary-500 font-medium hover:underline">
                    Browse Available Tasks
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
