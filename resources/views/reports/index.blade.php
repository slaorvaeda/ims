<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Unified Inventory Reports') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 rounded-full border border-indigo-100/50 dark:border-indigo-900/50 shadow-sm">
                System Reports
            </span>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- 1. Filters Component -->
        <x-reports.filters 
            :products="$products"
            :brands="$brands"
            :portals="$portals"
            :startDate="$startDate"
            :endDate="$endDate"
            :productId="$productId"
            :brandId="$brandId"
            :portalId="$portalId"
            :activeTab="$activeTab"
            :search="$search"
        />

        <!-- 2. KPIs Component -->
        <x-reports.kpis :kpis="$kpis" />

        <!-- 3. Reports Tab Navigation -->
        <div class="border-b border-slate-200 dark:border-slate-800/80">
            <nav class="flex space-x-8" aria-label="Tabs">
                <!-- Stock Balance Tab -->
                <a href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'stock'])) }}" 
                   class="border-b-2 py-4 px-1 text-sm font-semibold transition-all flex items-center gap-2 {{ $activeTab === 'stock' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Stock Balance Report</span>
                </a>

                <!-- Inward Log Tab -->
                <a href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'inward'])) }}" 
                   class="border-b-2 py-4 px-1 text-sm font-semibold transition-all flex items-center gap-2 {{ $activeTab === 'inward' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                    <span>Inward Serial Log</span>
                </a>

                <!-- Dispatch Log Tab -->
                <a href="{{ route('reports.index', array_merge(request()->query(), ['tab' => 'dispatch'])) }}" 
                   class="border-b-2 py-4 px-1 text-sm font-semibold transition-all flex items-center gap-2 {{ $activeTab === 'dispatch' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                    </svg>
                    <span>Dispatch Serial Log</span>
                </a>
            </nav>
        </div>

        <!-- 4. Active Tab Table Rendering -->
        <div class="mt-6">
            @if ($activeTab === 'stock')
                <x-reports.stock-table :stockData="$stockData" />
            @elseif ($activeTab === 'inward')
                <x-reports.inward-table :inwardCodes="$inwardCodes" />
            @elseif ($activeTab === 'dispatch')
                <x-reports.dispatch-table :dispatchCodes="$dispatchCodes" />
            @endif
        </div>
    </div>
</x-app-layout>
