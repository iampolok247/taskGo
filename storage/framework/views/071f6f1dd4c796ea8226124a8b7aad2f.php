<?php $__env->startSection('title', 'Deposits'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Header with Add Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Deposit History</h2>
            <p class="text-sm text-gray-500">Your deposit transactions</p>
        </div>
        <a href="<?php echo e(route('user.deposits.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500 text-white rounded-xl text-sm font-medium hover:bg-primary-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Deposit
        </a>
    </div>

    <!-- Deposits List -->
    <?php if($deposits->count() > 0): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                <?php if($deposit->status === 'approved'): ?> bg-green-100 text-green-600
                                <?php elseif($deposit->status === 'pending'): ?> bg-yellow-100 text-yellow-600
                                <?php else: ?> bg-red-100 text-red-600 <?php endif; ?>">
                                <?php if($deposit->status === 'approved'): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                <?php elseif($deposit->status === 'pending'): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e(ucfirst($deposit->method)); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($deposit->created_at->format('M d, Y h:i A')); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">৳<?php echo e(number_format($deposit->amount, 2)); ?></p>
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                <?php if($deposit->status === 'approved'): ?> bg-green-100 text-green-700
                                <?php elseif($deposit->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                                <?php echo e(ucfirst($deposit->status)); ?>

                            </span>
                        </div>
                    </div>
                    <?php if($deposit->transaction_id): ?>
                        <p class="text-xs text-gray-500 mt-2">TxID: <?php echo e($deposit->transaction_id); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($deposits->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-gray-900 font-medium mb-2">No Deposits Yet</h3>
            <p class="text-gray-500 text-sm mb-4">Make your first deposit to get started</p>
            <a href="<?php echo e(route('user.deposits.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500 text-white rounded-xl text-sm font-medium hover:bg-primary-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Make Deposit
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/deposits/index.blade.php ENDPATH**/ ?>