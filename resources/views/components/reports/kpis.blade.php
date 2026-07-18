@props(['kpis'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
    <!-- Total Inward -->
    <div class="p-6 bg-gradient-to-br from-indigo-50/60 to-white dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Inward</span>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($kpis['total_inward']) }}</h3>
            <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-semibold">Registered units</p>
        </div>
        <div class="w-12 h-12 bg-indigo-100/60 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
    </div>

    <!-- Total Dispatch -->
    <div class="p-6 bg-gradient-to-br from-rose-50/60 to-white dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Dispatch</span>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($kpis['total_dispatch']) }}</h3>
            <p class="text-[10px] text-rose-600 dark:text-rose-400 font-semibold">Shipped units</p>
        </div>
        <div class="w-12 h-12 bg-rose-100/60 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
            </svg>
        </div>
    </div>

    <!-- Active Stock Balance -->
    <div class="p-6 bg-gradient-to-br from-emerald-50/60 to-white dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Stock</span>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($kpis['active_stock']) }}</h3>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">Available in hand</p>
        </div>
        <div class="w-12 h-12 bg-emerald-100/60 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
    </div>

    <!-- Damaged Stock -->
    <div class="p-6 bg-gradient-to-br from-amber-50/60 to-white dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Damaged Stock</span>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($kpis['damaged_stock']) }}</h3>
            <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">Out of circulation</p>
        </div>
        <div class="w-12 h-12 bg-amber-100/60 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
    </div>

    <!-- Returns -->
    <div class="p-6 bg-gradient-to-br from-purple-50/60 to-white dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">RTG Returns</span>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($kpis['returns']) }}</h3>
            <p class="text-[10px] text-purple-600 dark:text-purple-400 font-semibold">Returned to good</p>
        </div>
        <div class="w-12 h-12 bg-purple-100/60 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3.25" />
            </svg>
        </div>
    </div>
</div>
