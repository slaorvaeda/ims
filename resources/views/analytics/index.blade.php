<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Advanced Unified Analytics') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 rounded-full border border-indigo-100/50 dark:border-indigo-900/50 shadow-sm">
                Real-time Sync
            </span>
        </div>
    </x-slot>

    <!-- Include Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-8" x-data="{ showDamagedModal: false, showStockModal: false, showDispatchModal: false }">
        <!-- Advanced Filters & Parameters Section -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <h3 class="font-heading font-bold text-base text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Control & Filtering Panel
            </h3>
            
            <form method="GET" action="{{ route('analytics.index') }}" class="flex flex-col gap-3">
                <!-- Main Filters Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 w-full">
                    <!-- Start Date -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
                    </div>

                    <!-- End Date -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
                    </div>

                    <!-- Brand -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Brand</label>
                        <select 
                            name="brand_id" 
                            id="brand-filter-select"
                            onchange="filterProducts()"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm"
                        >
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" {{ $brandId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Product</label>
                        <select 
                            name="product_id" 
                            id="product-filter-select"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm"
                        >
                            <option value="">All Products</option>
                            @foreach($products as $prod)
                                <option 
                                    value="{{ $prod->id }}" 
                                    data-brand-id="{{ $prod->brand_id }}" 
                                    {{ $productId == $prod->id ? 'selected' : '' }}
                                >
                                    {{ $prod->product_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type filter -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Activity Type</label>
                        <select name="type" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
                            <option value="">All Types</option>
                            <option value="Purchase" {{ $type == 'Purchase' ? 'selected' : '' }}>Purchases</option>
                            <option value="Sale" {{ $type == 'Sale' ? 'selected' : '' }}>Sales</option>
                            <option value="Inward" {{ $type == 'Inward' ? 'selected' : '' }}>Inwards (Serial)</option>
                            <option value="Dispatch" {{ $type == 'Dispatch' ? 'selected' : '' }}>Dispatches (Serial)</option>
                        </select>
                    </div>
                </div>

                <!-- Search and Buttons Row (Aligned to the Right) -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-start md:justify-end gap-3 w-full mt-1">
                    <div class="relative flex-1 md:w-80 max-w-md">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Search keyword (Vendor, Portal, UID, SKU)..." 
                            class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm"
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10 text-center shrink-0">
                            Apply Filters
                        </button>
                        @if($startDate || $endDate || $productId || $brandId || $portalId || $vendorId || $search || $type)
                            <a href="{{ route('analytics.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs text-center transition-all shrink-0">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Portal Radio Selection Under Input (Aligned to the Right) -->
                <div class="flex flex-wrap items-center justify-start md:justify-end gap-1.5 pt-2 border-t border-dashed border-slate-100 dark:border-slate-800/80 w-full">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-2">Filter by Portal:</span>
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            type="radio" 
                            name="portal_id" 
                            value="" 
                            {{ empty($portalId) ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="this.form.submit()"
                        >
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 px-3.5 py-2 rounded-2xl peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-all shadow-sm">
                            All Portals
                        </span>
                    </label>
                    @foreach($portals as $pName)
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input 
                                type="radio" 
                                name="portal_id" 
                                value="{{ $pName }}" 
                                {{ $portalId === $pName ? 'checked' : '' }}
                                class="sr-only peer"
                                onchange="this.form.submit()"
                            >
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 px-3.5 py-2 rounded-2xl peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-all shadow-sm">
                                {{ $pName }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </form>
        </div>

        <!-- KPI Metric Highlight Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            <!-- Total Purchases Cost -->
            <div class="p-6 bg-gradient-to-br from-blue-50/60 to-white dark:from-blue-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Purchase Outflow</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">₹{{ number_format($totalPurchasesCost, 2) }}</h3>
                    <p class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold">{{ $totalUnitsPurchased }} units purchased</p>
                </div>
                <div class="w-12 h-12 bg-blue-100/60 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Total Sales Value -->
            <div class="p-6 bg-gradient-to-br from-emerald-50/60 to-white dark:from-emerald-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Sales Inflow</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">₹{{ number_format($totalSalesValue, 2) }}</h3>
                    <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">{{ number_format($totalUnitsSold) }} units dispatched ({{ $totalSalesCount }} dispatches)</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100/60 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Inward Serial Scans -->
            <div class="p-6 bg-gradient-to-br from-purple-50/60 to-white dark:from-purple-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Serial Units Inwarded</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalUnitsInwarded) }} Units</h3>
                    <p class="text-[10px] text-purple-600 dark:text-purple-400 font-semibold">Registered barcoded stock</p>
                </div>
                <div class="w-12 h-12 bg-purple-100/60 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>

            <!-- Dispatch Unit Serial Codes -->
            <div class="p-6 bg-gradient-to-br from-rose-50/60 to-white dark:from-rose-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all cursor-pointer hover:border-rose-500/50 group" @click="showDispatchModal = true" title="Click to view detailed dispatches per product">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Serial Units Dispatched</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalUnitsDispatched) }} Units</h3>
                    <p class="text-[10px] text-rose-600 dark:text-rose-400 font-semibold">Shipped scanning activity</p>
                </div>
                <div class="w-12 h-12 bg-rose-100/60 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center group-hover:bg-rose-200 dark:group-hover:bg-rose-900/60 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/></svg>
                </div>
            </div>

            <!-- Available Stock -->
            <div class="p-6 bg-gradient-to-br from-teal-50/60 to-white dark:from-teal-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all cursor-pointer hover:border-teal-500/50 group" @click="showStockModal = true" title="Click to view detailed stock per product">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Available Stock</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalUnitsAvailable) }} Units</h3>
                    <p class="text-[10px] text-teal-600 dark:text-teal-400 font-semibold">Active stock on hand</p>
                </div>
                <div class="w-12 h-12 bg-teal-100/60 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>

        <!-- KPI Metric Highlight Cards (Second Row: Damaged and Returns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Total Damaged Stock -->
            <div class="p-6 bg-gradient-to-br from-amber-50/60 to-white dark:from-amber-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all cursor-pointer hover:border-amber-500/50 group" @click="showDamagedModal = true" title="Click to view damaged stock details">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Damaged Stock</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalUnitsDamaged) }} Units</h3>
                    <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">Logged as damaged purchases</p>
                    <span class="text-[9px] text-slate-400 group-hover:text-amber-500 transition-colors">Click to view details</span>
                </div>
                <div class="w-12 h-12 bg-amber-100/60 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>

            <!-- Total Returned Stock -->
            <div class="p-6 bg-gradient-to-br from-fuchsia-50/60 to-white dark:from-fuchsia-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Returned Stock</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalInwardRTG) }} Units</h3>
                    <p class="text-[10px] text-fuchsia-600 dark:text-fuchsia-400 font-semibold">{{ number_format($totalRTGSold) }}  RTG products sold</p>
                </div>
                <div class="w-12 h-12 bg-fuchsia-100/60 dark:bg-fuchsia-950/40 text-fuchsia-600 dark:text-fuchsia-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-6m0 0l-3 3m3-3l3 3m-9 0h6m-9-3V9m0 0l-3 3m3-3l3 3m-3 0h6m-3-3v6M4 4h16v16H4V4z"/></svg>
                </div>
            </div>
        </div>

        <!-- Analytical Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Line Chart (Sales Volume vs Purchases Volume over time) -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm lg:col-span-2 space-y-4">
                <h4 class="font-heading font-bold text-sm text-slate-800 dark:text-white uppercase tracking-wider">
                    Volume Trend Timeline
                </h4>
                <div class="relative h-72">
                    <canvas id="volumeTrendChart"></canvas>
                </div>
            </div>

            <!-- Portal Sales Share Donut Chart -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <h4 class="font-heading font-bold text-sm text-slate-800 dark:text-white uppercase tracking-wider">
                    Sales Portal Breakdown
                </h4>
                <div class="relative h-72 flex items-center justify-center">
                    <canvas id="portalBreakdownChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Combined Activity Log Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm space-y-4 p-6">
            <h4 class="font-heading font-bold text-base text-slate-800 dark:text-white mb-2">
                Consolidated Chronological Activity Logs
            </h4>
            
            <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <!-- Sortable columns -->
                            <th class="py-4 px-6">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date', 'sort_dir' => $sortBy === 'date' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-slate-800 dark:hover:text-white">
                                    <span>Date</span>
                                    @if($sortBy === 'date')
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                    @endif
                                </a>
                            </th>
                            <th class="py-4 px-6">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'type', 'sort_dir' => $sortBy === 'type' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-slate-800 dark:hover:text-white">
                                    <span>Type</span>
                                    @if($sortBy === 'type')
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                    @endif
                                </a>
                            </th>
                            <th class="py-4 px-6">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'product_name', 'sort_dir' => $sortBy === 'product_name' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-slate-800 dark:hover:text-white">
                                    <span>Product Details</span>
                                    @if($sortBy === 'product_name')
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                    @endif
                                </a>
                            </th>
                            <th class="py-4 px-6">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quantity', 'sort_dir' => $sortBy === 'quantity' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-slate-800 dark:hover:text-white">
                                    <span>Quantity</span>
                                    @if($sortBy === 'quantity')
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                    @endif
                                </a>
                            </th>
                            <th class="py-4 px-6">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'amount', 'sort_dir' => $sortBy === 'amount' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-slate-800 dark:hover:text-white">
                                    <span>Cost/Amount</span>
                                    @if($sortBy === 'amount')
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                    @endif
                                </a>
                            </th>
                            <th class="py-4 px-6">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'entity_name', 'sort_dir' => $sortBy === 'entity_name' && $sortDir === 'desc' ? 'asc' : 'desc']) }}" class="flex items-center gap-1 hover:text-slate-800 dark:hover:text-white">
                                    <span>Source Entity / UID</span>
                                    @if($sortBy === 'entity_name')
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"/></svg>
                                    @endif
                                </a>
                            </th>
                            <th class="py-4 px-6">Portal</th>
                            <th class="py-4 px-6">Author</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($paginatedActivities as $activity)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 text-slate-700 dark:text-slate-300 transition-colors">
                                <td class="py-4 px-6 font-mono text-xs font-semibold">
                                    {{ $activity['date'] }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($activity['type'] === 'Purchase')
                                        <span class="px-2 py-1 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-bold border border-blue-100/50 dark:border-blue-900/40">
                                            {{ $activity['type'] }}
                                        </span>
                                    @elseif($activity['type'] === 'Sale')
                                        <span class="px-2 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-bold border border-emerald-100/50 dark:border-emerald-900/40">
                                            {{ $activity['type'] }}
                                        </span>
                                    @elseif($activity['type'] === 'Inward')
                                        <span class="px-2 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 rounded-lg text-xs font-bold border border-purple-100/50 dark:border-purple-900/40">
                                            {{ $activity['type'] }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 rounded-lg text-xs font-bold border border-rose-100/50 dark:border-rose-900/40">
                                            {{ $activity['type'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $activity['product_name'] }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">SKU: {{ $activity['sku'] ?: 'N/A' }} | ID: {{ $activity['product_id_code'] ?: 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-semibold">
                                    {{ $activity['quantity'] }}
                                </td>
                                <td class="py-4 px-6 font-mono font-semibold">
                                    {{ $activity['amount'] ? '₹' . number_format($activity['amount'], 2) : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2.5 py-1.5 rounded-lg border border-slate-200/50 dark:border-slate-700/50 font-mono">
                                        {{ $activity['entity_name'] ?: '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if(!empty($activity['portal']))
                                        <span class="px-2.5 py-1 bg-indigo-50/50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 rounded-lg border border-indigo-100/30 dark:border-indigo-900/30 text-xs font-semibold">
                                            {{ $activity['portal'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-xs font-semibold">
                                    {{ $activity['updated_by'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    No transaction/scanning activity found matching current filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            @if ($paginatedActivities->hasPages())
                <div class="mt-4">
                    {{ $paginatedActivities->links() }}
                </div>
            @endif
        </div>

        <!-- Damaged Purchases Details Modal -->
        <div x-show="showDamagedModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showDamagedModal = false" class="w-full max-w-5xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col max-h-[85vh] overflow-hidden animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Damaged Stock Purchases</h3>
                        <p class="text-xs text-slate-400 mt-1">Detailed list of damaged purchase orders</p>
                    </div>
                    <button @click="showDamagedModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4">Product Details</th>
                                    <th class="py-3 px-4">Vendor ID</th>
                                    <th class="py-3 px-4 text-right">Quantity</th>
                                    <th class="py-3 px-4 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                @forelse($damagedPurchases as $dp)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                        <td class="py-3.5 px-4 font-medium whitespace-nowrap">{{ $dp->date }}</td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $dp->product->product_name ?? 'Unknown Product' }}</span>
                                                <span class="text-[10px] text-slate-400 font-mono">ID: {{ $dp->product->product_id ?? '-' }} | SKU: {{ $dp->product->sku ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 font-medium">{{ $dp->vendor_id }}</td>
                                        <td class="py-3.5 px-4 text-right font-bold text-amber-600 dark:text-amber-400 whitespace-nowrap">{{ $dp->quantity }} Units</td>
                                        <td class="py-3.5 px-4 text-right font-bold text-slate-900 dark:text-white whitespace-nowrap">₹{{ number_format($dp->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                            No damaged purchases found in this filter range.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800/50 flex justify-end">
                    <button @click="showDamagedModal = false" class="px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold rounded-2xl text-xs hover:bg-slate-800 dark:hover:bg-slate-100 transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Active Inventory Stock Details Modal -->
        <div x-show="showStockModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showStockModal = false" class="w-full max-w-5xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col max-h-[85vh] overflow-hidden animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Active Inventory Stock</h3>
                        <p class="text-xs text-slate-400 mt-1">Detailed list of inwarded, sold/outwarded, and available stock per product</p>
                    </div>
                    <button @click="showStockModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">
                                    <th class="py-3 px-4">Brand</th>
                                    <th class="py-3 px-4">Product Details</th>
                                    <th class="py-3 px-4 text-center">Inwarded (Stock In)</th>
                                    <th class="py-3 px-4 text-center">Sold/Outwarded (Stock Out)</th>
                                    <th class="py-3 px-4 text-center">Available Stock</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                @forelse($stockBreakdown as $sb)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">{{ $sb['brand_name'] }}</td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $sb['product_name'] }}</span>
                                                <span class="text-[10px] text-slate-400 font-mono">ID: {{ $sb['product_id_code'] }} | SKU: {{ $sb['sku'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-xs font-black">{{ number_format($sb['inward']) }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-rose-600 dark:text-rose-400 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/30 rounded-full text-xs font-black">{{ number_format($sb['outward']) }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 rounded-full text-xs font-black">{{ number_format($sb['available']) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                            No stock data matching your filter criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800/50 flex justify-end">
                    <button @click="showStockModal = false" class="px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold rounded-2xl text-xs hover:bg-slate-800 dark:hover:bg-slate-100 transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Serial Units Dispatched Details Modal -->
        <div x-show="showDispatchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showDispatchModal = false" class="w-full max-w-5xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col max-h-[85vh] overflow-hidden animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Serial Units Dispatched Details</h3>
                        <p class="text-xs text-slate-400 mt-1">Detailed list of units dispatched per product matching your active filters</p>
                    </div>
                    <button @click="showDispatchModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">
                                    <th class="py-3 px-4">Brand</th>
                                    <th class="py-3 px-4">Product Details</th>
                                    <th class="py-3 px-4 text-center">Dispatched (Stock Out)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                @forelse($dispatchBreakdown as $db)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-rose-600 dark:text-rose-400 whitespace-nowrap">{{ $db['brand_name'] }}</td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $db['product_name'] }}</span>
                                                <span class="text-[10px] text-slate-400 font-mono">ID: {{ $db['product_id_code'] }} | SKU: {{ $db['sku'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-rose-600 dark:text-rose-400 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/30 rounded-full text-xs font-black">{{ number_format($db['dispatch_count']) }} Units</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                            No dispatches found matching your filter criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800/50 flex justify-end">
                    <button @click="showDispatchModal = false" class="px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold rounded-2xl text-xs hover:bg-slate-800 dark:hover:bg-slate-100 transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dark mode helper
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#f1f5f9';

            // 1. Volume Trend Line/Bar Chart
            const ctxTrend = document.getElementById('volumeTrendChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Sales Quantity',
                            data: {!! json_encode($chartSales) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3
                        },
                        {
                            label: 'Purchases Quantity',
                            data: {!! json_encode($chartPurchases) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Plus Jakarta Sans'
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Plus Jakarta Sans'
                                }
                            }
                        }
                    }
                }
            });

            // 2. Portal Breakdown Pie Chart
            const ctxPortal = document.getElementById('portalBreakdownChart').getContext('2d');
            new Chart(ctxPortal, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($portalLabels) !!},
                    datasets: [{
                        data: {!! json_encode($portalValues) !!},
                        backgroundColor: [
                            '#4f46e5',
                            '#10b981',
                            '#f59e0b',
                            '#ec4899',
                            '#8b5cf6',
                            '#06b6d4'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // 3. Dynamic Brand to Product options filter
            const brandSelect = document.getElementById('brand-filter-select');
            const productSelect = document.getElementById('product-filter-select');
            
            if (brandSelect && productSelect) {
                const originalOptions = Array.from(productSelect.options);
                
                window.filterProducts = function() {
                    const selectedBrandId = brandSelect.value;
                    const currentSelectedProductValue = productSelect.value;
                    
                    productSelect.innerHTML = '';
                    
                    let isCurrentProductStillValid = false;
                    originalOptions.forEach(option => {
                        const optionBrandId = option.getAttribute('data-brand-id');
                        
                        // Check if the product has a valid brand associated
                        const hasBrand = optionBrandId !== null && optionBrandId !== '';
                        
                        // Show option if no brand is filtered, or if the product has a brand that matches the selection
                        const isMatch = !selectedBrandId || (hasBrand && optionBrandId == selectedBrandId);
                        
                        if (option.value === '' || isMatch) {
                            productSelect.appendChild(option.cloneNode(true));
                            if (option.value === currentSelectedProductValue) {
                                isCurrentProductStillValid = true;
                            }
                        }
                    });
                    
                    if (isCurrentProductStillValid) {
                        productSelect.value = currentSelectedProductValue;
                    } else {
                        productSelect.value = '';
                    }
                };
                
                window.filterProducts();
            }
        });
    </script>
</x-app-layout>
