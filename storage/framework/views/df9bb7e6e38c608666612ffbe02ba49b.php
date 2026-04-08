<?php $__env->startSection('body'); ?>
<div class="min-h-screen flex">
    <!-- Sidebar (Desktop) -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-gray-900">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-800">
            <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-10 w-auto">
            <div>
                <h1 class="text-white font-semibold">Task Go</h1>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</p>
            </div>

            <a href="<?php echo e(route('admin.users.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="text-sm font-medium">Users</span>
            </a>

            <a href="<?php echo e(route('admin.agents.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.agents.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-sm font-medium">Agents</span>
            </a>

            <a href="<?php echo e(route('admin.tasks.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.tasks.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="text-sm font-medium">Tasks</span>
            </a>

            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Approvals</p>
            </div>

            <a href="<?php echo e(route('admin.submissions.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.submissions.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Task Submissions</span>
                <?php $pendingSubs = \App\Models\TaskSubmission::pending()->count(); ?>
                <?php if($pendingSubs > 0): ?>
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e($pendingSubs); ?></span>
                <?php endif; ?>
            </a>

            <a href="<?php echo e(route('admin.deposits.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.deposits.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium">Deposits</span>
                <?php $pendingDeps = \App\Models\Deposit::pending()->count(); ?>
                <?php if($pendingDeps > 0): ?>
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e($pendingDeps); ?></span>
                <?php endif; ?>
            </a>

            <a href="<?php echo e(route('admin.withdrawals.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.withdrawals.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-7-7 7-7m0 14l7-7-7-7"></path>
                </svg>
                <span class="text-sm font-medium">Withdrawals</span>
                <?php $pendingWith = \App\Models\Withdrawal::pending()->count(); ?>
                <?php if($pendingWith > 0): ?>
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e($pendingWith); ?></span>
                <?php endif; ?>
            </a>

            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">System</p>
            </div>

            <a href="<?php echo e(route('admin.announcements.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.announcements.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <span class="text-sm font-medium">Announcements</span>
            </a>

            <a href="<?php echo e(route('admin.settings.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.settings.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'); ?> transition-app">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-sm font-medium">Settings</span>
            </a>
        </nav>

        <!-- User Menu -->
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3">
                <img src="<?php echo e(auth()->guard('admin')->user()->profile_photo_url); ?>" alt="" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->guard('admin')->user()->name); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo e(ucfirst(auth()->guard('admin')->user()->role)); ?></p>
                </div>
                <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="p-2 text-gray-400 hover:text-white transition-app">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="lg:hidden fixed top-0 left-0 right-0 bg-gray-900 text-white z-50 safe-area-top">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">
                <button onclick="toggleMobileMenu()" class="p-2 -ml-2 rounded-lg hover:bg-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <span class="font-semibold"><?php echo $__env->yieldContent('header-title', 'Admin Panel'); ?></span>
            </div>
            <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="p-2 rounded-lg hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="lg:hidden fixed inset-0 bg-black/50 z-50 hidden" onclick="toggleMobileMenu()"></div>

    <!-- Mobile Sidebar -->
    <div id="mobile-menu" class="lg:hidden fixed inset-y-0 left-0 w-64 bg-gray-900 z-50 transform -translate-x-full transition-transform duration-300">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-800">
            <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-10 w-auto">
            <div>
                <h1 class="text-white font-semibold">Task Go</h1>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
        </div>

        <nav class="px-3 py-4 space-y-1 overflow-y-auto max-h-[calc(100vh-100px)]">
            <!-- Same navigation items as desktop -->
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="text-sm font-medium">Users</span>
            </a>
            <a href="<?php echo e(route('admin.agents.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.agents.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-sm font-medium">Agents</span>
            </a>
            <a href="<?php echo e(route('admin.tasks.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.tasks.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="text-sm font-medium">Tasks</span>
            </a>
            <a href="<?php echo e(route('admin.submissions.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.submissions.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Submissions</span>
            </a>
            <a href="<?php echo e(route('admin.deposits.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.deposits.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium">Deposits</span>
            </a>
            <a href="<?php echo e(route('admin.withdrawals.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.withdrawals.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-7-7 7-7m0 14l7-7-7-7"></path>
                </svg>
                <span class="text-sm font-medium">Withdrawals</span>
            </a>
            <a href="<?php echo e(route('admin.announcements.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.announcements.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <span class="text-sm font-medium">Announcements</span>
            </a>
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg <?php echo e(request()->routeIs('admin.settings.*') ? 'bg-gray-800 text-white' : 'text-gray-400'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-sm font-medium">Settings</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="flex-1 lg:ml-64">
        <div class="pt-14 lg:pt-0 min-h-screen">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
</div>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        
        menu.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/taskisfk/public_html/resources/views/layouts/admin.blade.php ENDPATH**/ ?>