<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Purchase Master History') }}
            </h2>
            <a href="{{ route('purchases.create') }}" class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-150 flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                <span>Add Purchase</span>
            </a>
        </div>
    </x-slot>

    <div x-data="{ showExcelTools: false }" class="space-y-6 no-print">
        <!-- Search and Filters Panel -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('purchases.index') }}" class="flex-1 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Search purchases by Vendor ID or Product..." 
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
                        <a href="{{ route('purchases.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
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
                    Download all purchase records matching the current search criteria as an Excel-compatible CSV file.
                </p>
                <a href="{{ route('purchases.export', ['search' => request('search')]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-indigo-950/10">
                    Export Filtered History
                </a>
            </div>

            <!-- Import Section -->
            <div class="space-y-3">
                <h3 class="font-heading font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Purchases
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Upload an Excel/CSV file with columns: <strong>Date, Vendor ID, Quantity, Price, Product ID, SKU, Amount</strong>. Product ID or SKU is used to match products. Date format: YYYY-MM-DD.
                </p>
                <form action="{{ route('purchases.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
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
                            <th class="py-4 px-6">Date</th>
                            <th class="py-4 px-6">Product</th>
                            <th class="py-4 px-6">Vendor ID</th>
                            <th class="py-4 px-6">Quantity</th>
                            <th class="py-4 px-6">Price</th>
                            <th class="py-4 px-6">Amount</th>
                            <th class="py-4 px-6">Updated By</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($purchases as $purchase)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                <td class="py-4.5 px-6 font-semibold">{{ $purchase->id }}</td>
                                <td class="py-4.5 px-6 font-medium">{{ \Carbon\Carbon::parse($purchase->date)->format('Y-m-d') }}</td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $purchase->product->product_name ?? 'Deleted Product' }}</span>
                                        <span class="text-xs text-slate-400 font-mono">ID: {{ $purchase->product->product_id ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 font-medium">{{ $purchase->vendor_id }}</td>
                                <td class="py-4.5 px-6 font-semibold">{{ $purchase->quantity }}</td>
                                <td class="py-4.5 px-6 font-semibold">₹{{ number_format($purchase->price, 2) }}</td>
                                <td class="py-4.5 px-6 font-bold text-indigo-600 dark:text-indigo-400">₹{{ number_format($purchase->amount, 2) }}</td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs">{{ $purchase->updated_by ?? 'System' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $purchase->updated_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('purchases.destroy', $purchase->id) }}" onsubmit="return confirm('Are you sure you want to delete this purchase record?');" class="inline">
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
                                <td colspan="9" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    No purchase history registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($purchases->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/80">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </div>

    @if (session('new_purchase_uids'))
        <!-- CDN scripts loaded securely -->
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

        <!-- Barcode View Modal -->
        <div x-data="purchaseBarcodeModalApp()" x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" x-cloak>
            <div @click.away="closeModal()" class="w-full max-w-5xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col md:flex-row gap-8 max-h-[90vh] overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                
                <!-- Left panel: config options -->
                <div class="w-full md:w-80 flex-shrink-0 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-800/80 pb-6 md:pb-0 md:pr-8">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading">Print Purchase Barcodes</h3>
                            <p class="text-xs text-slate-400 mt-1">Generated for: <span class="font-semibold text-slate-600 dark:text-slate-300">{{ session('new_purchase_product_name') }}</span></p>
                        </div>

                        <!-- Styling Options -->
                        <div class="space-y-4">
                            <!-- Format -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Format</label>
                                <select 
                                    x-model="format"
                                    @change="updateAndRender()"
                                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all"
                                >
                                    <option value="CODE128">Code 128</option>
                                    <option value="CODE39">Code 39</option>
                                    <option value="EAN13">EAN 13</option>
                                    <option value="EAN8">EAN 8</option>
                                </select>
                            </div>

                            <!-- Width Slider -->
                            <div>
                                <div class="flex justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">
                                    <span>Line Width</span>
                                    <span x-text="barWidth"></span>
                                </div>
                                <input 
                                    type="range" 
                                    min="1" 
                                    max="4" 
                                    step="1"
                                    x-model.number="barWidth"
                                    @input="updateAndRender()"
                                    class="w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                >
                            </div>

                            <!-- Height Slider -->
                            <div>
                                <div class="flex justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">
                                    <span>Height (px)</span>
                                    <span x-text="barHeight + 'px'"></span>
                                </div>
                                <input 
                                    type="range" 
                                    min="30" 
                                    max="150" 
                                    step="5"
                                    x-model.number="barHeight"
                                    @input="updateAndRender()"
                                    class="w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                >
                            </div>

                            <!-- Display text check -->
                            <div class="flex items-center gap-3.5">
                                <input 
                                    type="checkbox" 
                                    id="modalDisplayValue" 
                                    x-model="displayValue"
                                    @change="updateAndRender()"
                                    class="w-4 h-4 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded focus:ring-indigo-500 focus:ring-2"
                                >
                                <label for="modalDisplayValue" class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 cursor-pointer select-none">Show Serial Text</label>
                            </div>

                            <!-- Print Dimensions (mm) -->
                            <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 space-y-3">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Print Dimensions (mm)</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-[9px] text-slate-400 dark:text-slate-500 mb-1">Width (mm)</label>
                                        <input 
                                            type="number" 
                                            min="10" 
                                            max="300" 
                                            x-model.number="printWidth"
                                            @input="updateAndRender()"
                                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-[9px] text-slate-400 dark:text-slate-500 mb-1">Height (mm)</label>
                                        <input 
                                            type="number" 
                                            min="10" 
                                            max="300" 
                                            x-model.number="printHeight"
                                            @input="updateAndRender()"
                                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-[9px] text-slate-400 dark:text-slate-500 mb-1">Gap (mm)</label>
                                        <input 
                                            type="number" 
                                            min="0" 
                                            max="50" 
                                            x-model.number="printGap"
                                            @input="updateAndRender()"
                                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-6 md:pt-0">
                        <button @click="printBarcodes()" class="w-full py-3 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-xs font-bold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Sheet</span>
                        </button>
                        <button @click="closeModal()" class="w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-center">
                            Close
                        </button>
                    </div>
                </div>

                <!-- Right panel: preview grid -->
                <div class="flex-1 flex flex-col min-h-0">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white font-heading">Barcode Sheet Preview</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5">Real-time rendered vector labels ready for printing</p>
                        </div>
                    </div>

                    <!-- Barcode Grid Container (The Target of printing) -->
                    <div id="modal-print-area" :style="'gap: ' + printGap + 'mm !important;'" class="flex-1 flex flex-wrap gap-4 justify-center items-start overflow-y-auto p-4 border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl bg-slate-50 dark:bg-slate-950/40 min-h-[300px]">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="modal-barcode-card p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center shadow-sm relative group shrink-0">
                                <!-- Label top info -->
                                <div class="label-title text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5" x-text="'Label #' + (index + 1)"></div>
                                
                                <!-- SKU/UID top text -->
                                <div class="print-sku-title text-[8px] text-slate-500 dark:text-slate-400 font-mono mb-1 text-center" x-text="productName.toLowerCase()"></div>
                                
                                <!-- SVG Barcode Container -->
                                <svg :id="'modal-barcode-' + index" class="barcode-svg max-w-full bg-white p-1 rounded"></svg>

                                <!-- Individual Card Action Overlay -->
                                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 rounded-2xl flex items-center justify-center gap-2 transition-all duration-200 backdrop-blur-[1px]">
                                    <button 
                                        @click="downloadBarcode(index, item)"
                                        class="p-2 bg-white text-slate-900 hover:bg-slate-100 rounded-lg shadow text-[10px] font-semibold flex items-center gap-1 transition-all"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Download</span>
                                    </button>
                                    <button 
                                        @click="printSingleBarcode(index)"
                                        class="p-2 bg-white text-slate-900 hover:bg-slate-100 rounded-lg shadow text-[10px] font-semibold flex items-center gap-1 transition-all"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                                        <span>Print</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <!-- Script Block for the Modal Logic -->
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

            function purchaseBarcodeModalApp() {
                return {
                    isOpen: true,
                    items: @json(session('new_purchase_uids')),
                    productName: @json(session('new_purchase_product_name')),
                    format: getStorage('barcode_format', 'CODE128'),
                    barWidth: parseInt(getStorage('barcode_barWidth', '2')) || 2,
                    barHeight: parseInt(getStorage('barcode_barHeight', '60')) || 60,
                    displayValue: getStorage('barcode_displayValue', 'true') === 'true',
                    printWidth: parseInt(getStorage('barcode_printWidth', '50')) || 50,
                    printHeight: parseInt(getStorage('barcode_printHeight', '25')) || 25,
                    printGap: parseInt(getStorage('barcode_printGap', '3')) || 3,

                    init() {
                        this.updateAndRender();
                    },

                    closeModal() {
                        this.isOpen = false;
                    },

                    updateAndRender() {
                        // Persist immediately on input change
                        setStorage('barcode_format', this.format);
                        setStorage('barcode_barWidth', this.barWidth);
                        setStorage('barcode_barHeight', this.barHeight);
                        setStorage('barcode_displayValue', this.displayValue);
                        setStorage('barcode_printWidth', this.printWidth);
                        setStorage('barcode_printHeight', this.printHeight);
                        setStorage('barcode_printGap', this.printGap);

                        setTimeout(() => {
                            this.renderBarcodes();
                        }, 0);
                    },

                    renderBarcodes() {
                        this.items.forEach((text, index) => {
                            try {
                                JsBarcode("#modal-barcode-" + index, text, {
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
                                console.error("Barcode rendering failed for " + text, err);
                            }
                        });
                    },

                    downloadBarcode(index, text) {
                        const svg = document.getElementById("modal-barcode-" + index);
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
                            downloadLink.download = "barcode-" + text.replace(/[^a-z0-9]/gi, '_').toLowerCase() + ".png";
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);
                        };
                        image.src = blobURL;
                    },

                    printBarcodes() {
                        const area = document.getElementById("modal-print-area").cloneNode(true);
                        const printDiv = document.createElement("div");
                        printDiv.id = "temp-print-area";
                        printDiv.appendChild(area);
                        document.body.appendChild(printDiv);

                        const style = document.createElement("style");
                        style.id = "modal-temp-print-style";
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
                                #temp-print-area #modal-print-area {
                                    width: 100% !important;
                                    display: flex !important;
                                    flex-wrap: wrap !important;
                                    justify-content: flex-start !important;
                                    gap: ${this.printGap || 3}mm !important;
                                    border: none !important;
                                    padding: 0 !important;
                                    margin: 0 !important;
                                    overflow: visible !important;
                                    max-height: none !important;
                                }
                                .modal-barcode-card {
                                    width: ${this.printWidth}mm !important;
                                    height: ${this.printHeight}mm !important;
                                    max-width: ${this.printWidth}mm !important;
                                    max-height: ${this.printHeight}mm !important;
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
                                .modal-barcode-card svg {
                                    max-width: 100% !important;
                                    max-height: 100% !important;
                                    width: auto !important;
                                    height: auto !important;
                                    object-fit: contain !important;
                                    margin: 0 !important;
                                    padding: 0 !important;
                                }
                                .modal-barcode-card .label-title {
                                    display: none !important;
                                }
                                .modal-barcode-card .print-sku-title {
                                    display: block !important;
                                    font-size: 8px !important;
                                    text-transform: lowercase !important;
                                    margin-bottom: 0 !important;
                                    line-height: 1 !important;
                                    font-family: monospace !important;
                                    color: black !important;
                                }
                                .group-hover\\:opacity-100 {
                                    display: none !important;
                                }
                            }
                        `;
                        document.head.appendChild(style);
                        window.print();
                        document.body.removeChild(printDiv);
                        document.head.removeChild(style);
                    },

                    printSingleBarcode(index) {
                        const card = document.getElementById("modal-barcode-" + index).parentElement.cloneNode(true);
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
                                #temp-print-area .modal-barcode-card {
                                    width: ${this.printWidth}mm !important;
                                    height: ${this.printHeight}mm !important;
                                    max-width: ${this.printWidth}mm !important;
                                    max-height: ${this.printHeight}mm !important;
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
                                }
                                #temp-print-area .modal-barcode-card svg {
                                    max-width: 100% !important;
                                    max-height: 100% !important;
                                    width: auto !important;
                                    height: auto !important;
                                    object-fit: contain !important;
                                    margin: 0 !important;
                                    padding: 0 !important;
                                }
                                #temp-print-area .label-title {
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
                                .group-hover\\:opacity-100 {
                                    display: none !important;
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
        </script>
    @endif
</x-app-layout>
