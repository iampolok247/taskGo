<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="TaskGo">
    <meta name="apple-mobile-web-app-title" content="TaskGo">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- PWA Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-16x16.png">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    
    <!-- Splash Screen for iOS -->
    <link rel="apple-touch-startup-image" href="/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1242x2208.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">

    <title>@yield('title', 'Task Go') - Complete Tasks. Earn Rewards.</title>

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

    <!-- Heroicons -->
    <script src="https://unpkg.com/@heroicons/vue@2.0.18/dist/cjs/index.js" defer></script>

    <style>
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Safe area for notched devices */
        .safe-area-top {
            padding-top: env(safe-area-inset-top);
        }
        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Smooth animations */
        .transition-app {
            transition: all 0.2s ease-in-out;
        }

        /* Pull to refresh indicator */
        .pull-indicator {
            transition: transform 0.2s ease-out;
        }

        /* Bottom navigation active indicator */
        .nav-item.active {
            color: #6366f1;
        }
        .nav-item.active svg {
            transform: scale(1.1);
        }

        /* Card hover effect */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:active {
            transform: scale(0.98);
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* PWA Splash Screen */
        .pwa-splash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .pwa-splash.hide {
            opacity: 0;
            visibility: hidden;
        }
        .pwa-splash-logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: pulse-logo 2s infinite;
        }
        @keyframes pulse-logo {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .pwa-splash-text {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-top: 1.5rem;
            letter-spacing: -0.5px;
        }
        .pwa-splash-tagline {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        .pwa-splash-loader {
            margin-top: 3rem;
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Toast notifications */
        .toast-enter {
            animation: toast-in 0.3s ease-out;
        }
        @keyframes toast-in {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen">
    <!-- PWA Splash Screen -->
    <div id="pwa-splash" class="pwa-splash">
        <div class="pwa-splash-logo">
            <svg class="w-16 h-16 text-primary-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <div class="pwa-splash-text">TaskGo</div>
        <div class="pwa-splash-tagline">Complete Tasks. Earn Rewards.</div>
        <div class="pwa-splash-loader"></div>
    </div>

    @yield('body')

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-20 left-4 right-4 z-50 flex flex-col gap-2 pointer-events-none">
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
        // Hide PWA Splash Screen
        window.addEventListener('load', function() {
            const splash = document.getElementById('pwa-splash');
            if (splash) {
                // Show splash for minimum 1.5 seconds for smooth experience
                setTimeout(function() {
                    splash.classList.add('hide');
                    // Remove from DOM after animation
                    setTimeout(function() {
                        splash.remove();
                    }, 500);
                }, 1500);
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

        // Prevent double-tap zoom on iOS
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registered: ', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
