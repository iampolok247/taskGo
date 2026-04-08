<?php $__env->startSection('title', 'My Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Users</h1>
            <p class="text-gray-500">Manage users registered under your agency</p>
        </div>
        <a href="<?php echo e(route('agent.users.create')); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Create User
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Active Users</p>
            <p class="text-2xl font-bold text-green-600"><?php echo e($stats['active']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Inactive Users</p>
            <p class="text-2xl font-bold text-gray-400"><?php echo e($stats['inactive']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">This Month</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['this_month']); ?></p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="<?php echo e(route('agent.users.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    placeholder="Search by name, email or phone...">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-all">
                Search
            </button>
        </form>
    </div>

    <!-- Users List -->
    <?php if($users->count() > 0): ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                            <span class="text-emerald-600 font-bold text-sm"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></div>
                                            <div class="text-sm text-gray-500">#<?php echo e($user->id); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($user->email); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($user->phone ?? 'N/A'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">৳<?php echo e(number_format($user->wallet->total_earned ?? 0, 2)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($user->taskSubmissions->where('status', 'approved')->count()); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($user->is_active): ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($user->created_at->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?php echo e(route('agent.users.show', $user)); ?>" class="text-emerald-600 hover:text-emerald-900">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-100">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                                <span class="text-emerald-600 font-bold"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></span>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900"><?php echo e($user->name); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                            </div>
                            <?php if($user->is_active): ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Earnings:</span>
                                <span class="font-medium text-green-600">৳<?php echo e(number_format($user->wallet->total_earned ?? 0, 2)); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-500">Tasks:</span>
                                <span class="font-medium"><?php echo e($user->taskSubmissions->where('status', 'approved')->count()); ?></span>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Joined <?php echo e($user->created_at->format('M d, Y')); ?></span>
                            <a href="<?php echo e(route('agent.users.show', $user)); ?>" class="text-emerald-600 text-sm font-medium">View Details</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($users->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl p-8 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Users Found</h3>
            <p class="text-gray-500 mb-4">Start by creating your first user.</p>
            <a href="<?php echo e(route('agent.users.create')); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition-all">
                Create First User
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/agent/users/index.blade.php ENDPATH**/ ?>