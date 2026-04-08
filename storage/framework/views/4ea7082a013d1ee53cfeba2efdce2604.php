<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">My Notifications</h3>

        <?php if(isset($notifications) && $notifications->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-xl p-4 transition hover:bg-gray-50 <?php echo e($notification->read_at ? 'opacity-70' : 'bg-blue-50'); ?>">
                        <p class="text-sm font-medium text-gray-900"><?php echo e($notification->data['message'] ?? 'Notification'); ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-4">
                <?php echo e($notifications->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">You have no notifications.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/agent/profile/notifications.blade.php ENDPATH**/ ?>