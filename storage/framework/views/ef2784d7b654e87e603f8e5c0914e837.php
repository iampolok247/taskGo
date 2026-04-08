<?php $__env->startSection('title', 'Edit: ' . $task->title); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.tasks.show', $task)); ?>" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Task</h1>
            <p class="text-gray-500"><?php echo e($task->title); ?></p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <form action="<?php echo e(route('admin.tasks.update', $task)); ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo e(old('title', $task->title)); ?>" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Enter task title">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="social" <?php echo e($task->category === 'social' ? 'selected' : ''); ?>>Social</option>
                        <option value="survey" <?php echo e($task->category === 'survey' ? 'selected' : ''); ?>>Survey</option>
                        <option value="video" <?php echo e($task->category === 'video' ? 'selected' : ''); ?>>Video</option>
                        <option value="app" <?php echo e($task->category === 'app' ? 'selected' : ''); ?>>App</option>
                        <option value="website" <?php echo e($task->category === 'website' ? 'selected' : ''); ?>>Website</option>
                        <option value="other" <?php echo e($task->category === 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reward (৳) <span class="text-red-500">*</span></label>
                    <input type="number" name="reward" value="<?php echo e(old('reward', $task->reward)); ?>" step="0.01" min="1" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['reward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['reward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Brief description of the task"><?php echo e(old('description', $task->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructions <span class="text-red-500">*</span></label>
                    <textarea name="instructions" rows="5" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Step by step instructions for completing the task"><?php echo e(old('instructions', $task->instructions)); ?></textarea>
                    <?php $__errorArgs = ['instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task URL</label>
                    <input type="url" name="task_url" value="<?php echo e(old('task_url', $task->task_url)); ?>" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['task_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="https://example.com">
                    <?php $__errorArgs = ['task_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty <span class="text-red-500">*</span></label>
                    <select name="difficulty" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="easy" <?php echo e($task->difficulty === 'easy' ? 'selected' : ''); ?>>Easy</option>
                        <option value="medium" <?php echo e($task->difficulty === 'medium' ? 'selected' : ''); ?>>Medium</option>
                        <option value="hard" <?php echo e($task->difficulty === 'hard' ? 'selected' : ''); ?>>Hard</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Time (minutes) <span class="text-red-500">*</span></label>
                    <input type="number" name="estimated_time" value="<?php echo e(old('estimated_time', $task->estimated_time)); ?>" min="1" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof Type <span class="text-red-500">*</span></label>
                    <select name="proof_type" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="screenshot" <?php echo e($task->proof_type === 'screenshot' ? 'selected' : ''); ?>>Screenshot</option>
                        <option value="url" <?php echo e($task->proof_type === 'url' ? 'selected' : ''); ?>>URL</option>
                        <option value="text" <?php echo e($task->proof_type === 'text' ? 'selected' : ''); ?>>Text</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof Instructions</label>
                    <textarea name="proof_instructions" rows="2" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="What proof should users submit?"><?php echo e(old('proof_instructions', $task->proof_instructions)); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Limit <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_limit" value="<?php echo e(old('daily_limit', $task->daily_limit)); ?>" min="0" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">0 = Unlimited</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Limit <span class="text-red-500">*</span></label>
                    <input type="number" name="total_limit" value="<?php echo e(old('total_limit', $task->total_limit)); ?>" min="0" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">0 = Unlimited</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <input type="number" name="priority" value="<?php echo e(old('priority', $task->priority)); ?>" min="0" required 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Higher = More visible</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="active" <?php echo e($task->status === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e($task->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        <option value="completed" <?php echo e($task->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                    <input type="datetime-local" name="expires_at" value="<?php echo e(old('expires_at', $task->expires_at?->format('Y-m-d\TH:i'))); ?>" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
                    <?php if($task->thumbnail): ?>
                    <div class="mb-2">
                        <img src="<?php echo e(asset('storage/' . $task->thumbnail)); ?>" alt="Current thumbnail" class="h-20 rounded-lg">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" accept="image/*" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image</p>
                </div>
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="<?php echo e(route('admin.tasks.show', $task)); ?>" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/tasks/edit.blade.php ENDPATH**/ ?>