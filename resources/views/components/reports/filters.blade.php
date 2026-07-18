@props(['products', 'brands', 'portals', 'startDate', 'endDate', 'productId', 'brandId', 'portalId', 'activeTab', 'search'])

@php
    $brandOptions = collect([['value' => '', 'label' => 'All Brands']])
        ->concat($brands->map(fn($b) => ['value' => (string)$b->id, 'label' => $b->name]))
        ->toArray();

    $productOptions = collect([['value' => '', 'label' => 'All Products']])
        ->concat($products->map(fn($p) => ['value' => (string)$p->id, 'label' => $p->product_name]))
        ->toArray();
@endphp

<div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
    <h3 class="font-heading font-bold text-base text-slate-800 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        Filter Report Parameters
    </h3>
    
    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col gap-4">
        <!-- Preserve active tab -->
        <input type="hidden" name="tab" value="{{ $activeTab }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Start Date -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
            </div>

            <!-- End Date -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
            </div>

            <!-- Brand Searchable Select -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Brand</label>
                <x-searchable-select 
                    name="brand_id" 
                    :options="$brandOptions" 
                    :selected="$brandId" 
                    placeholder="All Brands"
                />
            </div>

            <!-- Product Searchable Select -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Product</label>
                <x-searchable-select 
                    name="product_id" 
                    :options="$productOptions" 
                    :selected="$productId" 
                    placeholder="All Products"
                />
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 border-t border-slate-100 dark:border-slate-800/80 pt-4">
            <!-- Portal Filter inside options -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-1">Portal:</span>
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="radio" name="portal_id" value="" {{ empty($portalId) ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 px-3.5 py-1.5 rounded-xl peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-all">All Portals</span>
                </label>
                @foreach($portals as $portal)
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="radio" name="portal_id" value="{{ $portal->id }}" {{ $portalId == $portal->id ? 'checked' : '' }} class="sr-only peer" onchange="this.form.submit()">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 px-3.5 py-1.5 rounded-xl peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-all">{{ $portal->name }}</span>
                    </label>
                @endforeach
            </div>

            <!-- Search input and action buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search Serial, SKU, Name..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10">Apply Filters</button>
                    @if($startDate || $endDate || $productId || $brandId || $portalId || $search)
                        <a href="{{ route('reports.index', ['tab' => $activeTab]) }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs text-center transition-all">Reset</a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
