@extends('layouts.agent')

@section('title', 'Notifications')

@section('content')
<div class="p-4 space-y-4">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">My Notifications</h3>

        @if(isset($notifications) && $notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="border border-gray-200 rounded-xl p-4 transition hover:bg-gray-50 {{ $notification->read_at ? 'opacity-70' : 'bg-blue-50' }}">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['message'] ?? 'Notification' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">You have no notifications.</p>
            </div>
        @endif
    </div>
</div>
@endsection
