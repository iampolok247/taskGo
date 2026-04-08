<?php $__env->startSection('title', 'Task Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Task Image -->
    <?php if($task->thumbnail): ?>
        <img src="<?php echo e(asset('storage/' . $task->thumbnail)); ?>" alt="<?php echo e($task->title); ?>" class="w-full h-48 object-cover rounded-xl">
    <?php endif; ?>

    <!-- Task Info Card -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-900"><?php echo e($task->title); ?></h2>
                <p class="text-sm text-gray-500 mt-1"><?php echo e(ucfirst($task->category)); ?></p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-green-600">৳<?php echo e(number_format($task->reward, 2)); ?></p>
                <p class="text-xs text-gray-500">Reward</p>
            </div>
        </div>
        
        <div class="grid grid-cols-3 gap-4 p-3 bg-gray-50 rounded-xl">
            <div class="text-center">
                <p class="text-lg font-bold text-gray-900"><?php echo e($task->estimated_time); ?></p>
                <p class="text-xs text-gray-500">Minutes</p>
            </div>
            <div class="text-center border-x border-gray-200">
                <p class="text-lg font-bold text-gray-900"><?php echo e($task->completed_count); ?></p>
                <p class="text-xs text-gray-500">Completed</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-bold text-gray-900"><?php echo e($task->total_limit > 0 ? $task->total_limit - $task->completed_count : '∞'); ?></p>
                <p class="text-xs text-gray-500">Remaining</p>
            </div>
        </div>
    </div>

    <!-- Task Description -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-3">Description</h3>
        <div class="prose prose-sm text-gray-600">
            <?php echo nl2br(e($task->description)); ?>

        </div>
    </div>

    <!-- Task Instructions -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-3">Instructions</h3>
        <div class="space-y-3">
            <?php $__currentLoopData = explode("\n", $task->instructions); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $instruction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(trim($instruction)): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center flex-shrink-0 text-sm font-medium">
                            <?php echo e($index + 1); ?>

                        </div>
                        <p class="text-sm text-gray-600 pt-0.5"><?php echo e(trim($instruction)); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Existing Submission Status -->
    <?php if($submission): ?>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Your Submission</h3>
            
            <div class="mb-4">
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    <?php if($submission->status == 'pending'): ?> bg-yellow-100 text-yellow-700
                    <?php elseif($submission->status == 'approved'): ?> bg-green-100 text-green-700
                    <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                    <?php echo e(ucfirst($submission->status)); ?>

                </span>
                <span class="text-sm text-gray-500 ml-2"><?php echo e($submission->created_at->diffForHumans()); ?></span>
            </div>
            
            <?php if($submission->proof_url): ?>
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">Proof URL:</p>
                    <a href="<?php echo e($submission->proof_url); ?>" target="_blank" class="text-sm text-primary-500 break-all"><?php echo e($submission->proof_url); ?></a>
                </div>
            <?php endif; ?>
            
            <?php if($submission->proof_image): ?>
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">Proof Image:</p>
                    <img src="<?php echo e(asset('storage/' . $submission->proof_image)); ?>" alt="Proof" class="w-full rounded-lg">
                </div>
            <?php endif; ?>
            
            <?php if($submission->proof_text): ?>
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-1">Your Notes:</p>
                    <p class="text-sm text-gray-600"><?php echo e($submission->proof_text); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if($submission->status == 'rejected' && $submission->rejection_reason): ?>
                <div class="mt-4 p-3 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-red-700 mb-1">Rejection Reason:</p>
                    <p class="text-sm text-red-600"><?php echo e($submission->rejection_reason); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if($submission->status == 'approved'): ?>
                <div class="mt-4 p-3 bg-green-50 rounded-lg flex items-center gap-3">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-green-700">Task Completed!</p>
                        <p class="text-sm text-green-600">৳<?php echo e(number_format($task->reward, 2)); ?> has been added to your wallet.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Submission Form -->
    <?php if(!$submission || $submission->status == 'rejected'): ?>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4"><?php echo e($submission ? 'Resubmit Task' : 'Submit Task'); ?></h3>
            
            <form action="<?php echo e(route('user.tasks.submit', $task)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <?php if($task->proof_type == 'url'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proof URL</label>
                        <input type="url" name="proof_url" value="<?php echo e(old('proof_url')); ?>" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="https://example.com/proof">
                        <?php $__errorArgs = ['proof_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
                
                <?php if($task->proof_type == 'screenshot' || $task->proof_type == 'image'): ?>
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
                        <?php $__errorArgs = ['proof_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none" placeholder="Any additional information..."><?php echo e(old('notes')); ?></textarea>
                </div>
                
                <button type="submit" class="w-full py-3 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all active:scale-[0.98]">
                    Submit Task
                </button>
            </form>
        </div>
    <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/tasks/show.blade.php ENDPATH**/ ?>