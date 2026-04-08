<?php $__env->startSection('title', 'Create Announcement'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 max-w-2xl">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.announcements.index')); ?>" class="p-2 hover:bg-gray-100 rounded-lg">
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
        <form action="<?php echo e(route('admin.announcements.store')); ?>" method="POST" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="<?php echo e(old('title')); ?>" required 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="Announcement title">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                <textarea name="content" rows="5" required 
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="Write your announcement message..."><?php echo e(old('content')); ?></textarea>
                <?php $__errorArgs = ['content'];
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="info" <?php echo e(old('type') === 'info' ? 'selected' : ''); ?>>Info</option>
                        <option value="warning" <?php echo e(old('type') === 'warning' ? 'selected' : ''); ?>>Warning</option>
                        <option value="promo" <?php echo e(old('type') === 'promo' ? 'selected' : ''); ?>>Promo</option>
                        <option value="update" <?php echo e(old('type') === 'update' ? 'selected' : ''); ?>>Update</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience <span class="text-red-500">*</span></label>
                    <select name="target" required class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" <?php echo e(old('target') === 'all' ? 'selected' : ''); ?>>All (Users & Agents)</option>
                        <option value="users" <?php echo e(old('target') === 'users' ? 'selected' : ''); ?>>Users Only</option>
                        <option value="agents" <?php echo e(old('target') === 'agents' ? 'selected' : ''); ?>>Agents Only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Starts At</label>
                    <input type="datetime-local" name="starts_at" value="<?php echo e(old('starts_at')); ?>" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to start immediately</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ends At</label>
                    <input type="datetime-local" name="ends_at" value="<?php echo e(old('ends_at')); ?>" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty for no expiry</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_pinned" value="1" <?php echo e(old('is_pinned') ? 'checked' : ''); ?> 
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <span class="text-sm text-gray-700">Pin this announcement</span>
                </label>
            </div>

            <div class="border-t pt-6 flex items-center justify-end gap-3">
                <a href="<?php echo e(route('admin.announcements.index')); ?>" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all">
                    Publish Announcement
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/announcements/create.blade.php ENDPATH**/ ?>