<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Barcode Generator') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="barcodeApp()" :style="`--print-w: ${activePrintWidth}mm; --print-h: ${activePrintHeight}mm;`">
        <!-- Left Panel: Configuration Form -->
        <div class="lg:col-span-4 space-y-6 no-print">
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Generator Mode</h3>
                    <!-- Locked / Editable Status Badge -->
                    <span x-show="!isEditing" class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-md">Locked</span>
                    <span x-show="isEditing" class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md animate-pulse">Editing</span>
                </div>
                
                <!-- Mode Toggle -->
                <div class="flex p-1 bg-slate-100 dark:bg-slate-950/60 rounded-2xl mb-6" :class="{'opacity-75 cursor-not-allowed': !isEditing}">
                    <button 
                        type="button"
                        @click="if (isEditing) { mode = 'manual'; }"
                        :disabled="!isEditing"
                        :class="mode === 'manual' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        Manual Input
                    </button>
                    <button 
                        type="button"
                        @click="if (isEditing) { mode = 'sequence'; }"
                        :disabled="!isEditing"
                        :class="mode === 'sequence' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        Bulk Sequence
                    </button>
                </div>

                <!-- Input Fields -->
                <div class="space-y-4">
                    <!-- Manual Mode Fields -->
                    <template x-if="mode === 'manual'">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Barcode Text / UIDs</label>
                            <textarea 
                                x-model="manualText"
                                :disabled="!isEditing"
                                placeholder="Enter one text per line..."
                                rows="5"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                            ></textarea>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Type multiple items separated by new lines.</p>
                        </div>
                    </template>

                    <!-- Sequence Mode Fields -->
                    <template x-if="mode === 'sequence'">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Starting UID</label>
                                <input 
                                    type="text" 
                                    x-model="startUid"
                                    :disabled="!isEditing"
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
                                    min="1"
                                    max="100"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                                >
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Generates up to 100 codes at a time.</p>
                            </div>
                        </div>
                    </template>
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
                        class="w-5 h-5 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded-lg focus:ring-indigo-500 focus:ring-2 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                    <label for="displayValue" class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 cursor-pointer select-none">Display Text below Barcode</label>
                </div>



                <!-- Print Dimensions (mm) -->
                <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Print Dimensions (mm)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Width (mm)</label>
                            <input 
                                type="number" 
                                min="10" 
                                max="300" 
                                x-model.number="printWidth"
                                :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                                placeholder="50"
                            >
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Height (mm)</label>
                            <input 
                                type="number" 
                                min="10" 
                                max="300" 
                                x-model.number="printHeight"
                                :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono disabled:opacity-60 disabled:cursor-not-allowed"
                                placeholder="25"
                            >
                        </div>
                    </div>
                </div>

                <!-- Configuration Actions -->
                <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 flex items-center justify-between w-full gap-3">
                    <span x-show="settingsSaved && !isEditing" x-transition class="text-[10px] text-emerald-500 font-semibold" style="display: none;">Settings saved & applied!</span>
                    
                    <!-- Locked Mode Buttons -->
                    <div x-show="!isEditing" class="ml-auto">
                        <button 
                            type="button"
                            @click="isEditing = true"
                            class="px-4 py-2 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all shadow-sm flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit Settings</span>
                        </button>
                    </div>

                    <!-- Editing Mode Buttons -->
                    <div x-show="isEditing" class="flex items-center justify-between w-full">
                        <button 
                            type="button"
                            @click="cancelEdit()"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shadow-sm"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button"
                            @click="saveSettings()"
                            class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-500 transition-all shadow-sm flex items-center gap-1.5 shadow-emerald-900/10"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Settings</span>
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
                <div id="print-area" class="flex-1 flex flex-wrap gap-5 justify-center items-start overflow-y-auto p-4 max-h-[600px] border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl bg-slate-50 dark:bg-slate-950/40">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="barcode-card p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center shadow-sm relative group shrink-0">
                            <!-- Label top info -->
                            <div class="label-title text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2" x-text="'Label #' + (index + 1)"></div>
                            
                            <!-- SKU/UID top text -->
                            <div class="print-sku-title text-[9px] text-slate-400 dark:text-slate-500 font-mono mb-1 text-center hidden" x-text="(uidSkuMap[item.toLowerCase()] || item).toLowerCase()"></div>
                            
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
        function barcodeApp() {
            return {
                // Form State (bound to UI controls via x-model)
                mode: localStorage.getItem('barcode_mode') || 'manual',
                manualText: localStorage.getItem('barcode_manualText') !== null ? localStorage.getItem('barcode_manualText') : 'Zig0001\nZig0002',
                startUid: localStorage.getItem('barcode_startUid') || 'Zig0010',
                quantity: localStorage.getItem('barcode_quantity') !== null ? parseInt(localStorage.getItem('barcode_quantity')) : 5,
                format: localStorage.getItem('barcode_format') || 'CODE128',
                barWidth: parseInt(localStorage.getItem('barcode_barWidth')) || 2,
                barHeight: parseInt(localStorage.getItem('barcode_barHeight')) || 60,
                displayValue: localStorage.getItem('barcode_displayValue') === null ? true : (localStorage.getItem('barcode_displayValue') === 'true'),
                printWidth: parseInt(localStorage.getItem('barcode_printWidth')) || 50,
                printHeight: parseInt(localStorage.getItem('barcode_printHeight')) || 25,

                // Active State (used for rendering preview and styling/printing)
                activeMode: 'manual',
                activeManualText: 'Zig0001\nZig0002',
                activeStartUid: 'Zig0010',
                activeQuantity: 5,
                activeFormat: 'CODE128',
                activeBarWidth: 2,
                activeBarHeight: 60,
                activeDisplayValue: true,
                activePrintWidth: 50,
                activePrintHeight: 25,

                // Edit State
                isEditing: false,

                uidSkuMap: {!! json_encode($uidSkuMap) !!},
                items: [],
                settingsSaved: false,

                // Load initial settings and trigger rendering
                init() {
                    this.applyState();
                    
                    this.$nextTick(() => {
                        this.renderActiveBarcodes();
                    });
                },

                // Copy form state to active state
                applyState() {
                    this.activeMode = this.mode;
                    this.activeManualText = this.manualText;
                    this.activeStartUid = this.startUid;
                    this.activeQuantity = this.quantity;
                    this.activeFormat = this.format;
                    this.activeBarWidth = this.barWidth;
                    this.activeBarHeight = this.barHeight;
                    this.activeDisplayValue = this.displayValue;
                    this.activePrintWidth = this.printWidth;
                    this.activePrintHeight = this.printHeight;
                    
                    // Generate list of items based on active state
                    this.generateItems();
                },

                // Save current form state to localStorage, update active state, and re-lock
                saveSettings() {
                    // Persist to localStorage
                    localStorage.setItem('barcode_mode', this.mode);
                    localStorage.setItem('barcode_manualText', this.manualText);
                    localStorage.setItem('barcode_startUid', this.startUid);
                    localStorage.setItem('barcode_quantity', this.quantity);
                    localStorage.setItem('barcode_format', this.format);
                    localStorage.setItem('barcode_barWidth', this.barWidth);
                    localStorage.setItem('barcode_barHeight', this.barHeight);
                    localStorage.setItem('barcode_displayValue', this.displayValue);
                    localStorage.setItem('barcode_printWidth', this.printWidth);
                    localStorage.setItem('barcode_printHeight', this.printHeight);

                    // Update active state
                    this.applyState();

                    // Re-render
                    this.$nextTick(() => {
                        this.renderActiveBarcodes();
                    });

                    // Lock inputs
                    this.isEditing = false;

                    this.settingsSaved = true;
                    setTimeout(() => {
                        this.settingsSaved = false;
                    }, 2000);
                },

                // Revert form state back to active state and re-lock
                cancelEdit() {
                    this.mode = this.activeMode;
                    this.manualText = this.activeManualText;
                    this.startUid = this.activeStartUid;
                    this.quantity = this.activeQuantity;
                    this.format = this.activeFormat;
                    this.barWidth = this.activeBarWidth;
                    this.barHeight = this.activeBarHeight;
                    this.displayValue = this.activeDisplayValue;
                    this.printWidth = this.activePrintWidth;
                    this.printHeight = this.activePrintHeight;
                    
                    // Re-lock
                    this.isEditing = false;
                },

                generateItems() {
                    this.items = [];
                    let list = [];

                    if (this.activeMode === 'manual') {
                        list = this.activeManualText.split('\n')
                            .map(line => line.trim())
                            .filter(line => line.length > 0);
                    } else {
                        const qty = Math.min(Math.max(parseInt(this.activeQuantity) || 1, 1), 100);
                        const start = this.activeStartUid.trim();
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
                                format: this.activeFormat,
                                width: this.activeBarWidth,
                                height: this.activeBarHeight,
                                displayValue: this.activeDisplayValue,
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
                        
                        // Set white background
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
                    window.print();
                },

                printSingleBarcode(index) {
                    const card = document.getElementById("barcode-" + index).parentElement.cloneNode(true);
                    
                    // Create temporary print container
                    const printDiv = document.createElement("div");
                    printDiv.id = "temp-print-area";
                    printDiv.style.setProperty('--print-w', `${this.activePrintWidth || 50}mm`);
                    printDiv.style.setProperty('--print-h', `${this.activePrintHeight || 25}mm`);
                    printDiv.appendChild(card);
                    document.body.appendChild(printDiv);
                    
                    // Style sheet overrides for clean label sizes
                    const style = document.createElement("style");
                    style.id = "temp-print-style";
                    style.innerHTML = `
                        @media print {
                            body * {
                                visibility: hidden;
                            }
                            #temp-print-area, #temp-print-area * {
                                visibility: visible;
                            }
                            #temp-print-area {
                                position: absolute;
                                left: 50%;
                                top: 50%;
                                transform: translate(-50%, -50%);
                                padding: 0 !important;
                                background: white !important;
                                text-align: center;
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
                                font-size: 8px !important;
                                text-transform: lowercase !important;
                                margin-bottom: 0 !important;
                                line-height: 1 !important;
                                font-family: monospace !important;
                                color: black !important;
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
                    
                    // Cleanup
                    document.body.removeChild(printDiv);
                    document.head.removeChild(style);
                }
            };
        }
    </script>

    <!-- Printing CSS adjustments -->
    <style>
        @media print {
            /* Hide layout items and no-print elements completely from DOM flow */
            aside, header, .no-print {
                display: none !important;
            }
            
            /* Reset layout wrappers to collapse layout height and prevent extra pages */
            html, body, .min-h-screen, .flex-1, main, .grid {
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
            }
            
            body > div > div {
                padding-left: 0 !important;
            }
            
            main {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            .grid {
                display: block !important;
            }
            
            .lg\:col-span-8 {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .lg\:col-span-8 > div {
                border: none !important;
                padding: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            /* Make only the print area visible */
            body * {
                visibility: hidden;
            }
            
            #print-area, #print-area * {
                visibility: visible;
            }
            
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 15px !important;
                border: none !important;
                overflow: visible !important;
                max-h-none !important;
            }
            
            .barcode-card {
                border: none !important;
                box-shadow: none !important;
                background: white !important;
                color: black !important;
                page-break-inside: avoid;
                padding: 0 !important;
                margin: 0 !important;
                width: var(--print-w, 50mm) !important;
                height: var(--print-h, 25mm) !important;
                max-width: var(--print-w, 50mm) !important;
                max-height: var(--print-h, 25mm) !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                box-sizing: border-box !important;
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
                font-size: 8px !important;
                text-transform: lowercase !important;
                margin-bottom: 0 !important;
                line-height: 1 !important;
                font-family: monospace !important;
                color: #000000 !important;
            }
            
            /* Hide the individual download hover overlays in print */
            .barcode-card .group-hover\:opacity-100 {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
