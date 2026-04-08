<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title><?php echo $__env->yieldContent('title', 'Task Go'); ?> - Complete Tasks. Earn Rewards.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Safe area for notched devices */
        .safe-area-top { padding-top: env(safe-area-inset-top); }
        .safe-area-bottom { padding-bottom: env(safe-area-inset-bottom); }

        /* Drawer animation */
        .drawer-overlay {
            transition: opacity 0.3s ease;
        }
        .drawer-panel {
            transition: transform 0.3s ease;
        }
        .drawer-open .drawer-overlay {
            opacity: 1;
            pointer-events: auto;
        }
        .drawer-open .drawer-panel {
            transform: translateX(0);
        }

        /* Card hover effect */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:active {
            transform: scale(0.98);
        }

        /* Menu item hover */
        .menu-item {
            transition: all 0.2s ease;
        }
        .menu-item:hover, .menu-item.active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }
        .menu-item:hover svg, .menu-item.active svg {
            color: white;
        }

        /* Toast notifications */
        .toast-enter {
            animation: toast-in 0.3s ease-out;
        }
        @keyframes toast-in {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900 min-h-screen">
    <div id="app" class="min-h-screen">
        <!-- Mobile Header -->
        <header class="fixed top-0 left-0 right-0 bg-gradient-to-r from-primary-600 to-purple-600 text-white z-40 safe-area-top">
            <div class="flex items-center justify-between px-4 h-14">
                <!-- Menu Button -->
                <button onclick="toggleDrawer()" class="p-2 -ml-2 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Title -->
                <h1 class="text-lg font-semibold"><?php echo $__env->yieldContent('title', 'Task Go'); ?></h1>

                <!-- Notification -->
                <a href="<?php echo e(route('user.profile.notifications')); ?>" class="p-2 -mr-2 rounded-lg hover:bg-white/10 transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <?php
                        $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                    ?>
                    <?php if($unreadCount > 0): ?>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    <?php endif; ?>
                </a>
            </div>
        </header>

        <!-- Drawer Overlay -->
        <div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-black/50 z-50 opacity-0 pointer-events-none" onclick="toggleDrawer()"></div>

        <!-- Sidebar Drawer -->
        <aside id="drawer-panel" class="drawer-panel fixed top-0 left-0 bottom-0 w-72 bg-white z-50 transform -translate-x-full shadow-2xl overflow-hidden flex flex-col">
            <!-- Drawer Header -->
            <div class="bg-gradient-to-r from-primary-600 to-purple-600 text-white p-6 safe-area-top flex-shrink-0">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-4">
                    <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-10 w-auto">
                    <span class="text-xl font-bold">TaskGo</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-xl font-bold">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-lg truncate"><?php echo e(auth()->user()->name); ?></h3>
                        <p class="text-white/70 text-sm truncate"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
                <!-- Wallet Balance -->
                <div class="mt-4 bg-white/10 rounded-xl p-3">
                    <p class="text-white/70 text-xs">Wallet Balance</p>
                    <p class="text-2xl font-bold">৳<?php echo e(number_format(auth()->user()->wallet->balance ?? 0, 2)); ?></p>
                </div>
            </div>

            <!-- Drawer Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto hide-scrollbar">
                <a href="<?php echo e(route('user.dashboard')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Home</span>
                </a>

                <a href="<?php echo e(route('user.tasks.index')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.tasks.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="font-medium">Tasks</span>
                </a>

                <a href="<?php echo e(route('user.wallet.index')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.wallet.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="font-medium">Wallet</span>
                </a>

                <div class="border-t border-gray-100 my-3"></div>

                <a href="<?php echo e(route('user.deposits.index')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.deposits.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="font-medium">Deposit</span>
                </a>

                <a href="<?php echo e(route('user.withdrawals.index')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.withdrawals.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium">Withdraw</span>
                </a>

                <a href="<?php echo e(route('user.referrals.index')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.referrals.*') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium">Referrals</span>
                </a>

                <div class="border-t border-gray-100 my-3"></div>

                <a href="<?php echo e(route('user.profile.index')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.profile.index') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-medium">Profile</span>
                </a>

                <a href="<?php echo e(route('user.profile.password')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.profile.password') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="font-medium">Change Password</span>
                </a>

                <a href="<?php echo e(route('user.profile.notifications')); ?>" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 <?php echo e(request()->routeIs('user.profile.notifications') ? 'active' : ''); ?>">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="font-medium">Notifications</span>
                    <?php if($unreadCount > 0): ?>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e($unreadCount); ?></span>
                    <?php endif; ?>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 bg-white border-t border-gray-100 safe-area-bottom flex-shrink-0">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-600 rounded-xl font-medium hover:bg-red-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="pt-14 min-h-screen">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 left-4 right-4 z-[60] flex flex-col gap-2 pointer-events-none">
        <?php if(session('success')): ?>
            <div class="toast-enter bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 pointer-events-auto">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="toast-enter bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 pointer-events-auto">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Drawer toggle
        function toggleDrawer() {
            const app = document.getElementById('app');
            app.classList.toggle('drawer-open');
            document.body.style.overflow = app.classList.contains('drawer-open') ? 'hidden' : '';
        }

        // Close drawer on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('app').classList.remove('drawer-open');
                document.body.style.overflow = '';
            }
        });

        // Auto-hide toasts after 4 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('#toast-container > div');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            });
        });

        // Swipe to close drawer
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.getElementById('drawer-panel').addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        document.getElementById('drawer-panel').addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) {
                document.getElementById('app').classList.remove('drawer-open');
                document.body.style.overflow = '';
            }
        }, false);
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/taskisfk/public_html/resources/views/layouts/user.blade.php ENDPATH**/ ?>