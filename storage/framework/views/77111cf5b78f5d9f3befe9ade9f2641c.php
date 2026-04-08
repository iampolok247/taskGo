<?php $__env->startSection('title', 'Withdrawals'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 space-y-4">
    <!-- Header with Add Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Withdrawal History</h2>
            <p class="text-sm text-gray-500">Your withdrawal transactions</p>
        </div>
        <a href="<?php echo e(route('user.withdrawals.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-xl text-sm font-medium hover:bg-green-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            New Withdrawal
        </a>
    </div>

    <!-- Available Balance Card -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-4 text-white">
        <p class="text-green-100 text-sm">Available for Withdrawal</p>
        <p class="text-2xl font-bold">৳<?php echo e(number_format(auth()->user()->wallet->main_balance ?? 0, 2)); ?></p>
    </div>

    <!-- Withdrawals List -->
    <?php if($withdrawals->count() > 0): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                <?php if($withdrawal->status === 'completed'): ?> bg-green-100 text-green-600
                                <?php elseif($withdrawal->status === 'pending'): ?> bg-yellow-100 text-yellow-600
                                <?php elseif($withdrawal->status === 'processing'): ?> bg-blue-100 text-blue-600
                                <?php else: ?> bg-red-100 text-red-600 <?php endif; ?>">
                                <?php if($withdrawal->status === 'completed'): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                <?php elseif($withdrawal->status === 'pending' || $withdrawal->status === 'processing'): ?>
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
                                <p class="font-medium text-gray-900"><?php echo e(ucfirst($withdrawal->method)); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($withdrawal->created_at->format('M d, Y h:i A')); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">৳<?php echo e(number_format($withdrawal->amount, 2)); ?></p>
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                <?php if($withdrawal->status === 'completed'): ?> bg-green-100 text-green-700
                                <?php elseif($withdrawal->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($withdrawal->status === 'processing'): ?> bg-blue-100 text-blue-700
                                <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                                <?php echo e(ucfirst($withdrawal->status)); ?>

                            </span>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-500">
                        <p>To: <?php echo e($withdrawal->account_number); ?> (<?php echo e($withdrawal->account_name); ?>)</p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($withdrawals->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-gray-900 font-medium mb-2">No Withdrawals Yet</h3>
            <p class="text-gray-500 text-sm mb-4">Cash out your earnings when you're ready</p>
            <a href="<?php echo e(route('user.withdrawals.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-xl text-sm font-medium hover:bg-green-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Make Withdrawal
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/user/withdrawals/index.blade.php ENDPATH**/ ?>