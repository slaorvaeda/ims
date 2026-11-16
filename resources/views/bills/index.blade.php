<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div>
                <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-[#FF5A36]/10 text-[#FF5A36] rounded-xl inline-flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    <span>{{ __('Bills & Scanned Invoices') }}</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Manage scanned Sales & Purchase invoices with OCR data extraction & split-screen document viewer.
                </p>
            </div>
            
            <div class="flex items-center gap-2.5">
                <a href="{{ route('bills.create', ['type' => $type ?: 'purchase']) }}" 
                   class="px-5 py-2.5 bg-[#FF5A36] hover:bg-[#E04826] text-white text-sm font-semibold rounded-2xl transition-all duration-150 flex items-center gap-2 shadow-lg shadow-[#FF5A36]/20 hover:shadow-[#FF5A36]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/>
                    </svg>
                    <span>Upload Scanned Bill</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 rounded-2xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-sm shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/50 rounded-2xl flex items-center gap-3 text-rose-800 dark:text-rose-300 text-sm shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Quick Metrics KPI Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Total Invoices Card -->
            <a href="{{ route('bills.index') }}" 
               class="p-5 bg-white dark:bg-slate-900 border {{ empty($type) ? 'border-[#FF5A36] ring-2 ring-[#FF5A36]/10' : 'border-slate-100 dark:border-slate-800/80' }} rounded-[2rem] shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">All Scanned Bills</span>
                    <span class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($totalCount) }}</span>
                    <span class="text-xs font-semibold text-slate-500">Invoices on Record</span>
                </div>
            </a>

            <!-- Purchase Bills Card -->
            <a href="{{ route('bills.index', ['type' => 'purchase']) }}" 
               class="p-5 bg-white dark:bg-slate-900 border {{ $type === 'purchase' ? 'border-amber-500 ring-2 ring-amber-500/10' : 'border-slate-100 dark:border-slate-800/80' }} rounded-[2rem] shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Purchase Bills (Vendors)
                    </span>
                    <span class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold font-heading text-slate-900 dark:text-white">₹{{ number_format($purchaseTotal, 2) }}</span>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">{{ number_format($purchaseCount) }} bills</span>
                </div>
            </a>

            <!-- Sales Bills Card -->
            <a href="{{ route('bills.index', ['type' => 'sale']) }}" 
               class="p-5 bg-white dark:bg-slate-900 border {{ $type === 'sale' ? 'border-emerald-500 ring-2 ring-emerald-500/10' : 'border-slate-100 dark:border-slate-800/80' }} rounded-[2rem] shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Sales Bills (Portals/Clients)
                    </span>
                    <span class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold font-heading text-slate-900 dark:text-white">₹{{ number_format($salesTotal, 2) }}</span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($salesCount) }} bills</span>
                </div>
            </a>
        </div>

        <!-- Section Navigation Tabs & Search Filter Panel -->
        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
            <!-- Tabs Row -->
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800/80 pb-4">
                <div class="flex items-center gap-2 p-1 bg-slate-100 dark:bg-slate-800/60 rounded-2xl text-xs font-semibold">
                    <a href="{{ route('bills.index', array_merge(request()->except('type', 'page'))) }}" 
                       class="px-4 py-2 rounded-xl transition-all {{ empty($type) ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        All Invoices ({{ $totalCount }})
                    </a>
                    <a href="{{ route('bills.index', array_merge(request()->except('page'), ['type' => 'purchase'])) }}" 
                       class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $type === 'purchase' ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Purchases</span>
                        <span class="ml-1 px-1.5 py-0.5 rounded-md text-[10px] {{ $type === 'purchase' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">{{ $purchaseCount }}</span>
                    </a>
                    <a href="{{ route('bills.index', array_merge(request()->except('page'), ['type' => 'sale'])) }}" 
                       class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $type === 'sale' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Sales</span>
                        <span class="ml-1 px-1.5 py-0.5 rounded-md text-[10px] {{ $type === 'sale' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">{{ $salesCount }}</span>
                    </a>
                </div>

                <div class="text-xs text-slate-400">
                    Showing {{ $bills->firstItem() ?? 0 }}-{{ $bills->lastItem() ?? 0 }} of {{ $bills->total() }} records
                </div>
            </div>

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('bills.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                @if ($type)
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif

                <!-- Search Input -->
                <div class="lg:col-span-4 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Search invoice #, vendor, customer, item, GSTIN..." 
                        class="w-full pl-10 pr-9 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-xs focus:outline-none focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if ($search)
                        <a href="{{ route('bills.index', array_merge(request()->except('search', 'page'), $type ? ['type' => $type] : [])) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition-colors" title="Clear search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

                <!-- Status Select -->
                <div class="lg:col-span-2">
                    <select name="status" class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                        <option value="">All Statuses</option>
                        <option value="Paid" {{ $status === 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Partially Paid" {{ $status === 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="Cancelled" {{ $status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Date Range From -->
                <div class="lg:col-span-2">
                    <input 
                        type="date" 
                        name="start_date" 
                        value="{{ $startDate }}" 
                        placeholder="From Date"
                        class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"
                    >
                </div>

                <!-- Date Range To -->
                <div class="lg:col-span-2">
                    <input 
                        type="date" 
                        name="end_date" 
                        value="{{ $endDate }}" 
                        placeholder="To Date"
                        class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"
                    >
                </div>

                <!-- Filter Actions -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-[#FF5A36] hover:bg-[#E04826] text-white font-semibold rounded-xl text-xs transition-all shadow-md shadow-[#FF5A36]/20 text-center">
                        Filter
                    </button>
                    @if ($search || $status || $startDate || $endDate)
                        <a href="{{ route('bills.index', $type ? ['type' => $type] : []) }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all text-center">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Bills Data Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm overflow-hidden">
            @if ($bills->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800/80 bg-slate-50/75 dark:bg-slate-950/40 text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider">
                                <th class="py-4 px-5">Invoice Number</th>
                                <th class="py-4 px-4">Type</th>
                                <th class="py-4 px-4">Invoice Date</th>
                                <th class="py-4 px-5">Party / Vendor / Customer</th>
                                <th class="py-4 px-4 text-right">Total Amount</th>
                                <th class="py-4 px-4">Status</th>
                                <th class="py-4 px-4">Scanned Document</th>
                                <th class="py-4 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @foreach ($bills as $bill)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors group">
                                    <!-- Invoice Number (Clickable to Split Screen!) -->
                                    <td class="py-4 px-5">
                                        <a href="{{ route('bills.show', $bill) }}" 
                                           class="inline-flex items-center gap-1.5 font-bold font-mono text-sm text-[#FF5A36] hover:text-[#E04826] hover:underline group-hover:translate-x-0.5 transition-all"
                                           title="Click to view split screen PDF & details">
                                            <span>{{ $bill->invoice_number }}</span>
                                            <svg class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                        @if($bill->notes)
                                            <p class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">{{ $bill->notes }}</p>
                                        @endif
                                    </td>

                                    <!-- Bill Type Badge -->
                                    <td class="py-4 px-4">
                                        @if ($bill->bill_type === 'purchase')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/40">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                Purchase
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Sale
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Invoice Date -->
                                    <td class="py-4 px-4 text-slate-600 dark:text-slate-300 font-medium">
                                        {{ $bill->invoice_date ? $bill->invoice_date->format('d M, Y') : 'N/A' }}
                                    </td>

                                    <!-- Party Name / GSTIN -->
                                    <td class="py-4 px-5">
                                        <div class="font-semibold text-slate-900 dark:text-white max-w-xs truncate">
                                            {{ $bill->party_name }}
                                        </div>
                                        @if ($bill->seller_name && $bill->buyer_name)
                                            <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1 truncate max-w-xs">
                                                <span class="text-amber-600 dark:text-amber-400 font-medium truncate" title="From: {{ $bill->seller_name }}">{{ \Illuminate\Support\Str::limit($bill->seller_name, 18) }}</span>
                                                <span>&rarr;</span>
                                                <span class="text-emerald-600 dark:text-emerald-400 font-medium truncate" title="To: {{ $bill->buyer_name }}">{{ \Illuminate\Support\Str::limit($bill->buyer_name, 18) }}</span>
                                            </div>
                                        @endif
                                        @if ($bill->party_gstin)
                                            <span class="text-[10px] font-mono text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded mt-0.5 inline-block">
                                                GSTIN: {{ $bill->party_gstin }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Total Amount -->
                                    <td class="py-4 px-4 text-right font-mono font-bold text-slate-900 dark:text-white text-sm">
                                        {{ $bill->formatted_total_amount }}
                                    </td>

                                    <!-- Status -->
                                    <td class="py-4 px-4">
                                        @if ($bill->status === 'Paid')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
                                                Paid
                                            </span>
                                        @elseif ($bill->status === 'Pending')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">
                                                Pending
                                            </span>
                                        @elseif ($bill->status === 'Partially Paid')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">
                                                Partially Paid
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                                {{ $bill->status }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Scanned Document Pill -->
                                    <td class="py-4 px-4">
                                        <a href="{{ route('bills.show', $bill) }}" class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 hover:text-[#FF5A36] transition-colors" title="{{ $bill->file_name }}">
                                            @if ($bill->is_pdf)
                                                <span class="w-6 h-6 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                </span>
                                            @else
                                                <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </span>
                                            @endif
                                            <span class="truncate max-w-[110px]">{{ $bill->file_name }}</span>
                                            <span class="text-[10px] text-slate-400">({{ $bill->file_size_human }})</span>
                                        </a>
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="py-4 px-5 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- View Split Screen (Main action) -->
                                            <a href="{{ route('bills.show', $bill) }}" 
                                               class="p-2 bg-slate-100 hover:bg-[#FF5A36] hover:text-white dark:bg-slate-800 dark:hover:bg-[#FF5A36] text-slate-600 dark:text-slate-300 rounded-xl transition-all"
                                               title="Open Split View (PDF on Left, Details on Right)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <!-- Download PDF -->
                                            <a href="{{ route('bills.download', $bill) }}" 
                                               class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition-all"
                                               title="Download original file">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>

                                            <!-- Edit Bill -->
                                            <a href="{{ route('bills.edit', $bill) }}" 
                                               class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition-all"
                                               title="Edit bill details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- Delete Bill -->
                                            <form method="POST" action="{{ route('bills.destroy', $bill) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete invoice #{{ $bill->invoice_number }} and its scanned document?');">
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($bills->hasPages())
                    <div class="p-5 border-t border-slate-100 dark:border-slate-800/80">
                        {{ $bills->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 px-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-3xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">No scanned invoices found</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-6">
                        @if ($search || $status || $startDate || $endDate)
                            No bills match your current filters. Try clearing the search or date ranges.
                        @else
                            Start by uploading a scanned PDF or photo of your Sales or Purchase invoice. Our AI will automatically extract all data!
                        @endif
                    </p>
                    <a href="{{ route('bills.create', ['type' => $type ?: 'purchase']) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#FF5A36] hover:bg-[#E04826] text-white text-xs font-semibold rounded-2xl transition-all shadow-lg shadow-[#FF5A36]/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                        <span>Upload First Scanned Bill</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
