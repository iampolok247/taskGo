@extends('layouts.admin')

@section('title', 'Create Announcement')

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
            <h1 class="text-2xl font-bold text-gray-900">Create Announcement</h1>
            <p class="text-gray-500">Broadcast a message to users or agents</p>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <form action="{{ route('admin.announcements.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required 
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
                    placeholder="Write your announcement message...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="promo" {{ old('type') === 'promo' ? 'selected' : '' }}>Promo</option>
                        <option value="update" {{ old('type') === 'update' ? 'selected' : '' }}>Update</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience <span class="text-red-500">*</span></label>
                    <select name="target" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ old('target') === 'all' ? 'selected' : '' }}>All (Users & Agents)</option>
                        <option value="users" {{ old('target') === 'users' ? 'selected' : '' }}>Users Only</option>
                        <option value="agents" {{ old('target') === 'agents' ? 'selected' : '' }}>Agents Only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Starts At</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to start immediately</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ends At</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty for no expiry</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} 
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <span class="text-sm text-gray-700">Pin this announcement</span>
                </label>
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Publish Announcement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
