@props(['stockData'])

<div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
    <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-white">Product Stock Balance Summary</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Total inward, dispatch, and physical items in stock per product.</p>
        </div>
        <a href="{{ route('reports.export.stock', request()->query()) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-2xl transition-all shadow-md shadow-indigo-950/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Stock Report
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">Product ID</th>
                    <th class="py-4 px-6">SKU</th>
                    <th class="py-4 px-6">Product Name</th>
                    <th class="py-4 px-6">Brand</th>
                    <th class="py-4 px-6 text-center">Inward</th>
                    <th class="py-4 px-6 text-center">Dispatch</th>
                    <th class="py-4 px-6 text-center">Available Stock</th>
                    <th class="py-4 px-6 text-right">Purchase Rate</th>
                    <th class="py-4 px-6 text-right">Stock Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                @forelse($stockData as $row)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">{{ $row['product_id'] }}</td>
                        <td class="py-4 px-6 font-mono text-xs">{{ $row['sku'] }}</td>
                        <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-300">{{ $row['name'] }}</td>
                        <td class="py-4 px-6 text-slate-500 dark:text-slate-400">
                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-xs font-medium">{{ $row['brand'] }}</span>
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-slate-800 dark:text-slate-200">{{ number_format($row['inward']) }}</td>
                        <td class="py-4 px-6 text-center font-bold text-slate-800 dark:text-slate-200">{{ number_format($row['dispatch']) }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $row['balance'] > 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-100/30' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400 border border-rose-100/30' }}">
                                {{ number_format($row['balance']) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right font-semibold text-slate-700 dark:text-slate-300">₹{{ number_format($row['purchase_rate'], 2) }}</td>
                        <td class="py-4 px-6 text-right font-extrabold text-indigo-600 dark:text-indigo-400">₹{{ number_format($row['stock_value'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400 dark:text-slate-500">No records found matching filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
