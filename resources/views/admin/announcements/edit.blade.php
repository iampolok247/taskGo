@extends('layouts.admin')

@section('title', 'Edit Announcement')

@section('content')
<div class="space-y-6 max-w-2xl">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.announcements.index') }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Announcement</h1>
            <p class="text-gray-500">Update announcement details</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror"
                    placeholder="Announcement title">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                <textarea name="content" rows="5" required 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('content') border-red-500 @enderror"
                    placeholder="Write your announcement message...">{{ old('content', $announcement->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="info" {{ $announcement->type === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="warning" {{ $announcement->type === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="promo" {{ $announcement->type === 'promo' ? 'selected' : '' }}>Promo</option>
                        <option value="update" {{ $announcement->type === 'update' ? 'selected' : '' }}>Update</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience <span class="text-red-500">*</span></label>
                    <select name="target" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ $announcement->target === 'all' ? 'selected' : '' }}>All (Freelancers & Leaders)</option>
                        <option value="users" {{ $announcement->target === 'users' ? 'selected' : '' }}>Freelancers Only</option>
                        <option value="agents" {{ $announcement->target === 'agents' ? 'selected' : '' }}>Leaders Only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Starts At</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $announcement->starts_at?->format('Y-m-d\TH:i')) }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ends At</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $announcement->ends_at?->format('Y-m-d\TH:i')) }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $announcement->is_active ? 'checked' : '' }} 
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_pinned" value="1" {{ $announcement->is_pinned ? 'checked' : '' }} 
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <span class="text-sm text-gray-700">Pinned</span>
                </label>
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Update Announcement
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl shadow-sm border border-red-200">
        <div class="p-6">
            <h3 class="font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-gray-500 text-sm mb-4">Once you delete an announcement, it cannot be recovered.</p>
            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all">
                    Delete Announcement
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
