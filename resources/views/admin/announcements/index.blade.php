@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="text-gray-500">Manage announcements for users and agents</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Announcement
        </a>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse($announcements ?? [] as $announcement)
        <div class="bg-white rounded-xl p-6 shadow-sm {{ $announcement->is_pinned ? 'border-l-4 border-primary-500' : '' }}">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        @if($announcement->is_pinned)
                        <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"></path>
                        </svg>
                        @endif
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $announcement->type === 'info' ? 'bg-blue-100 text-blue-700' : ($announcement->type === 'warning' ? 'bg-yellow-100 text-yellow-700' : ($announcement->type === 'promo' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700')) }}">
                            {{ ucfirst($announcement->type) }}
                        </span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $announcement->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="text-xs text-gray-500">For: {{ ucfirst($announcement->target) }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $announcement->title }}</h3>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ $announcement->content }}</p>
                    <div class="mt-2 text-xs text-gray-500">
                        By {{ $announcement->creator->name ?? 'Admin' }} • {{ $announcement->created_at->format('M d, Y H:i') }}
                        @if($announcement->starts_at || $announcement->ends_at)
                        <br>
                        @if($announcement->starts_at)
                        Starts: {{ $announcement->starts_at->format('M d, Y') }}
                        @endif
                        @if($announcement->ends_at)
                        • Ends: {{ $announcement->ends_at->format('M d, Y') }}
                        @endif
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.announcements.toggle-status', $announcement) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg" title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                            @if($announcement->is_active)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            @endif
                        </button>
                    </form>
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-12 shadow-sm text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            <p class="font-medium text-gray-900 mb-1">No announcements yet</p>
            <p class="text-gray-500 text-sm mb-4">Create your first announcement to notify users.</p>
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                Create Announcement
            </a>
        </div>
        @endforelse
    </div>

    @if(isset($announcements) && $announcements->hasPages())
    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
    @endif
</div>
@endsection
