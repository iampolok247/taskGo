<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Welcome back, <?php echo e(auth('admin')->user()->name); ?>!</h1>
        <p class="text-gray-300">Here's what's happening with Task Go today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_users'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-2">+<?php echo e($stats['new_users_today']); ?> today</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Agents</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_agents'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2"><?php echo e($stats['active_agents']); ?> active</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Deposits</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['pending_deposits'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-yellow-600 mt-2">৳<?php echo e(number_format($stats['pending_deposit_amount'])); ?> pending</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['pending_withdrawals'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-red-600 mt-2">৳<?php echo e(number_format($stats['pending_withdrawal_amount'])); ?> pending</p>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Tasks</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_tasks'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2"><?php echo e($stats['active_tasks']); ?> active</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Submissions</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['pending_submissions'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-orange-600 mt-2">Needs review</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Today's Paid Out</p>
                    <p class="text-2xl font-bold text-gray-900">৳<?php echo e(number_format($stats['today_paid'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-emerald-600 mt-2"><?php echo e($stats['today_completed_tasks']); ?> tasks completed</p>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-4 space-y-3">
                <a href="<?php echo e(route('admin.deposits.index', ['status' => 'pending'])); ?>" class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-all">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Review Deposits</p>
                        <p class="text-sm text-gray-500"><?php echo e($stats['pending_deposits']); ?> pending</p>
                    </div>
                </a>
                
                <a href="<?php echo e(route('admin.withdrawals.index', ['status' => 'pending'])); ?>" class="flex items-center gap-3 p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-all">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Process Withdrawals</p>
                        <p class="text-sm text-gray-500"><?php echo e($stats['pending_withdrawals']); ?> pending</p>
                    </div>
                </a>
                
                <a href="<?php echo e(route('admin.submissions.index', ['status' => 'pending'])); ?>" class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-all">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Review Tasks</p>
                        <p class="text-sm text-gray-500"><?php echo e($stats['pending_submissions']); ?> pending</p>
                    </div>
                </a>
                
                <a href="<?php echo e(route('admin.tasks.create')); ?>" class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-all">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Create Task</p>
                        <p class="text-sm text-gray-500">Add new task</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Deposits -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Recent Deposits</h3>
                    <a href="<?php echo e(route('admin.deposits.index')); ?>" class="text-sm text-primary-600 font-medium">View All</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $recentDeposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            <?php if($deposit->status == 'approved'): ?> bg-green-100
                            <?php elseif($deposit->status == 'pending'): ?> bg-yellow-100
                            <?php else: ?> bg-red-100 <?php endif; ?>">
                            <?php if($deposit->status == 'approved'): ?>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            <?php elseif($deposit->status == 'pending'): ?>
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($deposit->user->name ?? 'Unknown'); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($deposit->created_at->diffForHumans()); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">৳<?php echo e(number_format($deposit->amount, 2)); ?></p>
                            <span class="text-xs capitalize
                                <?php if($deposit->status == 'approved'): ?> text-green-600
                                <?php elseif($deposit->status == 'pending'): ?> text-yellow-600
                                <?php else: ?> text-red-600 <?php endif; ?>">
                                <?php echo e($deposit->status); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-8 text-center">
                        <p class="text-gray-500">No recent deposits</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Recent Withdrawals</h3>
                    <a href="<?php echo e(route('admin.withdrawals.index')); ?>" class="text-sm text-primary-600 font-medium">View All</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $recentWithdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            <?php if($withdrawal->status == 'approved'): ?> bg-green-100
                            <?php elseif($withdrawal->status == 'pending'): ?> bg-yellow-100
                            <?php else: ?> bg-red-100 <?php endif; ?>">
                            <?php if($withdrawal->status == 'approved'): ?>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            <?php elseif($withdrawal->status == 'pending'): ?>
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($withdrawal->user->name ?? 'Unknown'); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($withdrawal->created_at->diffForHumans()); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">৳<?php echo e(number_format($withdrawal->amount, 2)); ?></p>
                            <span class="text-xs capitalize
                                <?php if($withdrawal->status == 'approved'): ?> text-green-600
                                <?php elseif($withdrawal->status == 'pending'): ?> text-yellow-600
                                <?php else: ?> text-red-600 <?php endif; ?>">
                                <?php echo e($withdrawal->status); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-8 text-center">
                        <p class="text-gray-500">No recent withdrawals</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>