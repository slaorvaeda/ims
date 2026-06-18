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
        <!-- Search Panel -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <form method="GET" action="{{ route('dispatch-item-codes.index') }}" class="flex flex-col sm:flex-row gap-4">
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
        </div>

        <!-- Table Panel -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">UID (Dispatched Code)</th>
                            <th class="py-4 px-6">Product Name</th>
                            <th class="py-4 px-6">Quantity</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Dispatched By</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($dispatchItemCodes as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                <td class="py-4.5 px-6 font-semibold">{{ $item->id }}</td>
                                <td class="py-4.5 px-6">
                                    <div class="flex items-center gap-3 cursor-pointer group" @click="openBarcodeModal('{{ $item->uid }}', '{{ $item->product->product_name ?? 'Product' }}')" title="Click to view/print barcode label">
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
                                <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
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
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 font-mono tracking-wider mb-2" x-text="uid"></span>
                <svg id="modal-barcode-svg" class="bg-white p-1 rounded max-w-full"></svg>
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
        function barcodeModalApp() {
            return {
                isOpen: false,
                uid: '',
                productName: '',
                
                openBarcodeModal(uid, productName) {
                    this.uid = uid;
                    this.productName = productName;
                    this.isOpen = true;
                    
                    this.$nextTick(() => {
                        try {
                            JsBarcode("#modal-barcode-svg", uid, {
                                format: "CODE128",
                                width: 2,
                                height: 70,
                                displayValue: true,
                                margin: 10,
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
                    const card = document.getElementById("modal-barcode-print-content").cloneNode(true);
                    const printDiv = document.createElement("div");
                    printDiv.id = "temp-print-area";
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
                                padding: 20px;
                                background: white !important;
                                text-align: center;
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
                    JsBarcode("#inline-barcode-{{ $item->id }}", "{{ $item->uid }}", {
                        format: "CODE128",
                        width: 1.1,
                        height: 25,
                        displayValue: false,
                        margin: 0
                    });
                } catch(e) {
                    console.error("Failed to render inline barcode for {{ $item->uid }}", e);
                }
            @endforeach
        });
    </script>
    </div> <!-- Closing Alpine x-data container -->
</x-app-layout>
