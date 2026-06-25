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
                    <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md">Live Preview</span>
                </div>
                
                <!-- Mode Toggle -->
                <div class="flex p-1 bg-slate-100 dark:bg-slate-950/60 rounded-2xl mb-6">
                    <button 
                        type="button"
                        @click="mode = 'manual'; updateAndRender();"
                        :class="mode === 'manual' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all"
                    >
                        Manual Input
                    </button>
                    <button 
                        type="button"
                        @click="mode = 'sequence'; updateAndRender();"
                        :class="mode === 'sequence' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all"
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
                                @input.debounce.250ms="updateAndRender()"
                                placeholder="Enter one text per line..."
                                rows="5"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 transition-all font-mono"
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
                                    @input.debounce.250ms="updateAndRender()"
                                    placeholder="e.g., Zig0001"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 transition-all font-mono"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Quantity</label>
                                <input 
                                    type="number" 
                                    x-model.number="quantity"
                                    @input="updateAndRender()"
                                    min="1"
                                    max="100"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition-all"
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
                        @change="updateAndRender()"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition-all"
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
                        @input="updateAndRender()"
                        class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600"
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
                        @input="updateAndRender()"
                        class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                    >
                </div>

                <!-- Display Value checkbox -->
                <div class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        id="displayValue" 
                        x-model="displayValue"
                        @change="updateAndRender()"
                        class="w-5 h-5 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded-lg focus:ring-indigo-500 focus:ring-2"
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
                                @input="updateAndRender()"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
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
                                @input="updateAndRender()"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all font-mono"
                                placeholder="25"
                            >
                        </div>
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
                            <div class="print-sku-title text-[9px] text-slate-500 dark:text-slate-400 font-mono mb-1 text-center" x-text="uidSkuMap[item.toLowerCase()] || item"></div>
                            
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

                uidSkuMap: {!! json_encode($uidSkuMap) !!},
                items: [],

                init() {
                    this.updateAndRender();
                },

                updateAndRender() {
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

                    this.generateItems();

                    setTimeout(() => {
                        this.renderActiveBarcodes();
                    }, 0);
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
                    window.print();
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
                    
                    document.body.removeChild(printDiv);
                    document.head.removeChild(style);
                }
            };
        }

        // Component data is returned directly by the global barcodeApp function
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
