<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <!-- Left Header: Breadcrumb & Title -->
            <div class="flex items-center gap-3">
                <a href="{{ route('bills.index', ['type' => $bill->bill_type]) }}" 
                   class="p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#FF5A36] rounded-2xl transition-all shadow-sm flex items-center justify-center"
                   title="Back to Bills">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-heading font-bold text-2xl text-slate-900 dark:text-white leading-tight font-mono">
                            {{ $bill->invoice_number }}
                        </h2>
                        @if ($bill->bill_type === 'purchase')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Purchase Bill
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Sales Bill
                            </span>
                        @endif

                        @if ($bill->status === 'Paid')
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
                                Paid
                            </span>
                        @elseif ($bill->status === 'Pending')
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">
                                Pending
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                {{ $bill->status }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ $bill->party_name }} &bull; {{ $bill->invoice_date ? $bill->invoice_date->format('d M, Y') : 'No Date' }}
                    </p>
                </div>
            </div>

            <!-- Right Header: Navigation & Action Buttons -->
            <div class="flex items-center gap-2">
                <!-- Previous Bill Link -->
                @if ($prevBill)
                    <a href="{{ route('bills.show', $prevBill) }}" 
                       class="px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:text-[#FF5A36] rounded-xl text-xs font-semibold transition-all shadow-sm flex items-center gap-1"
                       title="Previous Bill: {{ $prevBill->invoice_number }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        <span class="hidden sm:inline">Prev</span>
                    </a>
                @endif

                <!-- Next Bill Link -->
                @if ($nextBill)
                    <a href="{{ route('bills.show', $nextBill) }}" 
                       class="px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:text-[#FF5A36] rounded-xl text-xs font-semibold transition-all shadow-sm flex items-center gap-1"
                       title="Next Bill: {{ $nextBill->invoice_number }}">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif

                <!-- Download File -->
                <a href="{{ route('bills.download', $bill) }}" 
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Download</span>
                </a>

                <!-- Edit Button -->
                <a href="{{ route('bills.edit', $bill) }}" 
                   class="px-4 py-2 bg-[#FF5A36] hover:bg-[#E04826] text-white text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-md shadow-[#FF5A36]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit</span>
                </a>

                <!-- Delete Button -->
                <form method="POST" action="{{ route('bills.destroy', $bill) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete invoice #{{ $bill->invoice_number }} and its document?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="p-2 bg-slate-100 hover:bg-rose-500 hover:text-white dark:bg-slate-800 dark:hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl transition-all"
                            title="Delete invoice">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <!-- MAIN SPLIT VIEW CONTAINER (Left: PDF / Document, Right: Complete Details) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN: Scanned PDF / Document Viewer (55% on desktop) -->
        <div class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm overflow-hidden flex flex-col h-[calc(100vh-140px)] min-h-[720px]">
            <!-- Document Header Toolbar -->
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800/80 bg-slate-50/75 dark:bg-slate-950/40 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg {{ $bill->is_pdf ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40' }}">
                        @if ($bill->is_pdf)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 truncate max-w-xs">{{ $bill->file_name }}</span>
                    <span class="text-slate-400 text-[11px]">({{ $bill->file_size_human }})</span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ $bill->file_url }}" target="_blank" class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors" title="Open PDF in new tab">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                    <a href="{{ route('bills.download', $bill) }}" class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors" title="Download Document">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Document Embedded View -->
            <div class="flex-1 w-full bg-slate-900/5 dark:bg-slate-950/80 overflow-hidden relative">
                @if ($bill->is_pdf)
                    <iframe 
                        src="{{ $bill->file_url }}#toolbar=1&navpanes=0&scrollbar=1" 
                        class="w-full h-full border-0" 
                        title="Scanned Invoice PDF - {{ $bill->invoice_number }}">
                        <!-- Fallback if browser blocks iframe PDF rendering -->
                        <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                            <p class="text-sm text-slate-500 mb-4">Your browser does not support inline PDF viewing.</p>
                            <a href="{{ $bill->file_url }}" target="_blank" class="px-5 py-2.5 bg-[#FF5A36] text-white rounded-xl text-xs font-semibold shadow-md">
                                Open PDF in New Window
                            </a>
                        </div>
                    </iframe>
                @else
                    <!-- High-Resolution Image Viewer with Zoom & Pan -->
                    <div class="w-full h-full overflow-auto flex items-center justify-center p-6 bg-slate-950/30">
                        <img src="{{ $bill->file_url }}" alt="Scanned Document {{ $bill->invoice_number }}" class="max-w-full max-h-full object-contain rounded-xl shadow-xl border border-slate-200/20">
                    </div>
                @endif
            </div>
        </div>


        <!-- RIGHT COLUMN: Detailed Extracted & Structured Invoice Data (45% on desktop) -->
        <div class="lg:col-span-5 h-[calc(100vh-140px)] min-h-[720px] overflow-y-auto space-y-4 pr-1 scrollbar-thin">
            
            <!-- 1. Hero Financial Highlights Card -->
            <div class="p-6 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 text-white rounded-[2rem] shadow-xl border border-slate-800 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#FF5A36]/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs uppercase font-bold tracking-wider text-slate-400">Total Invoice Amount</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bill->status === 'Paid' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                        {{ $bill->status }}
                    </span>
                </div>
                
                <div class="flex items-baseline gap-2">
                    <span class="font-mono text-4xl font-extrabold tracking-tight text-white">{{ $bill->formatted_total_amount }}</span>
                    @if ($bill->payment_mode)
                        <span class="text-xs text-slate-400 font-medium">via {{ $bill->payment_mode }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 pt-5 border-t border-slate-800 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Subtotal (Taxable Value)</span>
                        <span class="font-mono font-semibold text-slate-200 text-sm">₹{{ number_format((float)$bill->subtotal, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Tax / GST Amount</span>
                        <span class="font-mono font-semibold text-slate-200 text-sm">₹{{ number_format((float)$bill->tax_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Invoice Details Card -->
            <div class="p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center justify-between">
                    <span>Invoice Specifications</span>
                    <span class="text-xs font-mono font-normal text-slate-400">ID: #{{ $bill->id }}</span>
                </h3>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-100 dark:border-slate-800/60">
                        <span class="text-slate-400 block text-[11px] mb-1">Invoice Number</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white text-sm select-all">{{ $bill->invoice_number }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-100 dark:border-slate-800/60">
                        <span class="text-slate-400 block text-[11px] mb-1">Invoice Date</span>
                        <span class="font-medium text-slate-900 dark:text-white text-sm">
                            {{ $bill->invoice_date ? $bill->invoice_date->format('d F, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-100 dark:border-slate-800/60">
                        <span class="text-slate-400 block text-[11px] mb-1">Bill Category</span>
                        <span class="font-semibold text-slate-900 dark:text-white capitalize">
                            {{ $bill->bill_type === 'purchase' ? 'Vendor Purchase Bill' : 'Customer Sales Bill' }}
                        </span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-100 dark:border-slate-800/60">
                        <span class="text-slate-400 block text-[11px] mb-1">Payment Method</span>
                        <span class="font-semibold text-slate-900 dark:text-white">
                            {{ $bill->payment_mode ?: 'Bank Transfer' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3. Dual Party Information: FROM (Seller/Supplier) ➔ TO (Buyer/Recipient) -->
            <div class="p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 bg-[#FF5A36]/10 text-[#FF5A36] rounded-xl inline-flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </span>
                        <span>Invoice Parties (FROM &rarr; TO)</span>
                    </h3>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60">
                        {{ $bill->bill_type === 'purchase' ? 'Vendor ➔ Indivolt' : 'Indivolt ➔ Customer' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <!-- FROM: Supplier / Seller Card -->
                    <div class="p-4 rounded-2xl border border-amber-200/70 dark:border-amber-900/40 bg-gradient-to-br from-amber-50/40 via-white to-amber-50/20 dark:from-amber-950/20 dark:via-slate-900 dark:to-slate-900 space-y-2.5 shadow-sm">
                        <div class="flex items-center justify-between pb-1 border-b border-amber-100 dark:border-amber-900/30">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 dark:text-amber-400">
                                    FROM: SUPPLIER / SELLER
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $bill->bill_type === 'purchase' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                {{ $bill->bill_type === 'purchase' ? 'External Vendor' : 'Host Issuer' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Company / Entity</span>
                            <h4 class="font-bold text-slate-900 dark:text-white text-xs select-all mt-0.5 leading-snug">
                                {{ $bill->from_party['name'] }}
                            </h4>
                        </div>

                        @if (!empty($bill->from_party['gstin']))
                            <div class="flex items-baseline justify-between pt-1 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                <span class="text-slate-400 text-[11px]">GSTIN:</span>
                                <span class="font-mono font-bold text-amber-700 dark:text-amber-400 text-[11px] select-all bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded border border-amber-200/60 dark:border-amber-900/40">
                                    {{ $bill->from_party['gstin'] }}
                                </span>
                            </div>
                        @endif

                        @if (!empty($bill->from_party['phone']))
                            <div class="flex items-baseline justify-between pt-1 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                <span class="text-slate-400 text-[11px]">Phone:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300 text-[11px] select-all">
                                    {{ $bill->from_party['phone'] }}
                                </span>
                            </div>
                        @endif

                        @if (!empty($bill->from_party['address']))
                            <div class="pt-1 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                <span class="text-slate-400 text-[10px] block mb-0.5 font-medium">Origin Address:</span>
                                <p class="text-slate-600 dark:text-slate-300 text-[11px] leading-relaxed line-clamp-3">
                                    {{ $bill->from_party['address'] }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- TO: Billed To / Recipient Card -->
                    <div class="p-4 rounded-2xl border border-emerald-200/70 dark:border-emerald-900/40 bg-gradient-to-br from-emerald-50/40 via-white to-emerald-50/20 dark:from-emerald-950/20 dark:via-slate-900 dark:to-slate-900 space-y-2.5 shadow-sm">
                        <div class="flex items-center justify-between pb-1 border-b border-emerald-100 dark:border-emerald-900/30">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                    TO: BILLED TO / BUYER
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $bill->bill_type === 'sale' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/70 dark:text-indigo-300' }}">
                                {{ $bill->bill_type === 'sale' ? 'Customer' : 'Indivolt (Recipient)' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Company / Entity</span>
                            <h4 class="font-bold text-slate-900 dark:text-white text-xs select-all mt-0.5 leading-snug">
                                {{ $bill->to_party['name'] }}
                            </h4>
                        </div>

                        @if (!empty($bill->to_party['gstin']))
                            <div class="flex items-baseline justify-between pt-1 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                <span class="text-slate-400 text-[11px]">GSTIN:</span>
                                <span class="font-mono font-bold text-emerald-700 dark:text-emerald-400 text-[11px] select-all bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200/60 dark:border-emerald-900/40">
                                    {{ $bill->to_party['gstin'] }}
                                </span>
                            </div>
                        @endif

                        @if (!empty($bill->to_party['phone']))
                            <div class="flex items-baseline justify-between pt-1 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                <span class="text-slate-400 text-[11px]">Phone:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300 text-[11px] select-all">
                                    {{ $bill->to_party['phone'] }}
                                </span>
                            </div>
                        @endif

                        @if (!empty($bill->to_party['address']))
                            <div class="pt-1 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                <span class="text-slate-400 text-[10px] block mb-0.5 font-medium">Billed To Address:</span>
                                <p class="text-slate-600 dark:text-slate-300 text-[11px] leading-relaxed line-clamp-3">
                                    {{ $bill->to_party['address'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 4. Extracted Line Items Table -->
            <div class="p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Scanned Line Items</span>
                    </h3>
                    @if (!empty($bill->line_items) && count($bill->line_items) > 0)
                        <span class="text-xs text-slate-400">{{ count($bill->line_items) }} items extracted</span>
                    @endif
                </div>

                @if (!empty($bill->line_items) && count($bill->line_items) > 0)
                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-2xl">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 text-slate-400 font-semibold border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-2.5 px-3">#</th>
                                    <th class="py-2.5 px-3">Item Description</th>
                                    <th class="py-2.5 px-3 text-center">Qty</th>
                                    <th class="py-2.5 px-3 text-right">Rate</th>
                                    <th class="py-2.5 px-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach ($bill->line_items as $index => $item)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                                        <td class="py-2.5 px-3 text-slate-400 font-mono text-[11px]">{{ $index + 1 }}</td>
                                        <td class="py-2.5 px-3 font-medium text-slate-900 dark:text-white">
                                            <div>{{ $item['item_name'] ?? 'Item' }}</div>
                                            @if(!empty($item['hsn']))
                                                <span class="text-[10px] text-slate-400 font-mono">HSN: {{ $item['hsn'] }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-3 text-center font-mono text-slate-700 dark:text-slate-300">
                                            {{ $item['quantity'] ?? 1 }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-mono text-slate-700 dark:text-slate-300">
                                            ₹{{ number_format((float)($item['rate'] ?? 0), 2) }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-mono font-semibold text-slate-900 dark:text-white">
                                            ₹{{ number_format((float)($item['amount'] ?? 0), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-100 dark:border-slate-800/60 text-center text-xs text-slate-500">
                        <p>No separate line item rows were parsed. The overall invoice total of <strong class="text-slate-800 dark:text-slate-200">{{ $bill->formatted_total_amount }}</strong> is recorded.</p>
                    </div>
                @endif
            </div>

            <!-- 5. Audit & Metadata Card -->
            <div class="p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-3">
                <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Document Information</h3>
                
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                        <span>Original File:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $bill->file_name }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                        <span>File Size:</span>
                        <span class="font-mono text-slate-800 dark:text-slate-200">{{ $bill->file_size_human }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                        <span>Uploaded By:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $bill->uploaded_by ?? 'System' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400">
                        <span>Uploaded Date:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $bill->created_at->format('d M, Y h:i A') }}</span>
                    </div>

                    @if ($bill->notes)
                        <div class="border-t border-slate-100 dark:border-slate-800 pt-2 mt-2">
                            <span class="text-slate-400 block text-[11px] mb-0.5">Notes:</span>
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed text-[11px] bg-slate-50 dark:bg-slate-950/60 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/60">
                                {{ $bill->notes }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
