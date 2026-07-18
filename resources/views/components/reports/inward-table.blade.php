@props(['inwardCodes'])

<div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
    <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-white">Inward Serial Codes Detail Log</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Chronological history of registered barcode inputs.</p>
        </div>
        <a href="{{ route('reports.export.inward', request()->query()) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-2xl transition-all shadow-md shadow-indigo-950/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Inward Log
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">Serial UID</th>
                    <th class="py-4 px-6">Product</th>
                    <th class="py-4 px-6">SKU</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Mark</th>
                    <th class="py-4 px-6">Updated By</th>
                    <th class="py-4 px-6">Inward Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                @forelse($inwardCodes as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors duration-150">
                        <td class="py-4 px-6 font-mono text-xs font-bold text-slate-800 dark:text-slate-200">
                            {{ $item->uid }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $item->product->product_name ?? 'N/A' }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $item->product->brand->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-mono text-xs">{{ $item->product->sku ?? 'N/A' }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status === 'Sold' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-400 border border-indigo-100/30' : ($item->status === 'Damaged' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($item->mark)
                                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400 rounded-full text-xs font-semibold border border-purple-100/20">
                                    {{ $item->mark }}
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-slate-600 dark:text-slate-400 font-medium">{{ $item->updated_by ?? 'System' }}</td>
                        <td class="py-4 px-6 text-slate-500 dark:text-slate-400 text-xs">{{ $item->created_at ? $item->created_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400 dark:text-slate-500">No inward serial codes logged for selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inwardCodes->hasPages())
        <div class="p-6 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/50">
            {{ $inwardCodes->links() }}
        </div>
    @endif
</div>
