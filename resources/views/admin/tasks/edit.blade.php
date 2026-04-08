@extends('layouts.admin')

@section('title', 'Edit: ' . $task->title)

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tasks.show', $task) }}" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Task</h1>
            <p class="text-gray-500">{{ $task->title }}</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <form action="{{ route('admin.tasks.update', $task) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror"
                        placeholder="Enter task title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="social" {{ $task->category === 'social' ? 'selected' : '' }}>Social</option>
                        <option value="survey" {{ $task->category === 'survey' ? 'selected' : '' }}>Survey</option>
                        <option value="video" {{ $task->category === 'video' ? 'selected' : '' }}>Video</option>
                        <option value="app" {{ $task->category === 'app' ? 'selected' : '' }}>App</option>
                        <option value="website" {{ $task->category === 'website' ? 'selected' : '' }}>Website</option>
                        <option value="other" {{ $task->category === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reward (৳) <span class="text-red-500">*</span></label>
                    <input type="number" name="reward" value="{{ old('reward', $task->reward) }}" step="0.01" min="1" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('reward') border-red-500 @enderror">
                    @error('reward')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror"
                        placeholder="Brief description of the task">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructions <span class="text-red-500">*</span></label>
                    <textarea name="instructions" rows="5" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('instructions') border-red-500 @enderror"
                        placeholder="Step by step instructions for completing the task">{{ old('instructions', $task->instructions) }}</textarea>
                    @error('instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task URL</label>
                    <input type="url" name="task_url" value="{{ old('task_url', $task->task_url) }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('task_url') border-red-500 @enderror"
                        placeholder="https://example.com">
                    @error('task_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty <span class="text-red-500">*</span></label>
                    <select name="difficulty" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="easy" {{ $task->difficulty === 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ $task->difficulty === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ $task->difficulty === 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Time (minutes) <span class="text-red-500">*</span></label>
                    <input type="number" name="estimated_time" value="{{ old('estimated_time', $task->estimated_time) }}" min="1" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof Type <span class="text-red-500">*</span></label>
                    <select name="proof_type" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="screenshot" {{ $task->proof_type === 'screenshot' ? 'selected' : '' }}>Screenshot</option>
                        <option value="url" {{ $task->proof_type === 'url' ? 'selected' : '' }}>URL</option>
                        <option value="text" {{ $task->proof_type === 'text' ? 'selected' : '' }}>Text</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof Instructions</label>
                    <textarea name="proof_instructions" rows="2" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="What proof should users submit?">{{ old('proof_instructions', $task->proof_instructions) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Limit <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_limit" value="{{ old('daily_limit', $task->daily_limit) }}" min="0" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">0 = Unlimited</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Limit <span class="text-red-500">*</span></label>
                    <input type="number" name="total_limit" value="{{ old('total_limit', $task->total_limit) }}" min="0" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">0 = Unlimited</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <input type="number" name="priority" value="{{ old('priority', $task->priority) }}" min="0" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Higher = More visible</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="active" {{ $task->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $task->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $task->expires_at?->format('Y-m-d\TH:i')) }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
                    @if($task->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $task->thumbnail) }}" alt="Current thumbnail" class="h-20 rounded-lg">
                    </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image</p>
                </div>
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.tasks.show', $task) }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
