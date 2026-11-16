<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div>
                <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-[#FF5A36]/10 text-[#FF5A36] rounded-xl inline-flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <span>{{ __('Global Omnisearch') }}</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    @if (!empty($q))
                        Found <strong class="text-slate-800 dark:text-white">{{ $counts['total'] }}</strong> matching records for <span class="px-2 py-0.5 rounded-md bg-[#FF5A36]/10 text-[#FF5A36] font-mono font-bold">"{{ $q }}"</span>
                    @else
                        Search across all bills, products, purchases, sales, barcodes, and users.
                    @endif
                </p>
            </div>

            <!-- Search Form in Header -->
            <form method="GET" action="{{ route('search.index') }}" class="relative max-w-md w-full">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $q }}" 
                    placeholder="Search invoice #, vendor, product, SKU, barcode..."
                    autofocus
                    class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs font-medium text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all shadow-sm"
                >
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if (!empty($q))
                    <a href="{{ route('search.index') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>
    </x-slot>

    <div x-data="{ activeTab: '{{ $tab ?: 'all' }}' }" class="space-y-6">

        <!-- Category Tabs -->
        <div class="flex items-center gap-2 p-1.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm overflow-x-auto">
            <button type="button" @click="activeTab = 'all'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'all' ? 'bg-[#FF5A36] text-white shadow-md shadow-[#FF5A36]/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <span>All Results</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['total'] }}</span>
            </button>

            @if ($counts['bills'] > 0 || $tab === 'bills')
            <button type="button" @click="activeTab = 'bills'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'bills' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Bills & Invoices</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'bills' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['bills'] }}</span>
            </button>
            @endif

            @if ($counts['products'] > 0 || $tab === 'products')
            <button type="button" @click="activeTab = 'products'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'products' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Products</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'products' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['products'] }}</span>
            </button>
            @endif

            @if ($counts['purchases'] > 0 || $tab === 'purchases')
            <button type="button" @click="activeTab = 'purchases'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'purchases' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Purchases</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'purchases' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['purchases'] }}</span>
            </button>
            @endif

            @if ($counts['sales'] > 0 || $tab === 'sales')
            <button type="button" @click="activeTab = 'sales'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'sales' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Sales</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'sales' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['sales'] }}</span>
            </button>
            @endif

            @if ($counts['inward'] > 0 || $counts['dispatch'] > 0 || $tab === 'barcodes')
            <button type="button" @click="activeTab = 'barcodes'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'barcodes' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Barcodes</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'barcodes' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['inward'] + $counts['dispatch'] }}</span>
            </button>
            @endif

            @if ($counts['users'] > 0 || $tab === 'users')
            <button type="button" @click="activeTab = 'users'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                    :class="activeTab === 'users' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Users</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'users' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">{{ $counts['users'] }}</span>
            </button>
            @endif
        </div>

        @if ($counts['total'] === 0)
            <!-- Empty State -->
            <div class="p-12 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm space-y-4">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white">No results found</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        @if (!empty($q))
                            We couldn't find anything matching "<span class="font-bold text-slate-700 dark:text-slate-300">{{ $q }}</span>". Try searching for an invoice number, vendor, customer, product name, or SKU.
                        @else
                            Start typing in the search bar above to look up invoices, products, purchases, and barcodes across the entire system.
                        @endif
                    </p>
                </div>
            </div>
        @else

            <!-- 1. Bills & Invoices Section -->
            <div x-show="activeTab === 'all' || activeTab === 'bills'" class="space-y-3">
                @if ($counts['bills'] > 0)
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-amber-500/10 text-amber-600 rounded-xl inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            <span>Bills & Scanned Invoices ({{ $counts['bills'] }})</span>
                        </h3>
                        <a href="{{ route('bills.index', ['search' => $q]) }}" class="text-xs text-[#FF5A36] hover:underline font-semibold">View in Bills →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($bills as $bill)
                            <a href="{{ route('bills.show', $bill) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] hover:shadow-md transition-all group block">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-mono font-bold text-xs text-[#FF5A36] group-hover:underline">#{{ $bill->invoice_number }}</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $bill->bill_type === 'purchase' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' }}">
                                        {{ ucfirst($bill->bill_type) }}
                                    </span>
                                </div>
                                <div class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $bill->party_name }}</div>
                                @if($bill->party_gstin)
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">GSTIN: {{ $bill->party_gstin }}</div>
                                @endif
                                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                                    <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $bill->formatted_total_amount }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $bill->invoice_date ? $bill->invoice_date->format('d M, Y') : '' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 2. Products Section -->
            <div x-show="activeTab === 'all' || activeTab === 'products'" class="space-y-3">
                @if ($counts['products'] > 0)
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-indigo-500/10 text-indigo-600 rounded-xl inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </span>
                            <span>Products Catalog ({{ $counts['products'] }})</span>
                        </h3>
                        <a href="{{ route('products.index', ['search' => $q]) }}" class="text-xs text-[#FF5A36] hover:underline font-semibold">View in Products →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($products as $prod)
                            <a href="{{ route('products.index', ['search' => $prod->sku ?: $prod->product_id]) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] hover:shadow-md transition-all group block">
                                <div class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-[#FF5A36] truncate">{{ $prod->product_name }}</div>
                                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400 font-mono">
                                    <span>SKU: {{ $prod->sku ?: 'N/A' }}</span>
                                    <span>•</span>
                                    <span>ID: {{ $prod->product_id }}</span>
                                </div>
                                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-slate-500 font-medium">{{ $prod->brand->name ?? 'General Brand' }}</span>
                                    <span class="text-xs text-[#FF5A36] font-semibold">View Product →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 3. Purchases Section -->
            <div x-show="activeTab === 'all' || activeTab === 'purchases'" class="space-y-3">
                @if ($counts['purchases'] > 0)
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-blue-500/10 text-blue-600 rounded-xl inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </span>
                            <span>Purchase Orders ({{ $counts['purchases'] }})</span>
                        </h3>
                        <a href="{{ route('purchases.index', ['search' => $q]) }}" class="text-xs text-[#FF5A36] hover:underline font-semibold">View in Purchases →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($purchases as $po)
                            <a href="{{ route('purchases.index', ['search' => $po->vendor_id]) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] hover:shadow-md transition-all group block">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white truncate">Vendor: {{ $po->vendor_id ?: 'Unknown' }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-300">Qty: {{ $po->quantity }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate">{{ $po->product->product_name ?? 'Product' }}</div>
                                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                                    <span class="font-mono font-bold text-slate-900 dark:text-white">₹{{ number_format((float) $po->amount, 2) }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $po->date ? \Carbon\Carbon::parse($po->date)->format('d M, Y') : '' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 4. Sales Section -->
            <div x-show="activeTab === 'all' || activeTab === 'sales'" class="space-y-3">
                @if ($counts['sales'] > 0)
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-emerald-500/10 text-emerald-600 rounded-xl inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span>Sales & Dispatches ({{ $counts['sales'] }})</span>
                        </h3>
                        <a href="{{ route('sales.index', ['search' => $q]) }}" class="text-xs text-[#FF5A36] hover:underline font-semibold">View in Sales →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($sales as $sale)
                            <a href="{{ route('sales.index', ['search' => $sale->portal_id]) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] hover:shadow-md transition-all group block">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white capitalize">{{ $sale->portal_id ?: 'Customer' }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-300">Qty: {{ $sale->quantity }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate">{{ $sale->product->product_name ?? 'Product' }}</div>
                                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-slate-400">Order: {{ $sale->order_date ? \Carbon\Carbon::parse($sale->order_date)->format('d M, Y') : 'N/A' }}</span>
                                    <span class="text-xs text-[#FF5A36] font-semibold">View Sale →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 5. Barcodes Section -->
            <div x-show="activeTab === 'all' || activeTab === 'barcodes'" class="space-y-3">
                @if ($counts['inward'] > 0 || $counts['dispatch'] > 0)
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-purple-500/10 text-purple-600 rounded-xl inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </span>
                            <span>Barcodes & Serial Item Codes ({{ $counts['inward'] + $counts['dispatch'] }})</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($inwardCodes as $code)
                            <a href="{{ route('inward-item-codes.index', ['search' => $code->item_code]) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] transition-all group block">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-mono font-bold text-xs text-slate-900 dark:text-white select-all">{{ $code->item_code }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-teal-50 text-teal-600 dark:bg-teal-950 dark:text-teal-300">Inward</span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate">{{ $code->product->product_name ?? 'Inward Stock' }}</div>
                                <div class="mt-2 text-[10px] text-slate-400">Scanned on {{ $code->created_at->format('d M, Y') }}</div>
                            </a>
                        @endforeach

                        @foreach ($dispatchCodes as $code)
                            <a href="{{ route('dispatch-item-codes.index', ['search' => $code->item_code]) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] transition-all group block">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-mono font-bold text-xs text-slate-900 dark:text-white select-all">{{ $code->item_code }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-50 text-purple-600 dark:bg-purple-950 dark:text-purple-300">Dispatch</span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate">{{ $code->product->product_name ?? 'Dispatched Stock' }}</div>
                                <div class="mt-2 text-[10px] text-slate-400">Dispatched on {{ $code->created_at->format('d M, Y') }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 6. Users Section -->
            <div x-show="activeTab === 'all' || activeTab === 'users'" class="space-y-3">
                @if ($counts['users'] > 0)
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-slate-500/10 text-slate-700 dark:text-slate-300 rounded-xl inline-flex">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <span>Users ({{ $counts['users'] }})</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($users as $u)
                            <a href="{{ route('users.index', ['search' => $u->name]) }}" class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm hover:border-[#FF5A36] transition-all group block">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $u->name }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">{{ ucfirst($u->role) }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400">{{ $u->email }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        @endif

    </div>
</x-app-layout>
