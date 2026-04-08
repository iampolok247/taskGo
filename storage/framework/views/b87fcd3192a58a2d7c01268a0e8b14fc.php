<?php $__env->startSection('title', 'Task Submissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Task Submissions</h1>
            <p class="text-gray-500">Review and approve task submissions</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Pending Review</p>
            <p class="text-xl font-bold text-yellow-600"><?php echo e($stats['pending'] ?? 0); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Approved Today</p>
            <p class="text-xl font-bold text-green-600"><?php echo e($stats['approved_today'] ?? 0); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Rejected Today</p>
            <p class="text-xl font-bold text-red-600"><?php echo e($stats['rejected_today'] ?? 0); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Submissions</p>
            <p class="text-xl font-bold text-gray-900"><?php echo e($stats['total'] ?? 0); ?></p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <form action="<?php echo e(route('admin.submissions.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by user name or task..." 
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <select name="status" class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
            </select>
            <select name="task_id" class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Tasks</option>
                <?php $__currentLoopData = $tasks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($task->id); ?>" <?php echo e(request('task_id') == $task->id ? 'selected' : ''); ?>><?php echo e($task->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition-all">
                Filter
            </button>
        </form>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $submissions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center mr-3">
                                    <span class="font-bold text-white"><?php echo e(strtoupper(substr($submission->user->name ?? 'U', 0, 1))); ?></span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($submission->user->name ?? 'User'); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($submission->user->email ?? ''); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 truncate max-w-xs"><?php echo e($submission->task->title ?? 'Task'); ?></p>
                            <p class="text-sm text-gray-500 capitalize"><?php echo e($submission->task->category ?? ''); ?></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-green-600">৳<?php echo e(number_format($submission->reward_amount ?? 0, 2)); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($submission->status === 'approved' ? 'bg-green-100 text-green-700' : ($submission->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
                                <?php echo e(ucfirst($submission->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($submission->created_at->diffForHumans()); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo e(route('admin.submissions.show', $submission)); ?>" class="text-primary-600 hover:text-primary-900">
                                    Review
                                </a>
                                <?php if($submission->status === 'pending'): ?>
                                <form action="<?php echo e(route('admin.submissions.approve', $submission)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-green-600 hover:text-green-900 ml-2">Approve</button>
                                </form>
                                <form action="<?php echo e(route('admin.submissions.reject', $submission)); ?>" method="POST" class="inline" onsubmit="return confirm('Reject this submission?')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="rejection_reason" value="Submission does not meet requirements">
                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Reject</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="font-medium">No submissions found</p>
                                <p class="text-sm">Submissions will appear here when users complete tasks.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(isset($submissions) && $submissions->hasPages()): ?>
        <div class="px-6 py-4 border-t">
            <?php echo e($submissions->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/submissions/index.blade.php ENDPATH**/ ?>