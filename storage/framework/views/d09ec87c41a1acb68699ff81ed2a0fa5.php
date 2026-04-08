<?php $__env->startSection('title', 'Manage Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Users</h1>
            <p class="text-gray-500">View and manage all registered users</p>
        </div>
        
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-2xl font-bold text-green-600"><?php echo e($stats['active']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">Inactive</p>
            <p class="text-2xl font-bold text-red-600"><?php echo e($stats['inactive']); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-500">This Month</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['this_month']); ?></p>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="<?php echo e(route('admin.users.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Search by name, email, or phone...">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
            <select name="agent_id" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">All Agents</option>
                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($agent->id); ?>" <?php echo e(request('agent_id') == $agent->id ? 'selected' : ''); ?>><?php echo e($agent->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                Filter
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <?php if($users->count() > 0): ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
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
                                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                            <span class="text-primary-600 font-bold text-sm"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></span>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($user->agent->name ?? 'Direct'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">৳<?php echo e(number_format($user->wallet->main_balance ?? 0, 2)); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($user->taskSubmissions->where('status', 'approved')->count()); ?> tasks</div>
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
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="<?php echo e(route('admin.users.toggle-status', $user)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="<?php echo e($user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'); ?>">
                                                <?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
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
            <p class="text-gray-500">No users match your search criteria.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/users/index.blade.php ENDPATH**/ ?>