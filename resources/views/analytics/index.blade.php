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

    <div class="space-y-8">
        <!-- Advanced Filters & Parameters Section -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <h3 class="font-heading font-bold text-base text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Control & Filtering Panel
            </h3>
            
            <form method="GET" action="{{ route('analytics.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Start Date -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                </div>

                <!-- End Date -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                </div>

                <!-- Product -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase">Product</label>
                    <select name="product_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                        <option value="">All Products</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ $productId == $prod->id ? 'selected' : '' }}>{{ $prod->product_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type filter -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase">Activity Type</label>
                    <select name="type" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                        <option value="">All Types</option>
                        <option value="Purchase" {{ $type == 'Purchase' ? 'selected' : '' }}>Purchases</option>
                        <option value="Sale" {{ $type == 'Sale' ? 'selected' : '' }}>Sales</option>
                        <option value="Inward" {{ $type == 'Inward' ? 'selected' : '' }}>Inwards (Serial)</option>
                        <option value="Dispatch" {{ $type == 'Dispatch' ? 'selected' : '' }}>Dispatches (Serial)</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-400 uppercase">Keyword Search</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Vendor, Portal, UID, SKU..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10 text-center">
                        Apply
                    </button>
                    @if($startDate || $endDate || $productId || $portalId || $vendorId || $search || $type)
                        <a href="{{ route('analytics.index') }}" class="py-3 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs text-center transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- KPI Metric Highlight Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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

            <!-- Total Units Sold -->
            <div class="p-6 bg-gradient-to-br from-emerald-50/60 to-white dark:from-emerald-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Sales Orders Volume</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalUnitsSold) }} Units</h3>
                    <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">{{ $totalSalesCount }} orders recorded</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100/60 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
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
            <div class="p-6 bg-gradient-to-br from-rose-50/60 to-white dark:from-rose-950/20 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex items-center justify-between hover:scale-[1.02] transition-all">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Serial Units Dispatched</span>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($totalUnitsDispatched) }} Units</h3>
                    <p class="text-[10px] text-rose-600 dark:text-rose-400 font-semibold">Shipped scanning activity</p>
                </div>
                <div class="w-12 h-12 bg-rose-100/60 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/></svg>
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
                                        {{ $activity['entity_name'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs font-semibold">
                                    {{ $activity['updated_by'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
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
        });
    </script>
</x-app-layout>
