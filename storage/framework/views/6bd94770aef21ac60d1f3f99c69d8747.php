<?php $__env->startSection('title', 'My Submissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">My Task Submissions</h3>

        <?php if($submissions->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-xl p-4 transition hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900"><?php echo e($submission->task->title ?? 'Unknown Task'); ?></h4>
                                <p class="text-xs text-gray-500">Submitted: <?php echo e($submission->created_at->format('d M, Y h:i A')); ?></p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                <?php if($submission->status == 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($submission->status == 'approved'): ?> bg-green-100 text-green-700
                                <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>
                            ">
                                <?php echo e(ucfirst($submission->status)); ?>

                            </span>
                        </div>

                        <div class="mt-3 text-sm text-gray-700">
                            <strong>Reward:</strong> ৳<?php echo e(number_format($submission->reward_amount ?? 0, 2)); ?>

                        </div>

                        <?php if($submission->status === 'rejected' && $submission->rejection_reason): ?>
                            <div class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded-lg">
                                <strong>Reason:</strong> <?php echo e($submission->rejection_reason); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-4">
                <?php echo e($submissions->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">You haven't submitted any tasks yet.</p>
                <a href="<?php echo e(route('user.tasks.index')); ?>" class="inline-block mt-4 text-primary-500 font-medium hover:underline">
                    Browse Available Tasks
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/tasks/submissions.blade.php ENDPATH**/ ?>