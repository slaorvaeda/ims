<div x-data="{ open: false }">
    <!-- Mobile Top Navigation Header -->
    <header class="md:hidden flex items-center justify-between p-4 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800/50 sticky top-0 z-20 transition-colors duration-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-slate-950 dark:bg-white rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-4 h-4 text-white dark:text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-base font-bold text-slate-900 dark:text-white tracking-tight font-heading">IMS</span>
        </a>
        
        <!-- Hamburger Button -->
        <button @click="open = true" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 focus:outline-none transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- Mobile Drawer Overlay & Content -->
    <div x-show="open" class="fixed inset-0 z-50 md:hidden" style="display: none;">
        <!-- Backdrop Backdrop overlay -->
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             @click="open = false" 
             class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm"></div>

        <!-- Sidebar Content Panel -->
        <div x-show="open" 
             x-transition:enter="transition ease-in-out duration-200 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-200 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="relative flex flex-col w-72 max-w-[80vw] h-screen bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 transition-colors duration-200">
            
            <!-- Close Button Area -->
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800/50">
                <span class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Navigation Menu</span>
                <button @click="open = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Drawer Links list -->
            <div class="flex-1 overflow-y-auto p-4 space-y-1.5">
                @include('layouts.navigation-links')
            </div>

            <!-- Bottom profile info for drawer -->
            <div class="p-4 border-t border-slate-100 dark:border-slate-800/50">
                @include('layouts.navigation-profile')
            </div>
        </div>
    </div>


    <!-- Desktop Persistent Left Sidebar -->
    <aside x-data="{ isHovered: false }" 
           @mouseenter="isHovered = true" 
           @mouseleave="isHovered = false"
           class="hidden md:flex md:flex-col md:fixed md:inset-y-0 left-3 w-16 pt-3.5 pb-8 items-center justify-between z-30 pointer-events-auto transition-all duration-300"
           :class="isHovered ? 'w-56' : 'w-16'">
        
        <!-- Top Capsule (Main Navigation & Admin) -->
        <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur border border-slate-100 dark:border-slate-800/80 shadow-sm py-4 px-2 flex flex-col items-center gap-3.5 pointer-events-auto shrink-0 transition-all duration-300 rounded-[28px]"
             :class="isHovered ? 'w-52 px-3' : 'w-14 px-2'">
            <!-- Home (Dashboard) -->
            <a href="{{ route('dashboard') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Dashboard">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('dashboard') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Dashboard</span>
            </a>

            <!-- Analytics -->
            <a href="{{ route('analytics.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('analytics.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Analytics">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('analytics.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Analytics</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('reports.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Reports">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('reports.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Reports</span>
            </a>

            <!-- Operators -->
            <a href="{{ route('operators.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('operators.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Operators Settings">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('operators.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Operators</span>
            </a>

            <!-- Stores -->
            <a href="{{ route('stores.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('stores.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Store Configurations">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('stores.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Stores</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <!-- User Master -->
            <a href="{{ route('users.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="User Master">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('users.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">User Master</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('barcodes.view'))
            <!-- Barcode Generator -->
            <a href="{{ route('barcodes.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('barcodes.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Barcode Generator">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5v14M6 5v14M9 5v14M12 5v14M14 5v14M17 5v14M21 5v14" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('barcodes.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Barcodes</span>
            </a>
            @endif
        </div>

        <!-- Middle Capsule (Inventory & Masters Operations) -->
        <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur border border-slate-100 dark:border-slate-800/80 shadow-sm py-4 px-2 flex flex-col items-center gap-3.5 pointer-events-auto shrink-0 transition-all duration-300 rounded-[28px]"
             :class="isHovered ? 'w-52 px-3' : 'w-14 px-2'">
            @if(auth()->user()->hasPermission('products.view'))
            <!-- Product Master -->
            <a href="{{ route('products.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Product Master">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('products.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Products</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('purchases.view'))
            <!-- Purchase Master -->
            <a href="{{ route('purchases.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('purchases.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Purchase Master">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3a4 4 0 0 0 0-8" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('purchases.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Purchases</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('sales.view'))
            <!-- Sale Master -->
            <a href="{{ route('sales.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('sales.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Sale Master">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('sales.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Sales</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('inward_item_codes.view'))
            <!-- Inward Serial Codes -->
            <a href="{{ route('inward-item-codes.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('inward-item-codes.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Inward Serial Codes">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('inward-item-codes.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Inward Codes</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('dispatch_item_codes.view'))
            <!-- Dispatch Serial Codes -->
            <a href="{{ route('dispatch-item-codes.index') }}" 
               class="h-10 rounded-full flex items-center transition-all duration-200 {{ request()->routeIs('dispatch-item-codes.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60' }}" 
               :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
               title="Dispatch Serial Codes">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                </svg>
                <span x-show="isHovered" x-cloak class="text-xs font-bold whitespace-nowrap overflow-hidden transition-opacity duration-300 {{ request()->routeIs('dispatch-item-codes.*') ? 'text-white dark:text-slate-950' : 'text-slate-700 dark:text-slate-200' }}">Dispatch Codes</span>
            </a>
            @endif
        </div>

        <!-- Bottom Capsule (Exit) -->
        <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur border border-slate-100 dark:border-slate-800/80 shadow-sm py-4 px-2 flex flex-col items-center gap-3.5 pointer-events-auto shrink-0 transition-all duration-300 rounded-[28px]"
             :class="isHovered ? 'w-52 px-3' : 'w-14 px-2'">
            <!-- Logout action -->
            <form method="POST" action="{{ route('logout') }}" class="inline w-full flex justify-center">
                @csrf
                <button type="submit" 
                        class="h-10 rounded-full flex items-center transition-all duration-200 bg-slate-50 hover:bg-slate-100 text-[#FF5A36] dark:bg-slate-800 dark:hover:bg-slate-700/60 focus:outline-none" 
                        :class="isHovered ? 'w-full px-3.5 gap-3' : 'w-10 justify-center'"
                        title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="isHovered" x-cloak class="text-xs font-bold text-rose-500 whitespace-nowrap overflow-hidden transition-opacity duration-300">Logout</span>
                </button>
            </form>
        </div>

    </aside>
</div>
