<?php $__env->startSection('title', 'Agent: ' . $agent->name); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.agents.index')); ?>" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?php echo e($agent->name); ?></h1>
                <p class="text-gray-500">Agent Code: <span class="font-mono text-primary-600"><?php echo e($agent->agent_code); ?></span></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.agents.edit', $agent)); ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="<?php echo e(route('admin.agents.toggle-status', $agent)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit" class="px-4 py-2 <?php echo e($agent->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white font-medium rounded-xl transition-all">
                    <?php echo e($agent->status === 'active' ? 'Block Agent' : 'Activate Agent'); ?>

                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Users</p>
            <p class="text-xl font-bold text-gray-900"><?php echo e($stats['total_users']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Active Users</p>
            <p class="text-xl font-bold text-green-600"><?php echo e($stats['active_users']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Earnings</p>
            <p class="text-xl font-bold text-primary-600">৳<?php echo e(number_format($stats['total_earnings'], 2)); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Pending</p>
            <p class="text-xl font-bold text-yellow-600">৳<?php echo e(number_format($stats['pending_earnings'], 2)); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Withdrawn</p>
            <p class="text-xl font-bold text-blue-600">৳<?php echo e(number_format($stats['withdrawn_earnings'], 2)); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Agent Info Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Agent Information</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white"><?php echo e(strtoupper(substr($agent->name, 0, 1))); ?></span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?php echo e($agent->name); ?></p>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($agent->status === 'active' ? 'bg-green-100 text-green-700' : ($agent->status === 'blocked' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')); ?>">
                            <?php echo e(ucfirst($agent->status)); ?>

                        </span>
                    </div>
                </div>
                
                <div class="border-t pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium"><?php echo e($agent->email); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="font-medium"><?php echo e($agent->phone ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Agent Code</span>
                        <span class="font-mono text-primary-600"><?php echo e($agent->agent_code); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Commission Rate</span>
                        <span class="font-medium"><?php echo e($agent->commission_rate); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Joined</span>
                        <span class="font-medium"><?php echo e($agent->created_at->format('M d, Y')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Recent Commissions</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <div>
                        <p class="font-medium text-sm"><?php echo e($commission->user->name ?? 'User'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($commission->created_at->format('M d, H:i')); ?></p>
                    </div>
                    <span class="text-green-600 font-medium">+৳<?php echo e(number_format($commission->amount, 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-center py-4">No commissions yet</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-4">Recent Users</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-xs font-bold"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                        </div>
                        <div>
                            <p class="font-medium text-sm"><?php echo e($user->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($user->created_at->format('M d')); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="text-primary-600 hover:text-primary-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-center py-4">No users yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- All Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">All Users (<?php echo e($stats['total_users']); ?>)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $agent->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                                    <span class="font-bold text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($user->name); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e(ucfirst($user->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($user->created_at->format('M d, Y')); ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="text-primary-600 hover:text-primary-700 font-medium text-sm">View</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No users under this agent</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/agents/show.blade.php ENDPATH**/ ?>