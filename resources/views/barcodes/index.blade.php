<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Barcode Generator') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="barcodeApp()" :style="'--print-w: ' + printWidth + 'mm; --print-h: ' + printHeight + 'mm;'">
        <!-- Left Panel: Configuration Form -->
        <div class="lg:col-span-4 space-y-6 no-print">
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Generator Mode</h3>
                    <span 
                        x-show="!isEditing" 
                        x-cloak
                        class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-slate-200/80 dark:border-slate-700/30 flex items-center gap-1"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        <span>Locked</span>
                    </span>
                    <span 
                        x-show="isEditing" 
                        x-cloak
                        class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-amber-100 dark:border-amber-900/40 flex items-center gap-1 animate-pulse"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>Editing</span>
                    </span>
                </div>

                
                <!-- Mode Toggle -->
                <div class="flex p-1 bg-slate-100 dark:bg-slate-950/60 rounded-2xl mb-6" :class="{'opacity-75 cursor-not-allowed': !isEditing}">
                    <button 
                        type="button"
                        @click="if (isEditing) { mode = 'manual'; updateAndRender(); }"
                        :disabled="!isEditing"
                        :class="mode === 'manual' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all disabled:pointer-events-none"
                    >
                        Manual Input
                    </button>
                    <button 
                        type="button"
                        @click="if (isEditing) { mode = 'sequence'; updateAndRender(); }"
                        :disabled="!isEditing"
                        :class="mode === 'sequence' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all disabled:pointer-events-none"
                    >
                        Bulk Sequence
                    </button>
                </div>
                
                <!-- Input Fields -->
                <div class="space-y-4">
                    <!-- Manual Mode Fields -->
                    <div x-show="mode === 'manual'" x-cloak>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Barcode Text / UIDs</label>
                        <textarea 
                            x-model="manualText"
                            :disabled="!isEditing"
                            @input.debounce.250ms="updateAndRender()"
                            placeholder="Enter one text per line..."
                            rows="5"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                        ></textarea>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Type multiple items separated by new lines.</p>
                    </div>

                    <!-- Sequence Mode Fields -->
                    <div x-show="mode === 'sequence'" x-cloak class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Starting UID</label>
                            <input 
                                type="text" 
                                x-model="startUid"
                                :disabled="!isEditing"
                                @input.debounce.250ms="updateAndRender()"
                                placeholder="e.g., Zig0001"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Quantity</label>
                            <input 
                                type="number" 
                                x-model.number="quantity"
                                :disabled="!isEditing"
                                @input="updateAndRender()"
                                min="1"
                                max="100"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Generates up to 100 codes at a time.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customizations Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 shadow-sm space-y-5">
                <h3 class="text-base font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Styling Options</h3>
                
                <!-- Format -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Barcode Format</label>
                    <select 
                        x-model="format"
                        :disabled="!isEditing"
                        @change="updateAndRender()"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <option value="CODE128">Code 128 (Recommended)</option>
                        <option value="CODE39">Code 39</option>
                        <option value="EAN13">EAN 13</option>
                        <option value="EAN8">EAN 8</option>
                    </select>
                </div>
 
                <!-- Width Slider -->
                <div>
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">
                        <span>Line Width</span>
                        <span x-text="barWidth"></span>
                    </div>
                    <input 
                        type="range" 
                        min="1" 
                        max="4" 
                        step="1"
                        x-model.number="barWidth"
                        :disabled="!isEditing"
                        @input="updateAndRender()"
                        class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                </div>
 
                <!-- Height Slider -->
                <div>
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">
                        <span>Barcode Height (px)</span>
                        <span x-text="barHeight + 'px'"></span>
                    </div>
                    <input 
                        type="range" 
                        min="30" 
                        max="150" 
                        step="5"
                        x-model.number="barHeight"
                        :disabled="!isEditing"
                        @input="updateAndRender()"
                        class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                </div>
 
                <!-- Display Value checkbox -->
                <div class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        id="displayValue" 
                        x-model="displayValue"
                        :disabled="!isEditing"
                        @change="updateAndRender()"
                        class="w-5 h-5 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded-lg focus:ring-indigo-500 focus:ring-2 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                    <label for="displayValue" class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 cursor-pointer select-none">Display Text below Barcode</label>
                </div>



                <!-- Print Dimensions (mm) -->
                <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Print Dimensions (mm)</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Width (mm)</label>
                            <input 
                                type="number" 
                                min="10" 
                                max="300" 
                                x-model.number="printWidth"
                                :disabled="!isEditing"
                                @input="updateAndRender()"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-[10px] focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                                placeholder="50"
                            >
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Height (mm)</label>
                            <input 
                                type="number" 
                                min="10" 
                                max="300" 
                                x-model.number="printHeight"
                                :disabled="!isEditing"
                                @input="updateAndRender()"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-[10px] focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                                placeholder="25"
                            >
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Gap (mm)</label>
                            <input 
                                type="number" 
                                min="0" 
                                max="50" 
                                x-model.number="printGap"
                                :disabled="!isEditing"
                                @input="updateAndRender()"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-[10px] focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                                placeholder="3"
                            >
                        </div>
                    </div>
                </div>

                <!-- Lock / Unlock Settings Controls -->
                <div class="border-t border-slate-100 dark:border-slate-800/50 pt-5 mt-2">
                    <button 
                        type="button" 
                        @click="isEditing = true"
                        x-show="!isEditing"
                        x-cloak
                        class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-2xl transition-all shadow-md shadow-indigo-900/10 flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Update Configuration</span>
                    </button>
                    <div x-show="isEditing" x-cloak class="flex gap-3">
                        <button 
                            type="button" 
                            @click="saveConfiguration()"
                            class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-2xl transition-all shadow-md shadow-emerald-900/10 flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Changes</span>
                        </button>
                        <button 
                            type="button" 
                            @click="cancelConfiguration()"
                            class="px-4 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-2xl transition-all"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Barcodes Preview & Actions -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/50 mb-6 shrink-0 no-print">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Barcodes Sheet Preview</h3>
                        <p class="text-xs text-slate-400 mt-1">Real-time rendered vector labels</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button 
                            @click="printBarcodes()"
                            :disabled="items.length === 0"
                            class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all flex items-center gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Sheet</span>
                        </button>
                    </div>
                </div>

                <!-- Barcode Grid Container (The Target of printing) -->
                <div id="print-area" :style="'gap: ' + printGap + 'mm !important;'" class="flex-1 flex flex-wrap gap-5 justify-center items-start overflow-y-auto p-4 max-h-[600px] border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl bg-slate-50 dark:bg-slate-950/40">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="barcode-card p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center shadow-sm relative group shrink-0">
                            <!-- Label top info -->
                            <div class="label-title text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2" x-text="'Label #' + (index + 1)"></div>
                            
                            <!-- SKU/UID top text -->
                            <div class="print-sku-title text-base font-black text-slate-800 dark:text-slate-200 font-mono mb-1 text-center tracking-wide" x-text="(uidSkuMap[item.toLowerCase()] || item).toUpperCase()"></div>
                            
                            <!-- SVG Barcode Container -->
                            <svg :id="'barcode-' + index" class="barcode-svg max-w-full bg-white p-1 rounded"></svg>

                            <!-- Individual Card Action Overlay -->
                            <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 rounded-2xl flex items-center justify-center gap-2.5 transition-all duration-200 backdrop-blur-[1px]">
                                <button 
                                    @click="downloadBarcode(index, item)"
                                    class="p-2.5 bg-white text-slate-900 hover:bg-slate-100 rounded-xl shadow text-xs font-semibold flex items-center gap-1.5 transition-all"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Download</span>
                                </button>
                                <button 
                                    @click="printSingleBarcode(index)"
                                    class="p-2.5 bg-white text-slate-900 hover:bg-slate-100 rounded-xl shadow text-xs font-semibold flex items-center gap-1.5 transition-all"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                                    <span>Print</span>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="items.length === 0" class="m-auto text-center py-16 space-y-3">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900 rounded-3xl flex items-center justify-center mx-auto text-slate-400 dark:text-slate-600 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.25c0-.414.336-.75.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">No Barcodes Rendered</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Select a mode and enter codes on the left configurations panel to visualize sticker labels.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CDN scripts loaded securely -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    
    <!-- Alpine Javascript Logic -->
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

        function barcodeApp() {
            return {
                isEditing: false,
                mode: getStorage('barcode_mode', 'manual') === 'sequence' ? 'sequence' : 'manual',
                manualText: getStorage('barcode_manualText', 'Zig0001\nZig0002'),
                startUid: getStorage('barcode_startUid', 'Zig0010'),
                quantity: parseInt(getStorage('barcode_quantity', '5')) || 5,
                format: getStorage('barcode_format', 'CODE128'),
                barWidth: parseInt(getStorage('barcode_barWidth', '2')) || 2,
                barHeight: parseInt(getStorage('barcode_barHeight', '60')) || 60,
                displayValue: getStorage('barcode_displayValue', 'true') === 'true',
                printWidth: parseInt(getStorage('barcode_printWidth', '50')) || 50,
                printHeight: parseInt(getStorage('barcode_printHeight', '25')) || 25,
                printGap: parseInt(getStorage('barcode_printGap', '3')) || 3,

                uidSkuMap: {!! json_encode($uidSkuMap) !!},
                items: [],

                init() {
                    this.updateAndRender();
                },

                updateAndRender() {
                    this.generateItems();

                    setTimeout(() => {
                        this.renderActiveBarcodes();
                    }, 0);
                },

                saveConfiguration() {
                    setStorage('barcode_mode', this.mode);
                    setStorage('barcode_manualText', this.manualText);
                    setStorage('barcode_startUid', this.startUid);
                    setStorage('barcode_quantity', this.quantity);
                    setStorage('barcode_format', this.format);
                    setStorage('barcode_barWidth', this.barWidth);
                    setStorage('barcode_barHeight', this.barHeight);
                    setStorage('barcode_displayValue', this.displayValue);
                    setStorage('barcode_printWidth', this.printWidth);
                    setStorage('barcode_printHeight', this.printHeight);
                    setStorage('barcode_printGap', this.printGap);

                    this.isEditing = false;
                },

                cancelConfiguration() {
                    this.mode = getStorage('barcode_mode', 'manual') === 'sequence' ? 'sequence' : 'manual';
                    this.manualText = getStorage('barcode_manualText', 'Zig0001\nZig0002');
                    this.startUid = getStorage('barcode_startUid', 'Zig0010');
                    this.quantity = parseInt(getStorage('barcode_quantity', '5')) || 5;
                    this.format = getStorage('barcode_format', 'CODE128');
                    this.barWidth = parseInt(getStorage('barcode_barWidth', '2')) || 2;
                    this.barHeight = parseInt(getStorage('barcode_barHeight', '60')) || 60;
                    this.displayValue = getStorage('barcode_displayValue', 'true') === 'true';
                    this.printWidth = parseInt(getStorage('barcode_printWidth', '50')) || 50;
                    this.printHeight = parseInt(getStorage('barcode_printHeight', '25')) || 25;
                    this.printGap = parseInt(getStorage('barcode_printGap', '3')) || 3;

                    this.updateAndRender();
                    this.isEditing = false;
                },

                generateItems() {
                    this.items = [];
                    let list = [];

                    if (this.mode === 'manual') {
                        list = this.manualText.split('\n')
                            .map(line => line.trim())
                            .filter(line => line.length > 0);
                    } else {
                        const qty = Math.min(Math.max(parseInt(this.quantity) || 1, 1), 100);
                        const start = this.startUid.trim();
                        if (start) {
                            const match = start.match(/^(.*?)(\d+)$/);
                            if (match) {
                                const prefix = match[1];
                                const numberStr = match[2];
                                const startNum = parseInt(numberStr);
                                const padLength = numberStr.length;
                                for (let i = 0; i < qty; i++) {
                                    const currentNum = startNum + i;
                                    const currentNumStr = String(currentNum).padStart(padLength, '0');
                                    list.push(prefix + currentNumStr);
                                }
                            } else {
                                list.push(start);
                            }
                        }
                    }

                    this.items = list;
                },

                renderActiveBarcodes() {
                    this.items.forEach((text, index) => {
                        try {
                            JsBarcode("#barcode-" + index, text, {
                                format: this.format,
                                width: this.barWidth,
                                height: this.barHeight,
                                displayValue: this.displayValue,
                                fontSize: 24,
                                fontOptions: "bold",
                                textMargin: 4,
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
                    const svg = document.getElementById("barcode-" + index);
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
                    const area = document.getElementById("print-area").cloneNode(true);
                    const printDiv = document.createElement("div");
                    printDiv.id = "temp-print-area";
                    printDiv.style.setProperty('--print-w', `${this.printWidth || 50}mm`);
                    printDiv.style.setProperty('--print-h', `${this.printHeight || 25}mm`);
                    printDiv.appendChild(area);
                    document.body.appendChild(printDiv);
                    
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
                                align-items: center !important;
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
                            .barcode-card .label-title {
                                display: none !important;
                            }
                            .barcode-card .print-sku-title {
                                display: block !important;
                                font-size: 14px !important;
                                font-weight: 900 !important;
                                text-transform: uppercase !important;
                                text-align: center !important;
                                width: 100% !important;
                                margin-bottom: 3px !important;
                                line-height: 1.1 !important;
                                font-family: monospace !important;
                                color: black !important;
                                letter-spacing: 0.05em !important;
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
                    const card = document.getElementById("barcode-" + index).parentElement.cloneNode(true);
                    const printDiv = document.createElement("div");
                    printDiv.id = "temp-print-area";
                    printDiv.style.setProperty('--print-w', `${this.printWidth || 50}mm`);
                    printDiv.style.setProperty('--print-h', `${this.printHeight || 25}mm`);
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
                             #temp-print-area .barcode-card {
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
                                 align-items: center !important;
                                 justify-content: center !important;
                                 box-sizing: border-box !important;
                                 background: white !important;
                             }
                            #temp-print-area .barcode-card svg {
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
                                font-size: 14px !important;
                                font-weight: 900 !important;
                                text-transform: uppercase !important;
                                text-align: center !important;
                                width: 100% !important;
                                margin-bottom: 3px !important;
                                line-height: 1.1 !important;
                                font-family: monospace !important;
                                color: black !important;
                                letter-spacing: 0.05em !important;
                            }
                            .group {
                                border: none !important;
                                box-shadow: none !important;
                            }
                            .group button, .group div:first-child {
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

        // Component data is returned directly by the global barcodeApp function
    </script>
</x-app-layout>
