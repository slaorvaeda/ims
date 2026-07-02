<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Inventory Overview') }}
        </h2>
    </x-slot>

    <div x-data="{ showStockModal: false }" class="py-6">
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
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 flex items-center justify-between shadow-sm hover:shadow-md hover:border-indigo-500/50 transition-all duration-200 group cursor-pointer" @click="showStockModal = true" title="Click to view detailed stock breakdown">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Inventory Stock</p>
                <h3 id="stat-active-stock" class="text-3xl font-extrabold font-heading text-slate-900 dark:text-white transition-all duration-350">{{ $stats['active_stock'] }}</h3>
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
                <h3 class="text-2xl font-extrabold font-heading text-slate-900 dark:text-white">₹{{ number_format($stats['total_purchase_cost'], 2) }}</h3>
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
                <p class="text-xs text-slate-500 dark:text-slate-400"><span id="stat-total-dispatch" class="transition-all duration-350">{{ $stats['total_dispatch'] }}</span> units dispatched</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/40 flex items-center justify-center text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="mt-12 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-extrabold font-heading text-slate-900 dark:text-white">Warehouse Insights & Analytics</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Real-time charts tracking transaction volumes, product levels, and logistics activity.</p>
            </div>
            <div class="flex items-center gap-2 bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 px-4 py-2 rounded-2xl self-start sm:self-center">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wide">Live Feed Active</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart 1: Sales & Purchases Growth Trend (Area Chart) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-md font-bold text-slate-800 dark:text-white">Sales & Purchases Growth Trend</h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Monthly transactional quantities comparison</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                        <span class="inline-block w-2.5 h-2.5 rounded bg-indigo-600"></span> Purchases
                        <span class="inline-block w-2.5 h-2.5 rounded bg-emerald-500 ml-2"></span> Sales
                    </div>
                </div>
                <div id="chart-monthly-trends" class="min-h-[300px]"></div>
            </div>

            <!-- Chart 2: Sales Channel Distribution (Donut Chart) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div>
                    <h4 class="text-md font-bold text-slate-800 dark:text-white">Sales Channels Allocation</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-6">Distribution of order sources</p>
                </div>
                <div class="flex items-center justify-center min-h-[300px]">
                    <div id="chart-portal-distribution" class="w-full"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 3: Physical Product Inventory (Horizontal Bar Chart) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="mb-6">
                    <h4 class="text-md font-bold text-slate-800 dark:text-white">Current Physical Stock Levels</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Inward inventory minus dispatched units</p>
                </div>
                <div id="chart-product-stock" class="min-h-[300px]"></div>
            </div>

            <!-- Chart 4: Logistics Activity Timeline (Column Chart) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-md font-bold text-slate-800 dark:text-white">Logistics Daily Intake & Dispatch</h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-sans">7-Day inward receipts vs outward dispatches</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                        <span class="inline-block w-2.5 h-2.5 rounded bg-indigo-500"></span> Inward
                        <span class="inline-block w-2.5 h-2.5 rounded bg-amber-500 ml-2"></span> Dispatched
                    </div>
                </div>
                <div id="chart-daily-log" class="min-h-[300px]"></div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if dark mode is active
            const isDarkMode = document.documentElement.classList.contains('dark') || 
                               (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            // 1. Sales vs Purchases area chart
            const monthlyTrendsOptions = {
                series: [{
                    name: 'Purchases (Qty)',
                    data: @json($chartMonthlyPurchases)
                }, {
                    name: 'Sales (Qty)',
                    data: @json($chartMonthlySales)
                }],
                chart: {
                    height: 320,
                    type: 'area',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#4f46e5', '#10b981'], // indigo-600, emerald-500
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                grid: {
                    borderColor: isDarkMode ? '#1e293b' : '#f1f5f9',
                    strokeDashArray: 4
                },
                xaxis: {
                    categories: @json($chartMonthlyMonths),
                    labels: {
                        style: { colors: isDarkMode ? '#94a3b8' : '#64748b' }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: isDarkMode ? '#94a3b8' : '#64748b' }
                    }
                },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light',
                    y: {
                        formatter: function (val) {
                            return val + " units";
                        }
                    }
                },
                legend: { show: false }
            };
            const monthlyTrendsChart = new ApexCharts(document.querySelector("#chart-monthly-trends"), monthlyTrendsOptions);
            monthlyTrendsChart.render();

            // 2. Sales Portals donut chart
            const portalDistributionOptions = {
                series: @json($chartPortalSales),
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                labels: @json($chartPortalNames),
                colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
                stroke: { show: false },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Sold',
                                    color: isDarkMode ? '#94a3b8' : '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' units'
                                    }
                                }
                            }
                        }
                    }
                },
                grid: {
                    padding: { bottom: 0 }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    labels: { colors: isDarkMode ? '#cbd5e1' : '#334155' }
                },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light'
                }
            };
            const portalDistributionChart = new ApexCharts(document.querySelector("#chart-portal-distribution"), portalDistributionOptions);
            portalDistributionChart.render();

            // 3. Product Stocks horizontal bar chart
            const productStockOptions = {
                series: [{
                    name: 'Physical Stock',
                    data: @json($chartProductStocks)
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                        barHeight: '45%',
                        distributed: true
                    }
                },
                colors: ['#4f46e5', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                dataLabels: { enabled: false },
                grid: {
                    borderColor: isDarkMode ? '#1e293b' : '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: false } }
                },
                xaxis: {
                    categories: @json($chartProductNames),
                    labels: {
                        style: { colors: isDarkMode ? '#94a3b8' : '#64748b' }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: isDarkMode ? '#94a3b8' : '#64748b' }
                    }
                },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light',
                    y: {
                        formatter: function (val) {
                            return val + " units in stock";
                        }
                    }
                },
                legend: { show: false }
            };
            const productStockChart = new ApexCharts(document.querySelector("#chart-product-stock"), productStockOptions);
            productStockChart.render();

            // 4. Daily Activity Log (Inward vs Dispatch)
            const dailyActivityOptions = {
                series: [{
                    name: 'Inward Receipts',
                    data: @json($chartActivityInward)
                }, {
                    name: 'Outward Dispatches',
                    data: @json($chartActivityDispatch)
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 6,
                    },
                },
                colors: ['#6366f1', '#f59e0b'], // Indigo-500, Amber-500
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                grid: {
                    borderColor: isDarkMode ? '#1e293b' : '#f1f5f9',
                    strokeDashArray: 4
                },
                xaxis: {
                    categories: @json($chartActivityDays),
                    labels: {
                        style: { colors: isDarkMode ? '#94a3b8' : '#64748b' }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: isDarkMode ? '#94a3b8' : '#64748b' }
                    }
                },
                fill: { opacity: 1 },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light',
                    y: {
                        formatter: function (val) {
                            return val + " items";
                        }
                    }
                },
                legend: { show: false }
            };
            const dailyActivityChart = new ApexCharts(document.querySelector("#chart-daily-log"), dailyActivityOptions);
            dailyActivityChart.render();
        });
    </script>

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

    <!-- Notification Toast Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-4 pointer-events-none w-full max-w-md"></div>

    <style>
        .toast-card {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(99, 102, 241, 0.3);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.5), 0 8px 10px -6px rgb(0 0 0 / 0.5);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            transform: translateX(120%);
            opacity: 0;
        }
        .toast-card.show {
            transform: translateX(0);
            opacity: 1;
        }
    </style>

    <!-- WebSockets Live Feeds -->
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Audio chimes
            function playChime() {
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    
                    const osc1 = audioCtx.createOscillator();
                    const gain1 = audioCtx.createGain();
                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(523.25, audioCtx.currentTime); // C5
                    gain1.gain.setValueAtTime(0.04, audioCtx.currentTime);
                    gain1.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.4);
                    osc1.connect(gain1);
                    gain1.connect(audioCtx.destination);
                    osc1.start();
                    osc1.stop(audioCtx.currentTime + 0.4);

                    setTimeout(() => {
                        const osc2 = audioCtx.createOscillator();
                        const gain2 = audioCtx.createGain();
                        osc2.type = 'sine';
                        osc2.frequency.setValueAtTime(659.25, audioCtx.currentTime); // E5
                        gain2.gain.setValueAtTime(0.04, audioCtx.currentTime);
                        gain2.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.5);
                        osc2.connect(gain2);
                        gain2.connect(audioCtx.destination);
                        osc2.start();
                        osc2.stop(audioCtx.currentTime + 0.5);
                    }, 100);
                } catch (e) {
                    console.log("Audio contextual block active", e);
                }
            }

            // Initialize Echo
            const echoHost = window.location.hostname;
            const reverbPort = {{ env('REVERB_PORT', 8080) }};
            const reverbKey = "{{ env('REVERB_APP_KEY') }}";

            if (reverbKey) {
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: reverbKey,
                    wsHost: echoHost,
                    wsPort: reverbPort,
                    wssPort: reverbPort,
                    forceTLS: window.location.protocol === 'https:',
                    enabledTransports: ['ws', 'wss'],
                });

                // Listen for dispatches
                window.Echo.channel('dispatches')
                    .listen('.barcode.dispatched', (e) => {
                        console.log('Dispatch event received:', e);
                        
                        // 1. Play premium audio feedback
                        playChime();

                        // 2. Update stats indicators with text animation
                        const activeStockEl = document.getElementById('stat-active-stock');
                        const totalDispatchEl = document.getElementById('stat-total-dispatch');

                        if (activeStockEl && e.stats) {
                            activeStockEl.classList.add('scale-110', 'text-rose-500', 'font-black');
                            setTimeout(() => {
                                activeStockEl.innerText = e.stats.active_stock;
                                setTimeout(() => {
                                    activeStockEl.classList.remove('scale-110', 'text-rose-500', 'font-black');
                                }, 300);
                            }, 150);
                        }

                        if (totalDispatchEl && e.stats) {
                            totalDispatchEl.classList.add('scale-110', 'text-amber-500', 'font-black');
                            setTimeout(() => {
                                totalDispatchEl.innerText = e.stats.total_dispatch;
                                setTimeout(() => {
                                    totalDispatchEl.classList.remove('scale-110', 'text-amber-500', 'font-black');
                                }, 300);
                            }, 150);
                        }

                        // 3. Render slide-in glassmorphism notification toast
                        const toastContainer = document.getElementById('toast-container');
                        if (toastContainer) {
                            const toast = document.createElement('div');
                            toast.className = 'toast-card pointer-events-auto p-5 rounded-2xl flex items-start gap-4 border border-slate-700/60 text-white w-full shadow-2xl';
                            toast.innerHTML = `
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 flex-shrink-0 animate-pulse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Live Dispatch</p>
                                        <p class="text-[10px] text-slate-500">Just now</p>
                                    </div>
                                    <h4 class="font-bold text-sm text-slate-100 mt-1">Item Sold & Dispatched</h4>
                                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                                        Serial Code <span class="font-mono text-slate-200 font-bold bg-slate-800/80 px-1.5 py-0.5 rounded border border-slate-700">${e.uid}</span> (<span class="font-medium text-slate-300">${e.productName}</span>) was shipped by operator <span class="font-semibold text-indigo-300">${e.updatedBy}</span>.
                                    </p>
                                </div>
                            `;

                            toastContainer.appendChild(toast);
                            
                            // Trigger slide-in entry animation
                            setTimeout(() => {
                                toast.classList.add('show');
                            }, 50);

                            // Trigger exit animation and remove from DOM
                            setTimeout(() => {
                                toast.classList.remove('show');
                                setTimeout(() => {
                                    toast.remove();
                                }, 500);
                            }, 5500);
                        }
                    });
            }
        });
    </script>
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
                                            No stock data available.
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
    </div>
</x-app-layout>
