<div class="flex items-center justify-between gap-3 p-2 bg-slate-50 dark:bg-slate-800/30 rounded-2xl transition-all duration-300" :class="sidebarMinimized ? 'flex-col items-center justify-center p-1.5' : 'p-2'">
    <div class="flex items-center gap-2.5 overflow-hidden" :class="sidebarMinimized ? 'flex-col' : ''">
        <div class="w-8 h-8 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-sm text-slate-700 dark:text-slate-300 shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div x-show="!sidebarMinimized" class="flex flex-col truncate transition-all duration-300">
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->name }}</span>
            <span class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</span>
        </div>
    </div>
    
    <!-- Logout Button -->
    <form method="POST" action="{{ route('logout') }}" :class="sidebarMinimized ? 'w-full flex justify-center' : ''">
        @csrf
        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 dark:text-slate-500 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 rounded-xl transition-all" :title="sidebarMinimized ? 'Logout' : 'Logout'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </button>
    </form>
</div>
