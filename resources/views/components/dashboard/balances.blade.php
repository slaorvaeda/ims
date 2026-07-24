@props(['stats'])

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full">
    <!-- Card 1: Active Inventory Stock (Dark Slate) -->
    <div class="bg-[#1e293b] border border-slate-800/40 p-6 rounded-[2rem] flex flex-col justify-between shadow-sm relative overflow-hidden group cursor-pointer min-h-[148px] text-white" 
         @click="showStockModal = true" 
         title="Click to view detailed stock breakdown">
        
        <!-- Top Row -->
        <div class="flex items-center justify-between w-full relative z-10">
            <!-- Icon -->
            <div class="w-11 h-11 rounded-full bg-[#2e3b52]/80 border border-slate-700/30 flex items-center justify-center shadow-inner">
                <svg class="w-5.5 h-5.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <!-- Badge -->
            <span class="bg-[#FF5A36] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">+35%</span>
        </div>

        <!-- Bottom Section -->
        <div class="mt-5 text-left relative z-10">
            <p class="text-slate-400 text-xs font-semibold tracking-wide uppercase">Active Inventory Stock</p>
            <h3 id="stat-active-stock" class="text-2xl sm:text-3xl font-extrabold text-white font-sans mt-0.5 transition-all duration-300">
                {{ number_format($stats['active_stock']) }}
            </h3>
            <p class="text-slate-400 text-[11px] mt-0.5">Total physical units</p>
        </div>

        <!-- Backing graphics (Hover scale & move animation) -->
        <div class="absolute -right-6 -bottom-10 w-28 h-28 rounded-full bg-slate-950/25 pointer-events-none transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-3 group-hover:-translate-y-3"></div>
    </div>

    <!-- Card 2: Product Catalog (Light) -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[2rem] flex flex-col justify-between shadow-sm relative overflow-hidden group min-h-[148px]">
        
        <!-- Top Row -->
        <div class="flex items-center justify-between w-full relative z-10">
            <!-- Icon -->
            <div class="w-11 h-11 rounded-full bg-[#EAF2FC] dark:bg-slate-800 flex items-center justify-center">
                <svg class="w-5.5 h-5.5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
            </div>
            <!-- Badge -->
            <span class="bg-[#EAF2FC] dark:bg-blue-950/40 text-[#3B82F6] dark:text-blue-400 text-[11px] font-bold px-3 py-1 rounded-full">Catalog</span>
        </div>

        <!-- Bottom Section -->
        <div class="mt-5 text-left relative z-10">
            <p class="text-slate-400 dark:text-slate-500 text-xs font-semibold tracking-wide uppercase">Product Catalog</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white font-sans mt-0.5">
                {{ number_format($stats['total_products']) }}
            </h3>
            <p class="text-slate-400 dark:text-slate-550 text-[11px] mt-0.5">Different SKUs registered</p>
        </div>

        <!-- Backing graphics (Hover scale & move animation) -->
        <div class="absolute -right-6 -bottom-10 w-28 h-28 rounded-full bg-slate-100/70 dark:bg-slate-800/20 pointer-events-none transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-3 group-hover:-translate-y-3"></div>
    </div>

    <!-- Card 3: Total Purchases Cost (Light) -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[2rem] flex flex-col justify-between shadow-sm relative overflow-hidden group min-h-[148px]">
        
        <!-- Top Row -->
        <div class="flex items-center justify-between w-full relative z-10">
            <!-- Icon -->
            <div class="w-11 h-11 rounded-full bg-[#E6F4EA] dark:bg-slate-800 flex items-center justify-center">
                <svg class="w-5.5 h-5.5 text-[#137333]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3a4 4 0 0 0 0-8" />
                </svg>
            </div>
            <!-- Badge -->
            <span class="bg-[#E6F4EA] dark:bg-emerald-950/40 text-[#137333] dark:text-emerald-400 text-[11px] font-bold px-3 py-1 rounded-full">INR</span>
        </div>

        <!-- Bottom Section -->
        <div class="mt-5 text-left relative z-10">
            <p class="text-slate-400 dark:text-slate-500 text-xs font-semibold tracking-wide uppercase">Total Purchases Cost</p>
            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white font-sans mt-0.5">
                ₹{{ number_format($stats['total_purchase_cost'], 2) }}
            </h3>
            <p class="text-slate-400 dark:text-slate-550 text-[11px] mt-0.5">For {{ number_format($stats['total_purchase_qty']) }} total units</p>
        </div>

        <!-- Backing graphics (Hover scale & move animation) -->
        <div class="absolute -right-6 -bottom-10 w-28 h-28 rounded-full bg-slate-100/70 dark:bg-slate-800/20 pointer-events-none transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-3 group-hover:-translate-y-3"></div>
    </div>

    <!-- Card 4: Available Stock Value (Light) -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[2rem] flex flex-col justify-between shadow-sm relative overflow-hidden group min-h-[148px]">
        
        <!-- Top Row -->
        <div class="flex items-center justify-between w-full relative z-10">
            <!-- Icon -->
            <div class="w-11 h-11 rounded-full bg-[#FFF2EE] dark:bg-slate-800 flex items-center justify-center">
                <svg class="w-5.5 h-5.5 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3a4 4 0 0 0 0-8" />
                </svg>
            </div>
            <!-- Badge -->
            <span class="bg-[#FFF2EE] dark:bg-orange-950/40 text-[#FF5A36] text-[11px] font-bold px-3 py-1 rounded-full">Available</span>
        </div>

        <!-- Bottom Section -->
        <div class="mt-5 text-left relative z-10">
            <p class="text-slate-400 dark:text-slate-500 text-xs font-semibold tracking-wide uppercase">Available Stock Value</p>
            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white font-sans mt-0.5">
                ₹{{ number_format($stats['available_stock_value'], 2) }}
            </h3>
            <p class="text-slate-400 dark:text-slate-550 text-[11px] mt-0.5">For {{ number_format($stats['active_stock']) }} available units</p>
        </div>

        <!-- Backing graphics (Hover scale & move animation) -->
        <div class="absolute -right-6 -bottom-10 w-28 h-28 rounded-full bg-slate-100/70 dark:bg-slate-800/20 pointer-events-none transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-3 group-hover:-translate-y-3"></div>
    </div>
</div>
