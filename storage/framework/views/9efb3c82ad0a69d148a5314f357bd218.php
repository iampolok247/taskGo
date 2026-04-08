<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
            <p class="text-sm text-gray-500">Stay updated with your activities</p>
        </div>
        <?php if($notifications->where('is_read', false)->count() > 0): ?>
            <form method="POST" action="<?php echo e(route('user.notifications.read-all')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    Mark all as read
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Notifications List -->
    <?php if($notifications->count() > 0): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl p-4 shadow-sm <?php echo e(!$notification->is_read ? 'border-l-4 border-primary-500' : ''); ?>">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            <?php if($notification->type === 'success'): ?> bg-green-100 text-green-600
                            <?php elseif($notification->type === 'warning'): ?> bg-yellow-100 text-yellow-600
                            <?php elseif($notification->type === 'error'): ?> bg-red-100 text-red-600
                            <?php else: ?> bg-blue-100 text-blue-600 <?php endif; ?>">
                            <?php if($notification->type === 'success'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            <?php elseif($notification->type === 'warning'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            <?php elseif($notification->type === 'error'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-medium text-gray-900"><?php echo e($notification->title); ?></p>
                                <?php if(!$notification->is_read): ?>
                                    <span class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0 mt-2"></span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e($notification->message); ?></p>
                            <p class="text-xs text-gray-400 mt-2"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php if(!$notification->is_read): ?>
                        <form method="POST" action="<?php echo e(route('user.notifications.read', $notification)); ?>" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-xs text-primary-600 hover:text-primary-700">
                                Mark as read
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($notifications->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-gray-900 font-medium mb-2">No Notifications</h3>
            <p class="text-gray-500 text-sm">You're all caught up! Check back later.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/profile/notifications.blade.php ENDPATH**/ ?>