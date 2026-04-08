@extends('layouts.user')

@section('title', 'Task Details')

@section('content')
<div class="p-4 space-y-4">
    <!-- Task Image -->
    @if($task->thumbnail)
        <img src="{{ asset('storage/' . $task->thumbnail) }}" alt="{{ $task->title }}" class="w-full h-48 object-cover rounded-xl">
    @endif

    <!-- Task Info Card -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-900">{{ $task->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ ucfirst($task->category) }}</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-green-600">৳{{ number_format($task->reward, 2) }}</p>
                <p class="text-xs text-gray-500">Reward</p>
            </div>
        </div>
        
        <div class="grid grid-cols-3 gap-4 p-3 bg-gray-50 rounded-xl">
            <div class="text-center">
                <p class="text-lg font-bold text-gray-900">{{ $task->estimated_time }}</p>
                <p class="text-xs text-gray-500">Minutes</p>
            </div>
            <div class="text-center border-x border-gray-200">
                <p class="text-lg font-bold text-gray-900">{{ $task->completed_count }}</p>
                <p class="text-xs text-gray-500">Completed</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-bold text-gray-900">{{ $task->total_limit > 0 ? $task->total_limit - $task->completed_count : '∞' }}</p>
                <p class="text-xs text-gray-500">Remaining</p>
            </div>
        </div>
    </div>

    <!-- Task Description -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-3">Description</h3>
        <div class="prose prose-sm text-gray-600">
            {!! nl2br(e($task->description)) !!}
        </div>
    </div>

    <!-- Task Instructions -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-3">Instructions</h3>
        <div class="space-y-3">
            @foreach(explode("\n", $task->instructions) as $index => $instruction)
                @if(trim($instruction))
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center flex-shrink-0 text-sm font-medium">
                            {{ $index + 1 }}
                        </div>
                        <p class="text-sm text-gray-600 pt-0.5">{{ trim($instruction) }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Existing Submission Status -->
    @if($submission)
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Your Submission</h3>
            
            <div class="mb-4">
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($submission->status == 'pending') bg-yellow-100 text-yellow-700
                    @elseif($submission->status == 'approved') bg-green-100 text-green-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($submission->status) }}
                </span>
                <span class="text-sm text-gray-500 ml-2">{{ $submission->created_at->diffForHumans() }}</span>
            </div>
            
            @if($submission->proof_url)
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">Proof URL:</p>
                    <a href="{{ $submission->proof_url }}" target="_blank" class="text-sm text-primary-500 break-all">{{ $submission->proof_url }}</a>
                </div>
            @endif
            
            @if($submission->proof_image)
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">Proof Image:</p>
                    <img src="{{ asset('storage/' . $submission->proof_image) }}" alt="Proof" class="w-full rounded-lg">
                </div>
            @endif
            
            @if($submission->proof_text)
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">Your Notes:</p>
                    <p class="text-sm text-gray-600">{{ $submission->proof_text }}</p>
                </div>
            @endif
            
            @if($submission->status == 'rejected' && $submission->rejection_reason)
                <div class="mt-4 p-3 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-red-700 mb-1">Rejection Reason:</p>
                    <p class="text-sm text-red-600">{{ $submission->rejection_reason }}</p>
                </div>
            @endif
            
            @if($submission->status == 'approved')
                <div class="mt-4 p-3 bg-green-50 rounded-lg flex items-center gap-3">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-green-700">Task Completed!</p>
                        <p class="text-sm text-green-600">৳{{ number_format($task->reward, 2) }} has been added to your wallet.</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Submission Form -->
    @if(!$submission || $submission->status == 'rejected')
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">{{ $submission ? 'Resubmit Task' : 'Submit Task' }}</h3>
            
            <form action="{{ route('user.tasks.submit', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                @if($task->proof_type == 'url')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proof URL</label>
                        <input type="url" name="proof_url" value="{{ old('proof_url') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="https://example.com/proof">
                        @error('proof_url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
                
                @if($task->proof_type == 'screenshot' || $task->proof_type == 'image')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proof Screenshot</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center" id="dropzone">
                            <input type="file" name="proof_image" id="proof_image" accept="image/*" class="hidden">
                            <div id="preview-container" class="hidden">
                                <img id="preview-image" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg mb-3">
                                <button type="button" onclick="removeImage()" class="text-red-500 text-sm">Remove</button>
                            </div>
                            <div id="upload-prompt">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 mb-2">Tap to upload screenshot</p>
                                <p class="text-xs text-gray-400">PNG, JPG up to 5MB</p>
                            </div>
                        </div>
                        @error('proof_image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none" placeholder="Any additional information...">{{ old('notes') }}</textarea>
                </div>
                
                <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all active:scale-[0.98]">
                    Submit Task
                </button>
            </form>
        </div>
    @endif
</div>

<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('proof_image');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const uploadPrompt = document.getElementById('upload-prompt');

if (dropzone) {
    dropzone.addEventListener('click', () => fileInput.click());
    
    fileInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
}

function removeImage() {
    fileInput.value = '';
    previewContainer.classList.add('hidden');
    uploadPrompt.classList.remove('hidden');
}
</script>
@endsection
