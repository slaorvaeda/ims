<!-- Dashboard Link -->
<a href="{{ route('dashboard') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Dashboard' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
    </svg>
    <span x-show="!sidebarMinimized">Dashboard</span>
</a>

<!-- Product Master Link -->
<a href="{{ route('products.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('products.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Product Master' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
    </svg>
    <span x-show="!sidebarMinimized">Product Master</span>
</a>

<!-- Purchase Master Link -->
<a href="{{ route('purchases.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('purchases.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Purchase Master' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span x-show="!sidebarMinimized">Purchase Master</span>
</a>

<!-- Inward ItemCode Master Link -->
<a href="{{ route('inward-item-codes.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('inward-item-codes.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Inward Serial Codes' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
    </svg>
    <span x-show="!sidebarMinimized">Inward Serial Codes</span>
</a>

<!-- Sale Master Link -->
<a href="{{ route('sales.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('sales.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Sale Master' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>
    <span x-show="!sidebarMinimized">Sale Master</span>
</a>

<!-- Dispatch ItemCode Master Link -->
<a href="{{ route('dispatch-item-codes.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('dispatch-item-codes.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Dispatch Serial Codes' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
    </svg>
    <span x-show="!sidebarMinimized">Dispatch Serial Codes</span>
</a>

<!-- User Master Link -->
<a href="{{ route('users.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'User Master' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    <span x-show="!sidebarMinimized">User Master</span>
</a>

<!-- Barcode Generator Link -->
<a href="{{ route('barcodes.index') }}" 
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('barcodes.*') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-md shadow-slate-900/10' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/40' }}"
   :class="sidebarMinimized ? 'justify-center px-2' : ''"
   x-bind:title="sidebarMinimized ? 'Barcode Generator' : ''">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5v14M6 5v14M9 5v14M12 5v14M14 5v14M17 5v14M21 5v14" />
    </svg>
    <span x-show="!sidebarMinimized">Barcode Generator</span>
</a>
