<?php $__env->startSection('body'); ?>
<div class="min-h-screen flex flex-col pb-16 safe-area-bottom bg-gray-50">
    <!-- Top Header -->
    <header class="bg-emerald-600 text-white sticky top-0 z-40 safe-area-top">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <?php if(isset($showBack) && $showBack): ?>
                    <a href="<?php echo e(url()->previous()); ?>" class="p-2 -ml-2 rounded-full hover:bg-emerald-700 transition-app">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-8 w-auto">
                <?php endif; ?>
                <div>
                    <h1 class="font-semibold"><?php echo $__env->yieldContent('header-title', 'Agent Panel'); ?></h1>
                    <?php if (! empty(trim($__env->yieldContent('header-subtitle')))): ?>
                        <p class="text-xs text-emerald-100"><?php echo $__env->yieldContent('header-subtitle'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('agent.profile.notifications')); ?>" class="p-2 rounded-full hover:bg-emerald-700 transition-app relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto hide-scrollbar">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-50 safe-area-bottom">
        <div class="flex items-center justify-around py-2">
            <a href="<?php echo e(route('agent.dashboard')); ?>" class="nav-item flex flex-col items-center py-1 px-4 <?php echo e(request()->routeIs('agent.dashboard') ? 'text-emerald-600' : 'text-gray-500'); ?>">
                <svg class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-[10px] font-medium mt-1">Dashboard</span>
            </a>
            <a href="<?php echo e(route('agent.users.index')); ?>" class="nav-item flex flex-col items-center py-1 px-4 <?php echo e(request()->routeIs('agent.users.*') ? 'text-emerald-600' : 'text-gray-500'); ?>">
                <svg class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="text-[10px] font-medium mt-1">Users</span>
            </a>
            <a href="<?php echo e(route('agent.commissions.index')); ?>" class="nav-item flex flex-col items-center py-1 px-4 <?php echo e(request()->routeIs('agent.commissions.*') ? 'text-emerald-600' : 'text-gray-500'); ?>">
                <svg class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-[10px] font-medium mt-1">Earnings</span>
            </a>
            <a href="<?php echo e(route('agent.profile.index')); ?>" class="nav-item flex flex-col items-center py-1 px-4 <?php echo e(request()->routeIs('agent.profile.*') ? 'text-emerald-600' : 'text-gray-500'); ?>">
                <svg class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-[10px] font-medium mt-1">Profile</span>
            </a>
        </div>
    </nav>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/layouts/agent.blade.php ENDPATH**/ ?>