<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Inventory Overview') }}
        </h2>
    </x-slot>

    <!-- Welcome User Banner -->
    <div class="mb-8 p-6 md:p-8 bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-900/40 dark:to-indigo-950/40 border border-slate-800 dark:border-slate-800/80 rounded-[2rem] text-white relative overflow-hidden shadow-lg shadow-indigo-900/10">
        <div class="relative z-10 max-w-lg">
            <h1 class="text-3xl font-extrabold font-heading mb-2 tracking-tight">Hello, {{ Auth::user()->name }}!</h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                Welcome back to your dashboard. Monitor your inventory levels, track outward shipments, and manage purchase records dynamically.
            </p>
        </div>
        <!-- Abstract Glassmorphism Shape -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Stock -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Inventory Stock</p>
                <h3 class="text-3xl font-extrabold font-heading text-slate-900 dark:text-white">{{ $stats['active_stock'] }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Total physical units</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Product Catalog</p>
                <h3 class="text-3xl font-extrabold font-heading text-slate-900 dark:text-white">{{ $stats['total_products'] }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Different SKUs registered</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>

        <!-- Purchases Expenditure -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Purchases Cost</p>
                <h3 class="text-2xl font-extrabold font-heading text-slate-900 dark:text-white">${{ number_format($stats['total_purchase_cost'], 2) }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">For {{ $stats['total_purchase_qty'] }} total units</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Dispatch Orders count -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Portal Orders</p>
                <h3 class="text-3xl font-extrabold font-heading text-slate-900 dark:text-white">{{ $stats['total_sales'] }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $stats['total_dispatch'] }} units dispatched</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/40 flex items-center justify-center text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Panels Info -->
    <div class="mt-12 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] p-6 sm:p-8">
        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">Database Master Directory</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- 1. Product Master -->
            <a href="{{ route('products.index') }}" class="p-5 border border-slate-100 dark:border-slate-800/50 hover:border-indigo-500/30 dark:hover:border-white/20 rounded-2xl flex flex-col justify-between hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span>Product Master</span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Manage your catalogue specifications, product names, SKUs, FSN, and ASIN identifier keys.</p>
                </div>
            </a>

            <!-- 2. Purchase Master -->
            <a href="{{ route('purchases.index') }}" class="p-5 border border-slate-100 dark:border-slate-800/50 hover:border-indigo-500/30 dark:hover:border-white/20 rounded-2xl flex flex-col justify-between hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span>Purchase Master</span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Track billing files, quantities bought, vendor invoices, unit pricing, and overall expense tallies.</p>
                </div>
            </a>

            <!-- 3. Inward ItemCode Master -->
            <a href="{{ route('inward-item-codes.index') }}" class="p-5 border border-slate-100 dark:border-slate-800/50 hover:border-indigo-500/30 dark:hover:border-white/20 rounded-2xl flex flex-col justify-between hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span>Inward ItemCodes</span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Log unique item serial barcodes (UIDs) as they arrive in stock to track good inventory levels.</p>
                </div>
            </a>

            <!-- 4. Sale Master -->
            <a href="{{ route('sales.index') }}" class="p-5 border border-slate-100 dark:border-slate-800/50 hover:border-indigo-500/30 dark:hover:border-white/20 rounded-2xl flex flex-col justify-between hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span>Sale Master</span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Record sales orders coming from portals (Amazon, Flipkart, etc.) along with order dates and quantities.</p>
                </div>
            </a>

            <!-- 5. Dispatch ItemCodes -->
            <a href="{{ route('dispatch-item-codes.index') }}" class="p-5 border border-slate-100 dark:border-slate-800/50 hover:border-indigo-500/30 dark:hover:border-white/20 rounded-2xl flex flex-col justify-between hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span>Dispatch ItemCodes</span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Scan or check-out serial items as they get sold and shipped to customers, updating inventory.</p>
                </div>
            </a>

            <!-- 6. User Master -->
            <a href="{{ route('users.index') }}" class="p-5 border border-slate-100 dark:border-slate-800/50 hover:border-indigo-500/30 dark:hover:border-white/20 rounded-2xl flex flex-col justify-between hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span>User Master</span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Administer security credentials and toggle active status policies for warehouse operators.</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
