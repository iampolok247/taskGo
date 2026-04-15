<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>@yield('title', 'Leader Panel') - TaskGo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
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

        /* Menu item hover */
        .menu-item {
            transition: all 0.2s ease;
        }
        .menu-item:hover, .menu-item.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900 min-h-screen">
    <div id="app" class="min-h-screen">
        <!-- Mobile Header -->
        <header class="fixed top-0 left-0 right-0 bg-gradient-to-r from-emerald-600 to-green-600 text-white z-40 safe-area-top">
            <div class="flex items-center justify-between px-4 h-14">
                <!-- Menu Button -->
                <button onclick="toggleDrawer()" class="p-2 -ml-2 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Title -->
                <h1 class="text-lg font-semibold">@yield('title', 'Leader Panel')</h1>

                <!-- Notification -->
                <a href="{{ route('agent.profile.notifications') }}" class="p-2 -mr-2 rounded-lg hover:bg-white/10 transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </a>
            </div>
        </header>

        <!-- Drawer Overlay -->
        <div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-black/50 z-50 opacity-0 pointer-events-none" onclick="toggleDrawer()"></div>

        <!-- Sidebar Drawer -->
        <aside id="drawer-panel" class="drawer-panel fixed top-0 left-0 bottom-0 w-72 bg-white z-50 transform -translate-x-full shadow-2xl overflow-hidden flex flex-col">
            <!-- Drawer Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white p-6 safe-area-top flex-shrink-0">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-4">
                    <img src="https://i.ibb.co.com/wNSGPL3W/taskgo-logo.png" alt="TaskGo" class="h-10 w-auto">
                    <span class="text-xl font-bold">TaskGo Leader</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr(auth()->guard('agent')->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-lg truncate">{{ auth()->guard('agent')->user()->name }}</h3>
                        <p class="text-white/70 text-sm truncate">{{ auth()->guard('agent')->user()->email }}</p>
                    </div>
                </div>
                <!-- Wallet Balance -->
                <div class="mt-4 bg-white/10 rounded-xl p-3">
                    <p class="text-white/70 text-xs">Available Balance</p>
                    <p class="text-2xl font-bold">{{ format_currency(auth()->guard('agent')->user()->wallet->main_balance ?? 0) }}</p>
                </div>
            </div>

            <!-- Drawer Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto hide-scrollbar">
                <a href="{{ route('agent.dashboard') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('agent.users.index') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="font-medium">Freelancers</span>
                </a>

                <a href="{{ route('agent.commissions.index') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.commissions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Commissions</span>
                </a>

                <div class="border-t border-gray-100 my-3"></div>

                <a href="{{ route('agent.deposits.index') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.deposits.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="font-medium">Deposit</span>
                </a>

                <a href="{{ route('agent.withdrawals.index') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.withdrawals.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium">Withdraw</span>
                </a>

                <div class="border-t border-gray-100 my-3"></div>

                <a href="{{ route('agent.profile.index') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.profile.index') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-medium">Profile</span>
                </a>

                <a href="{{ route('agent.profile.password') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.profile.password') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="font-medium">Change Password</span>
                </a>

                <a href="{{ route('agent.profile.notifications') }}" class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 {{ request()->routeIs('agent.profile.notifications') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="font-medium">Notifications</span>
                </a>
            </nav>

            <!-- Download App Button -->
            <div class="px-4 py-3">
                <a href="{{ route('download.app') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white rounded-xl font-medium hover:bg-green-600 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.523 2H6.477C5.1 2 4 3.1 4 4.477v15.046C4 20.9 5.1 22 6.477 22h11.046C18.9 22 20 20.9 20 19.523V4.477C20 3.1 18.9 2 17.523 2zM12 17.5l-4-4h2.5V9h3v4.5H16l-4 4z"/>
                    </svg>
                    Download App
                </a>
            </div>

            <!-- Logout Button -->
            <div class="p-4 bg-white border-t border-gray-100 safe-area-bottom flex-shrink-0">
                <form method="POST" action="{{ route('agent.logout') }}">
                    @csrf
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
            @yield('content')
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 left-4 right-4 z-[60] flex flex-col gap-2 pointer-events-none">
        @if(session('success'))
            <div class="toast-enter bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 pointer-events-auto">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-enter bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 pointer-events-auto">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
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
                const app = document.getElementById('app');
                if (app.classList.contains('drawer-open')) {
                    toggleDrawer();
                }
            }
        });

        // Auto-hide toasts
        setTimeout(() => {
            document.querySelectorAll('.toast-enter').forEach(toast => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(100%)';
                setTimeout(() => toast.remove(), 300);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
