<?php $__env->startSection('title', 'Task: ' . $task->title); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.tasks.index')); ?>" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?php echo e($task->title); ?></h1>
                <p class="text-gray-500"><?php echo e(ucfirst($task->category)); ?> Task</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.tasks.edit', $task)); ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="<?php echo e(route('admin.tasks.toggle-status', $task)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-4 py-2 <?php echo e($task->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white font-medium rounded-xl transition-all">
                    <?php echo e($task->status === 'active' ? 'Deactivate' : 'Activate'); ?>

                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Reward</p>
            <p class="text-xl font-bold text-green-600">৳<?php echo e(number_format($task->reward, 2)); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Submissions</p>
            <p class="text-xl font-bold text-gray-900"><?php echo e($task->submissions_count); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Approved</p>
            <p class="text-xl font-bold text-green-600"><?php echo e($task->approved_submissions_count); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Pending</p>
            <p class="text-xl font-bold text-yellow-600"><?php echo e($task->pending_submissions_count); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Status</p>
            <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full <?php echo e($task->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'); ?>">
                <?php echo e(ucfirst($task->status)); ?>

            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Task Details</h3>
                
                <?php if($task->thumbnail): ?>
                <div class="mb-4">
                    <img src="<?php echo e(asset('storage/' . $task->thumbnail)); ?>" alt="<?php echo e($task->title); ?>" class="w-full h-48 object-cover rounded-xl">
                </div>
                <?php endif; ?>

                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Description</h4>
                        <p class="text-gray-900"><?php echo e($task->description); ?></p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Instructions</h4>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            <?php echo nl2br(e($task->instructions)); ?>

                        </div>
                    </div>

                    <?php if($task->task_url): ?>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Task URL</h4>
                        <a href="<?php echo e($task->task_url); ?>" target="_blank" class="text-primary-600 hover:underline break-all"><?php echo e($task->task_url); ?></a>
                    </div>
                    <?php endif; ?>

                    <?php if($task->proof_instructions): ?>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Proof Instructions</h4>
                        <p class="text-gray-700"><?php echo e($task->proof_instructions); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Submissions -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Recent Submissions</h3>
                    <a href="<?php echo e(route('admin.submissions.index', ['task_id' => $task->id])); ?>" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Submitted</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php $__empty_1 = true; $__currentLoopData = $recentSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-xs font-bold"><?php echo e(strtoupper(substr($submission->user->name ?? 'U', 0, 1))); ?></span>
                                        </div>
                                        <span class="font-medium"><?php echo e($submission->user->name ?? 'User'); ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($submission->status === 'approved' ? 'bg-green-100 text-green-700' : ($submission->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
                                        <?php echo e(ucfirst($submission->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($submission->created_at->diffForHumans()); ?></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="<?php echo e(route('admin.submissions.show', $submission)); ?>" class="text-primary-600 hover:text-primary-700 text-sm">Review</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">No submissions yet</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Task Info</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Category</span>
                        <span class="font-medium capitalize"><?php echo e($task->category); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Difficulty</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($task->difficulty === 'easy' ? 'bg-green-100 text-green-700' : ($task->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
                            <?php echo e(ucfirst($task->difficulty)); ?>

                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Est. Time</span>
                        <span class="font-medium"><?php echo e($task->estimated_time); ?> mins</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Proof Type</span>
                        <span class="font-medium capitalize"><?php echo e($task->proof_type); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Daily Limit</span>
                        <span class="font-medium"><?php echo e($task->daily_limit ?: 'Unlimited'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Total Limit</span>
                        <span class="font-medium"><?php echo e($task->total_limit ?: 'Unlimited'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Priority</span>
                        <span class="font-medium"><?php echo e($task->priority); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium"><?php echo e($task->created_at->format('M d, Y')); ?></span>
                    </div>
                    <?php if($task->expires_at): ?>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Expires</span>
                        <span class="font-medium <?php echo e($task->expires_at->isPast() ? 'text-red-600' : ''); ?>"><?php echo e($task->expires_at->format('M d, Y')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4">Earnings Paid</h3>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                    <p class="text-sm opacity-90">Total Paid to Users</p>
                    <p class="text-2xl font-bold">৳<?php echo e(number_format($task->approved_submissions_count * $task->reward, 2)); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/admin/tasks/show.blade.php ENDPATH**/ ?>