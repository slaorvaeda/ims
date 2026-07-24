<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>IMS - The Ultimate Solution for Physical Stock</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('lofg.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Tailwind CSS CDN Fallback for robust rendering -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @endif
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                            heading: ['Outfit', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        
        <!-- Custom Styles -->
        <style>
            [x-cloak] {
                display: none !important;
            }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .font-heading {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div x-data="{ sidebarMinimized: localStorage.getItem('sidebar_minimized') === 'true', toggleSidebar() { this.sidebarMinimized = !this.sidebarMinimized; localStorage.setItem('sidebar_minimized', this.sidebarMinimized); } }" class="min-h-screen flex flex-col md:flex-row w-full">
            <!-- Left Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden w-full md:pl-20 transition-all duration-300">
                <!-- Page Heading (Custom Floating Pill Header) -->
                @isset($header)
                    <header class="sticky top-0 z-20 bg-transparent py-4 px-6 sm:px-8 max-w-full w-full mx-auto">
                        <div class="flex items-center justify-between gap-4 w-full">
                            
                            <!-- Left Section: Brand & Connected Active Tab Pill -->
                            <div class="flex items-center gap-3 shrink-0">
                                @php
                                    $routeName = Route::currentRouteName();
                                    $cleanTitle = 'Overview';
                                    if ($routeName) {
                                        if (str_contains($routeName, 'products')) {
                                            $cleanTitle = 'Products';
                                        } elseif (str_contains($routeName, 'purchases')) {
                                            $cleanTitle = 'Purchases';
                                        } elseif (str_contains($routeName, 'sales')) {
                                            $cleanTitle = 'Sales';
                                        } elseif (str_contains($routeName, 'analytics')) {
                                            $cleanTitle = 'Analytics';
                                        } elseif (str_contains($routeName, 'reports')) {
                                            $cleanTitle = 'Reports';
                                        } elseif (str_contains($routeName, 'users')) {
                                            $cleanTitle = 'Users';
                                        } elseif (str_contains($routeName, 'barcodes')) {
                                            $cleanTitle = 'Barcodes';
                                        } elseif (str_contains($routeName, 'operators')) {
                                            $cleanTitle = 'Operators';
                                        } elseif (str_contains($routeName, 'profile')) {
                                            $cleanTitle = 'Profile';
                                        }
                                    }
                                @endphp
                                <!-- Unified Pill Block -->
                                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-sm rounded-full p-1.5 px-4 flex items-center gap-3.5">
                                    <!-- Brand orange circle with flame/box icon -->
                                    <div class="w-8.5 h-8.5 rounded-full bg-[#FF5A36] text-white flex items-center justify-center shadow-lg shadow-[#FF5A36]/15 shrink-0">
                                        <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        </svg>
                                    </div>
                                    <!-- Active Page Title -->
                                    <span class="text-slate-800 dark:text-white font-extrabold text-base tracking-tight font-sans">
                                        {{ __($cleanTitle) }}
                                    </span>
                                </div>

                                <!-- Inactive Tabs (Separate Pills) -->
                                <div class="hidden lg:flex items-center gap-2">
                                    <a href="{{ route('users.index') }}" class="bg-white/80 dark:bg-slate-900/80 border border-slate-100 dark:border-slate-800/80 shadow-sm rounded-full px-4 py-2 text-[13px] font-extrabold text-slate-500 dark:text-slate-400 hover:text-slate-700 hover:bg-white transition-all shrink-0">Account</a>
                                </div>
                            </div>

                            <!-- Center/Search bar -->
                            <div class="hidden md:block relative max-w-xs xl:max-w-md w-full mx-auto">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" placeholder="Start searching here..." class="w-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-sm rounded-full py-2.5 pl-10 pr-4 text-xs focus:ring-1 focus:ring-[#FF5A36]/30 text-slate-600 dark:text-slate-200 font-medium placeholder-slate-400 transition-all focus:outline-none" />
                            </div>

                            <!-- Right Section (Actions) -->
                            <div class="flex items-center gap-3 shrink-0">
                                <!-- Notification Pill Wrapper -->
                                <div class="border border-slate-100 dark:border-slate-800/80 rounded-full p-1 pr-3 flex items-center gap-2 bg-white dark:bg-slate-900 shadow-sm">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <!-- Dynamic Current Date display -->
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        {{ now()->format('D, d M') }}
                                    </span>
                                </div>

                                <!-- User Profile Initials Avatar (Links to Profile settings) -->
                                <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-md border-2 border-white hover:scale-105 transition-transform" title="{{ Auth::user()->name }}">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </a>
                            </div>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 px-6 sm:px-8 pt-2 pb-8 max-w-full w-full mx-auto">
                    <!-- Page Sub-header Action panel -->
                    @if (Route::currentRouteName() !== 'dashboard')
                        @isset($header)
                            <div class="mb-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-sm rounded-3xl p-6 flex items-center justify-between w-full">
                                {{ $header }}
                            </div>
                        @endisset
                    @endif
                    <!-- Flash Message Alert -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 text-sm animate-pulse">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/50 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
        <x-dashboard.ai-copilot />
    </body>
</html>
