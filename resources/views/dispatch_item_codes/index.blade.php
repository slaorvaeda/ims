<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Dispatch Unit Serial Codes') }}
            </h2>
            <a href="{{ route('dispatch-item-codes.create') }}" class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-150 flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                <span>Dispatch Item</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="barcodeModalApp()">
        <!-- Barcode Scanner Input Form for Cancellation -->
        <div class="p-6 bg-gradient-to-r from-indigo-50/50 to-rose-50/50 dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between transition-colors">
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 animate-pulse text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2M6 20H4a2 2 0 01-2-2v-2m16-10V4a2 2 0 00-2-2h-2m-8 2H4a2 2 0 00-2 2v2" />
                    </svg>
                    <span>Barcode Scan Dispatch Canceller</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Scan or type a serial code to cancel its dispatch and return it to Good Inventory.</p>
            </div>
            
            <form id="scan-cancel-form" action="{{ route('dispatch-item-codes.scan-cancel') }}" method="POST" class="w-full md:w-auto flex flex-wrap sm:flex-nowrap gap-3 items-center">
                @csrf
                <div class="relative w-full md:w-80">
                    <input 
                        type="text" 
                        name="scan_uid" 
                        placeholder="Scan Barcode/QR to Cancel (e.g. Zig0001)..." 
                        autofocus
                        required
                        @focus="inputFocused = true"
                        @blur="inputFocused = false"
                        class="w-full pl-5 pr-28 py-3.5 bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-100 dark:focus:ring-rose-950/30 transition-all font-mono"
                    >
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center pointer-events-none select-none">
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-lg text-[9px] font-bold border border-emerald-100/50 dark:border-emerald-900/50 shadow-sm" x-show="inputFocused" x-cloak>
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            <span>Active Scan</span>
                        </div>
                        <div class="flex items-center gap-1 px-2.5 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 rounded-lg text-[9px] font-bold border border-rose-100/50 dark:border-rose-900/50 shadow-sm" x-show="!inputFocused" x-cloak>
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            <span>Paused</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-2xl text-sm transition-all shadow-md shadow-rose-900/10 whitespace-nowrap">
                    Cancel Dispatch
                </button>
            </form>
        </div>

        <!-- Search Panel -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('dispatch-item-codes.index') }}" class="flex-1 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Search dispatch by UID or Product..." 
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
                        <a href="{{ route('dispatch-item-codes.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Clear
                        </a>
                    @endif
                </form>

                <div class="flex items-center gap-2">
                    <button type="button" @click="showExcelTools = !showExcelTools" class="px-5 py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all flex items-center gap-2 border border-slate-200/40 dark:border-slate-700/40 shrink-0">
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
                    Download all dispatch serial codes matching the current search criteria as an Excel-compatible CSV file.
                </p>
                <a href="{{ route('dispatch-item-codes.export', ['search' => request('search')]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-indigo-950/10">
                    Export Filtered Dispatch Codes
                </a>
            </div>

            <!-- Import Section -->
            <div class="space-y-3">
                <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Dispatch Codes
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Upload an Excel/CSV file with columns: <strong>UID, Product ID, SKU, Quantity, Status</strong>. Product ID or SKU is used to match products. Status defaults to "Sold".
                </p>
                <form action="{{ route('dispatch-item-codes.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <input type="file" name="file" required class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-slate-700 transition-all border border-slate-200 dark:border-slate-800 rounded-xl p-1 bg-white dark:bg-slate-950">
                    <button type="submit" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-emerald-950/10 whitespace-nowrap">
                        Upload & Import
                    </button>
                </form>
            </div>
        </div>

        <!-- Bulk Action Bar -->
        <div 
            x-show="selectedUids.length > 0" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="p-4 bg-indigo-50 dark:bg-slate-900/60 border border-indigo-100/30 dark:border-slate-800 rounded-3xl flex items-center justify-between shadow-sm"
            style="display: none;"
        >
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold" x-text="selectedUids.length"></span>
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">barcodes selected for download</span>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    @click="selectedUids = []" 
                    class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-3 py-2 transition-all"
                >
                    Clear Selection
                </button>
                <button 
                    @click="downloadSelectedBarcodes()" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Download Selected</span>
                </button>
                <button 
                    @click="printSelectedBarcodes()" 
                    class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                    <span>Print Selected</span>
                </button>
            </div>
        </div>

        <!-- Table Panel -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6 w-12 select-none">
                                <input 
                                    type="checkbox" 
                                    @change="toggleSelectAll($event)"
                                    :checked="isAllSelected()"
                                    class="w-4 h-4 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                                >
                            </th>
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">UID (Dispatched Code)</th>
                            <th class="py-4 px-6">Product Name</th>
                            <th class="py-4 px-6">Quantity</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Portal</th>
                            <th class="py-4 px-6">Mark</th>
                            <th class="py-4 px-6">Dispatched By</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($dispatchItemCodes as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                <td class="py-4.5 px-6 w-12">
                                    <input 
                                        type="checkbox" 
                                        :value="{{ json_encode($item->uid) }}" 
                                        x-model="selectedUids"
                                        class="w-4 h-4 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                                    >
                                </td>
                                <td class="py-4.5 px-6 font-semibold">{{ $item->id }}</td>
                                <td class="py-4.5 px-6">
                                    <div class="flex items-center gap-3 cursor-pointer group" @click="openBarcodeModal({{ json_encode($item->uid) }}, {{ json_encode($item->product->sku ?? $item->product->product_name ?? 'Product') }})" title="Click to view/print barcode label">
                                        <div class="flex flex-col gap-1.5 shrink-0">
                                            <span class="px-3 py-1 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800 rounded-xl font-mono text-xs font-bold text-slate-800 dark:text-slate-200 shadow-sm w-fit group-hover:border-indigo-500/50 dark:group-hover:border-indigo-500/50 transition-colors">
                                                {{ $item->uid }}
                                            </span>
                                            <!-- Inline Rendered Barcode SVG -->
                                            <svg id="inline-barcode-{{ $item->id }}" class="bg-white p-0.5 rounded border border-slate-100 max-w-[130px] h-[32px] group-hover:border-indigo-500/50 transition-colors"></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $item->product->product_name ?? 'Deleted Product' }}</span>
                                        <span class="text-xs text-slate-400 font-mono">ID: {{ $item->product->product_id ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 font-semibold text-rose-500">{{ $item->quantity }}</td>
                                <td class="py-4.5 px-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900/50">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="py-4.5 px-6 font-semibold">
                                    @if ($item->portal)
                                        <span class="px-2.5 py-1 bg-indigo-50/50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 rounded-lg border border-indigo-100/30 dark:border-indigo-900/30 text-xs font-semibold">
                                            {{ $item->portal->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6 font-mono text-xs font-semibold">
                                    @if ($item->mark)
                                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg border border-slate-200/50 dark:border-slate-700/50">
                                            {{ $item->mark }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs">{{ $item->updated_by ?? 'System' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $item->updated_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('dispatch-item-codes.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('dispatch-item-codes.destroy', $item->id) }}" onsubmit="return confirm('Are you sure you want to cancel this dispatch record?');" class="inline">
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
                                <td colspan="10" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    No dispatch serial items registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($dispatchItemCodes->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/80">
                    {{ $dispatchItemCodes->links() }}
                </div>
            @endif
        </div>
    <!-- Note: Closing div moved to bottom of file -->

    <!-- Barcode View Modal -->
    <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" style="display: none;" x-cloak>
        <div @click.away="closeBarcodeModal()" class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 shadow-xl space-y-6 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/50 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading">Barcode Label</h3>
                    <p class="text-xs text-slate-400" x-text="productName"></p>
                </div>
                <button @click="closeBarcodeModal()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Printable Label Container -->
            <div id="modal-barcode-print-content" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-950/40 rounded-3xl border border-slate-100 dark:border-slate-800/80">
                <span class="barcode-uid-title text-xs font-bold text-slate-400 dark:text-slate-500 font-mono tracking-wider mb-2" x-text="uid"></span>
                <!-- SKU Name at top of barcode -->
                <div class="print-sku-title text-[9px] text-slate-400 dark:text-slate-500 font-mono mb-1 text-center hidden" x-text="productName.toLowerCase()"></div>
                <svg id="modal-barcode-svg" class="bg-white p-1 rounded max-w-full"></svg>
            </div>

            <!-- Print Dimensions (mm) -->
            <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Print Dimensions (mm)</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 mb-1">Width (mm)</label>
                        <input 
                            type="number" 
                            min="10" 
                            max="300" 
                            x-model.number="printWidth"
                            @input="setStorage('barcode_printWidth', printWidth)"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
                            placeholder="50"
                        >
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 dark:text-slate-500 mb-1">Height (mm)</label>
                        <input 
                            type="number" 
                            min="10" 
                            max="300" 
                            x-model.number="printHeight"
                            @input="setStorage('barcode_printHeight', printHeight)"
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
                            placeholder="25"
                        >
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800/50 pt-4">
                <button @click="downloadBarcode()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Download PNG</span>
                </button>
                <button @click="printBarcode()" class="px-6 py-3 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                    <span>Print Label</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Barcode Rendering and Modal Logic -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        function getStorage(key, fallback) {
            try {
                const val = localStorage.getItem(key);
                return val !== null ? val : fallback;
            } catch (e) {
                return fallback;
            }
        }
        function setStorage(key, val) {
            try {
                localStorage.setItem(key, val);
            } catch (e) {}
        }

        function barcodeModalApp() {
            return {
                isOpen: false,
                showExcelTools: false,
                inputFocused: false,
                uid: '',
                productName: '',
                printWidth: parseInt(getStorage('barcode_printWidth', '50')) || 50,
                printHeight: parseInt(getStorage('barcode_printHeight', '25')) || 25,
                printGap: parseInt(getStorage('barcode_printGap', '3')) || 3,
                format: getStorage('barcode_format', 'CODE128'),
                barWidth: parseInt(getStorage('barcode_barWidth', '2')) || 2,
                barHeight: parseInt(getStorage('barcode_barHeight', '60')) || 60,
                displayValue: getStorage('barcode_displayValue', 'true') === 'true',
                selectedUids: [],
                pageUids: {!! json_encode($dispatchItemCodes->pluck('uid')->toArray()) !!},
                
                isAllSelected() {
                    return this.pageUids.length > 0 && this.pageUids.every(uid => this.selectedUids.includes(uid));
                },
                
                toggleSelectAll(event) {
                    if (event.target.checked) {
                        this.pageUids.forEach(uid => {
                            if (!this.selectedUids.includes(uid)) {
                                this.selectedUids.push(uid);
                            }
                        });
                    } else {
                        this.selectedUids = this.selectedUids.filter(uid => !this.pageUids.includes(uid));
                    }
                },

                downloadBarcodeByText(uid) {
                    const tempSvg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    tempSvg.style.display = "none";
                    document.body.appendChild(tempSvg);
                    
                    try {
                        JsBarcode(tempSvg, uid, {
                            format: this.format,
                            width: this.barWidth,
                            height: this.barHeight,
                            displayValue: this.displayValue,
                            margin: 10,
                            background: "#ffffff",
                            lineColor: "#000000"
                        });
                        
                        const svgString = new XMLSerializer().serializeToString(tempSvg);
                        const svgBlob = new Blob([svgString], { type: "image/svg+xml;charset=utf-8" });
                        
                        const URL = window.URL || window.webkitURL || window;
                        const blobURL = URL.createObjectURL(svgBlob);
                        
                        const image = new Image();
                        image.onload = () => {
                            const canvas = document.createElement("canvas");
                            const bbox = tempSvg.getBBox();
                            canvas.width = bbox.width + 20;
                            canvas.height = bbox.height + 20;
                            const context = canvas.getContext("2d");
                            
                            context.fillStyle = "#ffffff";
                            context.fillRect(0, 0, canvas.width, canvas.height);
                            context.drawImage(image, 10, 10);
                            
                            const png = canvas.toDataURL("image/png");
                            const downloadLink = document.createElement("a");
                            downloadLink.href = png;
                            downloadLink.download = "barcode-" + uid.replace(/[^a-z0-9]/gi, '_').toLowerCase() + ".png";
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);
                            
                            document.body.removeChild(tempSvg);
                            URL.revokeObjectURL(blobURL);
                        };
                        image.src = blobURL;
                    } catch (err) {
                        console.error("Failed to generate/download barcode for " + uid, err);
                        if (tempSvg.parentNode) {
                            document.body.removeChild(tempSvg);
                        }
                    }
                },

                downloadSelectedBarcodes() {
                    if (this.selectedUids.length === 0) return;
                    this.selectedUids.forEach((uid, index) => {
                        setTimeout(() => {
                            this.downloadBarcodeByText(uid);
                        }, index * 250);
                    });
                },

                printSelectedBarcodes() {
                    if (this.selectedUids.length === 0) return;
                    
                    const printDiv = document.createElement("div");
                    printDiv.id = "temp-print-area";
                    printDiv.style.setProperty('--print-w', `${this.printWidth || 50}mm`);
                    printDiv.style.setProperty('--print-h', `${this.printHeight || 25}mm`);
                    
                    const printArea = document.createElement("div");
                    printArea.id = "print-area";
                    printDiv.appendChild(printArea);
                    document.body.appendChild(printDiv);
                    
                    this.selectedUids.forEach((uid, index) => {
                        const card = document.createElement("div");
                        card.className = "barcode-card";
                        
                        const skuText = document.createElement("div");
                        skuText.className = "print-sku-title";
                        
                        let product = "";
                        try {
                            const rows = document.querySelectorAll("tbody tr");
                            for (let row of rows) {
                                const checkbox = row.querySelector("input[type='checkbox']");
                                if (checkbox && checkbox.value === JSON.stringify(uid)) {
                                    const productNameSpan = row.querySelector("td:nth-child(4) span.font-bold") || row.querySelector("td:nth-child(4)");
                                    if (productNameSpan) {
                                        product = productNameSpan.textContent.trim().toLowerCase();
                                    }
                                    break;
                                }
                            }
                        } catch (e) {}
                        
                        skuText.textContent = product || "product";
                        card.appendChild(skuText);
                        
                        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                        svg.id = "print-svg-" + index;
                        card.appendChild(svg);
                        printArea.appendChild(card);
                        
                        try {
                            JsBarcode(svg, uid, {
                                format: this.format,
                                width: this.barWidth,
                                height: this.barHeight,
                                displayValue: this.displayValue,
                                margin: 10,
                                marginTop: 2,
                                background: "#ffffff",
                                lineColor: "#000000"
                            });
                        } catch (err) {
                            console.error("Failed to render barcode in print selection", err);
                        }
                    });
                    
                    const style = document.createElement("style");
                    style.id = "temp-print-style-bulk";
                    style.innerHTML = `
                        @media print {
                            @page {
                                margin: 0;
                            }
                            body > :not(#temp-print-area) {
                                display: none !important;
                            }
                            #temp-print-area {
                                position: static !important;
                                padding: 0 !important;
                                margin: 0 !important;
                                background: white !important;
                                text-align: left !important;
                            }
                            #temp-print-area #print-area {
                                width: 100% !important;
                                display: flex !important;
                                flex-wrap: wrap !important;
                                justify-content: flex-start !important;
                                gap: ${this.printGap || 3}mm !important;
                                border: none !important;
                                background: white !important;
                                padding: 0 !important;
                                margin: 0 !important;
                                overflow: visible !important;
                                max-height: none !important;
                            }
                            .barcode-card {
                                width: var(--print-w, 50mm) !important;
                                height: var(--print-h, 25mm) !important;
                                max-width: var(--print-w, 50mm) !important;
                                max-height: var(--print-h, 25mm) !important;
                                border: none !important;
                                box-shadow: none !important;
                                padding: 0 !important;
                                margin: 0 !important;
                                display: flex !important;
                                flex-direction: column !important;
                                align-items: flex-start !important;
                                justify-content: center !important;
                                box-sizing: border-box !important;
                                background: white !important;
                                page-break-inside: avoid;
                            }
                            .barcode-card svg {
                                max-width: 100% !important;
                                max-height: 100% !important;
                                width: auto !important;
                                height: auto !important;
                                object-fit: contain !important;
                                margin: 0 !important;
                                padding: 0 !important;
                            }
                            .barcode-card .print-sku-title {
                                display: block !important;
                                font-size: 8px !important;
                                text-transform: lowercase !important;
                                margin-bottom: 0 !important;
                                line-height: 1 !important;
                                font-family: monospace !important;
                                color: black !important;
                            }
                        }
                    `;
                    document.head.appendChild(style);
                    window.print();
                    document.body.removeChild(printDiv);
                    document.head.removeChild(style);
                },
                
                openBarcodeModal(uid, productName) {
                    this.uid = uid;
                    this.productName = productName;
                    this.isOpen = true;
                    
                    // Sync settings in case they were modified elsewhere
                    this.printWidth = parseInt(getStorage('barcode_printWidth', '50')) || 50;
                    this.printHeight = parseInt(getStorage('barcode_printHeight', '25')) || 25;
                    this.format = getStorage('barcode_format', 'CODE128');
                    this.barWidth = parseInt(getStorage('barcode_barWidth', '2')) || 2;
                    this.barHeight = parseInt(getStorage('barcode_barHeight', '60')) || 60;
                    this.displayValue = getStorage('barcode_displayValue', 'true') === 'true';
                    
                    this.$nextTick(() => {
                        try {
                            JsBarcode("#modal-barcode-svg", uid, {
                                format: this.format,
                                width: this.barWidth,
                                height: this.barHeight,
                                displayValue: this.displayValue,
                                margin: 10,
                                marginTop: 2,
                                background: "#ffffff",
                                lineColor: "#000000"
                            });
                        } catch (err) {
                            console.error("Failed to render modal barcode", err);
                        }
                    });
                },
                
                closeBarcodeModal() {
                    this.isOpen = false;
                },
                
                downloadBarcode() {
                    const svg = document.getElementById("modal-barcode-svg");
                    if (!svg) return;

                    const svgString = new XMLSerializer().serializeToString(svg);
                    const svgBlob = new Blob([svgString], { type: "image/svg+xml;charset=utf-8" });
                    
                    const URL = window.URL || window.webkitURL || window;
                    const blobURL = URL.createObjectURL(svgBlob);
                    
                    const image = new Image();
                    image.onload = () => {
                        const canvas = document.createElement("canvas");
                        const bbox = svg.getBBox();
                        canvas.width = bbox.width + 20;
                        canvas.height = bbox.height + 20;
                        const context = canvas.getContext("2d");
                        
                        context.fillStyle = "#ffffff";
                        context.fillRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(image, 10, 10);
                        
                        const png = canvas.toDataURL("image/png");
                        const downloadLink = document.createElement("a");
                        downloadLink.href = png;
                        downloadLink.download = "barcode-" + this.uid.replace(/[^a-z0-9]/gi, '_').toLowerCase() + ".png";
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                    };
                    image.src = blobURL;
                },
                
                printBarcode() {
                    setStorage('barcode_printWidth', this.printWidth);
                    setStorage('barcode_printHeight', this.printHeight);
                    const card = document.getElementById("modal-barcode-print-content").cloneNode(true);
                    const printDiv = document.createElement("div");
                    printDiv.id = "temp-print-area";
                    printDiv.appendChild(card);
                    document.body.appendChild(printDiv);
                    
                    const style = document.createElement("style");
                    style.id = "temp-print-style";
                    style.innerHTML = `
                        @media print {
                            @page {
                                margin: 0;
                            }
                            body > :not(#temp-print-area) {
                                display: none !important;
                            }
                            #temp-print-area {
                                position: static !important;
                                padding: 0 !important;
                                margin: 0 !important;
                                background: white !important;
                                text-align: left !important;
                            }
                            #temp-print-area #modal-barcode-print-content {
                                width: ${this.printWidth || 50}mm !important;
                                height: ${this.printHeight || 25}mm !important;
                                max-width: ${this.printWidth || 50}mm !important;
                                max-height: ${this.printHeight || 25}mm !important;
                                border: none !important;
                                box-shadow: none !important;
                                padding: 0 !important;
                                margin: 0 !important;
                                display: flex !important;
                                flex-direction: column !important;
                                align-items: flex-start !important;
                                justify-content: center !important;
                                background: white !important;
                                box-sizing: border-box !important;
                            }
                            #temp-print-area #modal-barcode-print-content svg {
                                max-width: 100% !important;
                                max-height: 100% !important;
                                width: auto !important;
                                height: auto !important;
                                object-fit: contain !important;
                                margin: 0 !important;
                                padding: 0 !important;
                            }
                            #temp-print-area .barcode-uid-title {
                                display: none !important;
                            }
                            #temp-print-area .print-sku-title {
                                display: block !important;
                                font-size: 8px !important;
                                text-transform: lowercase !important;
                                margin-bottom: 0 !important;
                                line-height: 1 !important;
                                font-family: monospace !important;
                                color: black !important;
                            }
                        }
                    `;
                    document.head.appendChild(style);
                    
                    window.print();
                    
                    document.body.removeChild(printDiv);
                    document.head.removeChild(style);
                }
            };
        }

        // Render all inline barcodes on page load
        window.addEventListener("DOMContentLoaded", () => {
            @foreach ($dispatchItemCodes as $item)
                try {
                    JsBarcode("#inline-barcode-{{ $item->id }}", {!! json_encode($item->uid) !!}, {
                        format: "CODE128",
                        width: 1.1,
                        height: 25,
                        displayValue: false,
                        margin: 0
                    });
                } catch(e) {
                    console.error("Failed to render inline barcode for " + {!! json_encode($item->uid) !!}, e);
                }
            @endforeach

            // Physical scanner input auto-focus flow
            const scanInput = document.querySelector('input[name="scan_uid"]');
            if (scanInput) {
                // Focus on load
                scanInput.focus();

                // Prevent losing focus when clicking anywhere outside of search inputs, select boxes, inputs, buttons and textareas
                document.addEventListener('click', (e) => {
                    const ignoredSelectors = [
                        'input',
                        'select',
                        'textarea',
                        'button',
                        'a',
                        'option',
                        '[role="button"]',
                        '.ignore-scan-focus'
                    ];

                    const clickedIgnored = ignoredSelectors.some(selector => {
                        return e.target.closest(selector) !== null;
                    });

                    if (!clickedIgnored) {
                        scanInput.focus();
                    }
                });
            }
        });
    </script>
    </div> <!-- Closing Alpine x-data container -->
</x-app-layout>
