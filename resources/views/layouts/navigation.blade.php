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
    <aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800/80 justify-between transition-all duration-300 z-30" :class="sidebarMinimized ? 'md:w-20' : 'md:w-64'">
        <div class="flex flex-col">
            <!-- Brand / Logo Area -->
            <div class="flex p-5 border-b border-slate-100 dark:border-slate-800/50 transition-all duration-300" :class="sidebarMinimized ? 'flex-col items-center gap-3 px-2 py-4' : 'items-center justify-between gap-3'">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-slate-950 dark:bg-white rounded-xl flex items-center justify-center shadow-md shrink-0">
                        <svg class="w-4 h-4 text-white dark:text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span x-show="!sidebarMinimized" class="text-lg font-bold text-slate-900 dark:text-white tracking-tight font-heading">IMS</span>
                </a>
                
                <!-- Collapse Button: Visible when sidebar is EXPANDED -->
                <button 
                    x-show="!sidebarMinimized" 
                    x-cloak
                    @click="toggleSidebar()" 
                    title="Collapse sidebar"
                    class="hidden md:flex p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 focus:outline-none transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Expand Button: Visible when sidebar is MINIMIZED -->
                <button 
                    x-show="sidebarMinimized" 
                    x-cloak
                    @click="toggleSidebar()" 
                    title="Expand sidebar"
                    class="hidden md:flex p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 focus:outline-none transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Links list -->
            <nav class="p-4 space-y-1.5 overflow-y-auto" :class="sidebarMinimized ? 'px-2' : 'p-4'">
                @include('layouts.navigation-links')
            </nav>
        </div>

        <!-- User Profile Area -->
        <div class="border-t border-slate-100 dark:border-slate-800/50" :class="sidebarMinimized ? 'p-2' : 'p-4'">
            @include('layouts.navigation-profile')
        </div>
    </aside>
</div>
