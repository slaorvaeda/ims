<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Sales Order History') }}
            </h2>
            <a href="{{ route('sales.create') }}" class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-150 flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                <span>Add Sale Order</span>
            </a>
        </div>
    </x-slot>

    <div x-data="{ showExcelTools: false }" class="space-y-6">
        <!-- Search Panel -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('sales.index') }}" class="flex-1 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Search sales by Portal ID or Product..." 
                            class="w-full pl-11 pr-5 py-3.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                        >
                        <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-sm transition-all shadow-md shadow-indigo-900/10">
                        Apply Search
                    </button>
                    @if ($search)
                        <a href="{{ route('sales.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Clear
                        </a>
                    @endif
                </form>

                <div class="flex items-center gap-2">
                    <button @click="showExcelTools = !showExcelTools" class="px-5 py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all flex items-center gap-2 border border-slate-200/40 dark:border-slate-700/40">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>Excel Import/Export</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Excel Tools Panel (Toggleable) -->
        <div x-show="showExcelTools" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="p-6 bg-gradient-to-br from-indigo-50/50 to-slate-50 dark:from-indigo-950/20 dark:to-slate-900 border border-indigo-100/60 dark:border-slate-800/80 rounded-[2rem] shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6" 
             x-cloak>
            <!-- Export Section -->
            <div class="space-y-3">
                <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Data
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Download all sales orders matching the current search criteria as an Excel-compatible CSV file.
                </p>
                <a href="{{ route('sales.export', ['search' => request('search')]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-indigo-950/10">
                    Export Filtered Sales
                </a>
            </div>

            <!-- Import Section -->
            <div class="space-y-3">
                <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Sales
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Upload an Excel/CSV file with columns: <strong>Order Date, Portal ID, Quantity, Product ID, SKU</strong>. Product ID or SKU is used to match products. Date format: YYYY-MM-DD.
                </p>
                <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <input type="file" name="file" required class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-slate-700 transition-all border border-slate-200 dark:border-slate-800 rounded-xl p-1 bg-white dark:bg-slate-950">
                    <button type="submit" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-emerald-950/10 whitespace-nowrap">
                        Upload & Import
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Panel -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Order Date</th>
                            <th class="py-4 px-6">Portal ID</th>
                            <th class="py-4 px-6">Product</th>
                            <th class="py-4 px-6">Quantity</th>
                            <th class="py-4 px-6">Updated By</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                <td class="py-4.5 px-6 font-semibold">{{ $sale->id }}</td>
                                <td class="py-4.5 px-6 font-medium">{{ \Carbon\Carbon::parse($sale->order_date)->format('Y-m-d') }}</td>
                                <td class="py-4.5 px-6">
                                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-lg font-bold text-xs">
                                        {{ $sale->portal_id }}
                                    </span>
                                </td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $sale->product->product_name ?? 'Deleted Product' }}</span>
                                        <span class="text-xs text-slate-400 font-mono">ID: {{ $sale->product->product_id ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 font-bold text-indigo-600 dark:text-indigo-400">{{ $sale->quantity }}</td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs">{{ $sale->updated_by ?? 'System' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $sale->updated_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('sales.destroy', $sale->id) }}" onsubmit="return confirm('Are you sure you want to delete this sale order?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    No sales history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sales->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/80">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
