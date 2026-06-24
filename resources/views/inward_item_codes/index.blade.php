<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Inward Serial Codes') }}
            </h2>
            <a href="{{ route('inward-item-codes.create') }}" class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-150 flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                <span>Add Inward Code</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="barcodeModalApp()">
        <!-- Barcode Scanner Input Form -->
        <div class="p-6 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 dark:from-slate-900 dark:to-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between transition-colors">
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 animate-pulse text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2M6 20H4a2 2 0 01-2-2v-2m16-10V4a2 2 0 00-2-2h-2m-8 2H4a2 2 0 00-2 2v2" />
                    </svg>
                    <span>Barcode Scan Dispatcher</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Scan or type a serial code to automatically record dispatch as Sold.</p>
            </div>
            
            <form id="scan-dispatch-form" action="{{ route('inward-item-codes.scan-dispatch') }}" method="POST" class="w-full md:w-auto flex flex-wrap sm:flex-nowrap gap-3 items-center">
                @csrf
                <div class="relative w-full md:w-80">
                    <input 
                        type="text" 
                        name="scan_uid" 
                        placeholder="Scan Barcode (e.g. Zig0001)..." 
                        autofocus
                        required
                        @focus="inputFocused = true"
                        @blur="inputFocused = false"
                        class="w-full pl-5 pr-28 py-3.5 bg-white dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center pointer-events-none select-none">
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-lg text-[9px] font-bold border border-emerald-100/50 dark:border-emerald-900/50 shadow-sm" x-show="inputFocused" x-cloak>
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            <span>ACTIVE</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 rounded-lg text-[9px] font-bold border border-rose-100/50 dark:border-rose-900/50 shadow-sm" x-show="!inputFocused" x-cloak>
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                            <span>OFFLINE</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md shrink-0">
                    Dispatch
                </button>
            </form>
        </div>

        <!-- View Toggle & Search Panel Wrapper -->
        <div class="flex flex-col xl:flex-row justify-between items-stretch xl:items-center gap-4">
            <!-- Search and Filters Panel -->
            <div class="flex-1 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
                <form method="GET" action="{{ route('inward-item-codes.index') }}" class="w-full flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Search by UID or Product..." 
                            class="w-full pl-11 pr-5 py-3.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                        >
                        <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    
                    <!-- Status Filter Dropdown -->
                    <div class="w-full sm:w-48">
                        <select 
                            name="status" 
                            onchange="this.form.submit()"
                            class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition-all"
                        >
                            <option value="">All Statuses</option>
                            <option value="Good Inventory" {{ $status == 'Good Inventory' ? 'selected' : '' }}>Good Inventory</option>
                            <option value="Damaged" {{ $status == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="Sold" {{ $status == 'Sold' ? 'selected' : '' }}>Sold</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-sm transition-all shadow-md shadow-indigo-900/10">
                        Apply Search
                    </button>
                    @if ($search || $status)
                        <a href="{{ route('inward-item-codes.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
            
            <!-- Toggle Buttons -->
            <div class="flex p-1 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm self-end xl:self-center shrink-0">
                <button 
                    @click="setViewMode('list')"
                    :class="viewMode === 'list' ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span>List View</span>
                </button>
                <button 
                    @click="setViewMode('card')"
                    :class="viewMode === 'card' ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950 shadow-sm' : 'text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    <span>Card View</span>
                </button>
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
            </div>
        </div>

        <!-- Table Panel (List View) -->
        <div x-show="viewMode === 'list'" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
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
                            <th class="py-4 px-6">UID (Serial Code)</th>
                            <th class="py-4 px-6">Product Name</th>
                            <th class="py-4 px-6">Quantity</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Updated By</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($inwardItemCodes as $item)
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
                                <td class="py-4.5 px-6 font-semibold">{{ $item->quantity }}</td>
                                <td class="py-4.5 px-6" id="inward-status-{{ $item->uid }}">
                                    @if ($item->status == 'Good Inventory')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/50">
                                            Good Inventory
                                        </span>
                                    @elseif ($item->status == 'Sold')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">
                                            Sold
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-900/50">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6" id="inward-updater-{{ $item->uid }}">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs">{{ $item->updated_by ?? 'System' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $item->updated_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="py-4.5 px-6 text-right" id="inward-actions-{{ $item->uid }}">
                                    <div class="flex items-center justify-end gap-2" id="inward-action-wrapper-{{ $item->uid }}">
                                        @if ($item->status !== 'Sold')
                                            <form method="POST" action="{{ route('inward-item-codes.scan-dispatch') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="scan_uid" value="{{ $item->uid }}">
                                                <button type="submit" class="p-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Dispatch / Sell Item">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('inward-item-codes.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('inward-item-codes.destroy', $item->id) }}" onsubmit="return confirm('Are you sure you want to delete this serial code?');" class="inline">
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
                                    No inward serial items registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($inwardItemCodes->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/80">
                    {{ $inwardItemCodes->links() }}
                </div>
            @endif
        </div>

        <!-- Card View Panel -->
        <div x-show="viewMode === 'card'" style="display: none;" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($inwardItemCodes as $item)
                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 shadow-sm hover:shadow-md transition-all relative group flex flex-col justify-between min-h-[260px]">
                        <!-- Card Top -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <input 
                                    type="checkbox" 
                                    :value="{{ json_encode($item->uid) }}" 
                                    x-model="selectedUids"
                                    class="w-4 h-4 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer"
                                >
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase font-mono">ID: {{ $item->id }}</span>
                            </div>
                            <div id="card-status-{{ $item->uid }}">
                                @if ($item->status == 'Good Inventory')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/50">
                                        Good Inventory
                                    </span>
                                @elseif ($item->status == 'Sold')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">
                                        Sold
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-900/50">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Center -->
                        <div class="flex flex-col items-center justify-center py-4 cursor-pointer" @click="openBarcodeModal({{ json_encode($item->uid) }}, {{ json_encode($item->product->sku ?? $item->product->product_name ?? 'Product') }})" title="Click to view/print barcode label">
                            <span class="px-3 py-1 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800 rounded-xl font-mono text-xs font-bold text-slate-800 dark:text-slate-200 shadow-sm w-fit mb-2 group-hover:border-indigo-500/50 transition-colors">
                                {{ $item->uid }}
                            </span>
                            <svg id="inline-barcode-card-{{ $item->id }}" class="bg-white p-1 rounded border border-slate-100 max-w-full h-[45px] group-hover:border-indigo-500/50 transition-colors"></svg>
                        </div>

                        <!-- Card Bottom -->
                        <div class="border-t border-slate-50 dark:border-slate-800/60 pt-4 flex flex-col gap-1.5">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $item->product->product_name ?? 'Deleted Product' }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">Code: {{ $item->product->product_id ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] text-slate-400">
                                <span>Qty: {{ $item->quantity }}</span>
                                <span id="card-updater-{{ $item->uid }}">By: {{ $item->updated_by ?? 'System' }}</span>
                            </div>
                        </div>

                        <!-- Actions Overlay -->
                        <div id="card-actions-{{ $item->uid }}" class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 rounded-[2.5rem] flex items-center justify-center gap-2 transition-all duration-200 backdrop-blur-[1px] z-10 p-4 flex-wrap">
                                @if ($item->status !== 'Sold')
                                    <form method="POST" action="{{ route('inward-item-codes.scan-dispatch') }}" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="scan_uid" value="{{ $item->uid }}">
                                        <button type="submit" class="p-2 bg-emerald-600 text-white hover:bg-emerald-500 rounded-xl shadow font-semibold text-[11px] flex items-center gap-1.5 transition-all" title="Dispatch / Sell Item">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            <span>Dispatch</span>
                                        </button>
                                    </form>
                                @endif
                            <button 
                             @click="openBarcodeModal({{ json_encode($item->uid) }}, {{ json_encode($item->product->sku ?? $item->product->product_name ?? 'Product') }})"
                                 class="p-2 bg-white text-slate-900 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl shadow font-semibold text-[11px] flex items-center gap-1.5 transition-all"
                             >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Zoom</span>
                            </button>
                            <a 
                                href="{{ route('inward-item-codes.edit', $item->id) }}"
                                class="p-2 bg-white text-slate-900 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl shadow font-semibold text-[11px] flex items-center gap-1.5 transition-all"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Edit</span>
                            </a>
                            <form method="POST" action="{{ route('inward-item-codes.destroy', $item->id) }}" onsubmit="return confirm('Are you sure you want to delete this serial code?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-white text-rose-600 hover:bg-rose-50 rounded-xl shadow font-semibold text-[11px] flex items-center gap-1.5 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                        No inward serial items registered.
                    </div>
                @endforelse
            </div>

            <!-- Card View Pagination -->
            @if ($inwardItemCodes->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
                    {{ $inwardItemCodes->links() }}
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
        function barcodeModalApp() {
            return {
                isOpen: false,
                uid: '',
                productName: '',
                printWidth: parseInt(localStorage.getItem('modal_printWidth')) || 50,
                printHeight: parseInt(localStorage.getItem('modal_printHeight')) || 25,
                selectedUids: [],
                pageUids: {!! json_encode($inwardItemCodes->pluck('uid')->toArray()) !!},
                
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
                            format: "CODE128",
                            width: 2,
                            height: 70,
                            displayValue: true,
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
                viewMode: localStorage.getItem('inward_view_mode') || 'list',
                inputFocused: true,

                setViewMode(mode) {
                    this.viewMode = mode;
                    localStorage.setItem('inward_view_mode', mode);
                    // Re-render barcodes in next tick once DOM elements are rendered
                    this.$nextTick(() => {
                        window.renderAllBarcodes();
                    });
                },
                
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
                    localStorage.setItem('modal_printWidth', this.printWidth);
                    localStorage.setItem('modal_printHeight', this.printHeight);
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
                                padding: 0 !important;
                                background: white !important;
                                text-align: center;
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
                                align-items: center !important;
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

        // Render function available globally to re-trigger on view swaps
        window.renderAllBarcodes = function() {
            @foreach ($inwardItemCodes as $item)
                try {
                    const tableEl = document.getElementById("inline-barcode-{{ $item->id }}");
                    if (tableEl) {
                        JsBarcode("#inline-barcode-{{ $item->id }}", {!! json_encode($item->uid) !!}, {
                            format: "CODE128",
                            width: 1.1,
                            height: 25,
                            displayValue: false,
                            margin: 0
                        });
                    }
                } catch(e) {
                    console.error("Failed to render inline barcode (table) for " + {!! json_encode($item->uid) !!}, e);
                }

                try {
                    const cardEl = document.getElementById("inline-barcode-card-{{ $item->id }}");
                    if (cardEl) {
                        JsBarcode("#inline-barcode-card-{{ $item->id }}", {!! json_encode($item->uid) !!}, {
                            format: "CODE128",
                            width: 1.3,
                            height: 40,
                            displayValue: false,
                            margin: 0
                        });
                    }
                } catch(e) {
                    console.error("Failed to render inline barcode (card) for " + {!! json_encode($item->uid) !!}, e);
                }
            @endforeach
        };

        // Render all inline barcodes on page load & setup physical scanner focus handlers
        window.addEventListener("DOMContentLoaded", () => {
            window.renderAllBarcodes();

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

    <!-- WebSockets Live Feeds -->
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Audio chimes
            function playChime() {
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                    gain.gain.setValueAtTime(0.04, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.4);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                } catch (e) {
                    console.log("Audio feedback block active");
                }
            }

            // Initialize Echo
            const echoHost = window.location.hostname;
            const reverbPort = {{ env('REVERB_PORT', 8080) }};
            const reverbKey = "{{ env('REVERB_APP_KEY') }}";

            if (reverbKey) {
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: reverbKey,
                    wsHost: echoHost,
                    wsPort: reverbPort,
                    wssPort: reverbPort,
                    forceTLS: window.location.protocol === 'https:',
                    enabledTransports: ['ws', 'wss'],
                });

                // Listen for dispatches
                window.Echo.channel('dispatches')
                    .listen('.barcode.dispatched', (e) => {
                        console.log('Dispatch event received on serial codes index page:', e);
                        
                        // 1. Play chime audio
                        playChime();

                        // 2. Update Table Status Badge
                        const tableStatus = document.getElementById(`inward-status-${e.uid}`);
                        if (tableStatus) {
                            tableStatus.classList.add('scale-105', 'transition-all', 'duration-300');
                            tableStatus.innerHTML = `
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">
                                    Sold
                                </span>
                            `;
                            setTimeout(() => {
                                tableStatus.classList.remove('scale-105');
                            }, 300);
                        }

                        // 3. Update Table Updater Details
                        const tableUpdater = document.getElementById(`inward-updater-${e.uid}`);
                        if (tableUpdater) {
                            tableUpdater.innerHTML = `
                                <div class="flex flex-col">
                                    <span class="font-bold text-xs">${e.updatedBy}</span>
                                    <span class="text-[10px] text-slate-400 font-sans">Just now</span>
                                </div>
                            `;
                        }

                        // 4. Remove Table Dispatch Action Form
                        const dispatchForm = document.querySelector(`#inward-action-wrapper-${e.uid} form[action*="scan-dispatch"]`);
                        if (dispatchForm) {
                            dispatchForm.remove();
                        }

                        // 5. Update Card Status Badge
                        const cardStatus = document.getElementById(`card-status-${e.uid}`);
                        if (cardStatus) {
                            cardStatus.innerHTML = `
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">
                                    Sold
                                </span>
                            `;
                        }

                        // 6. Update Card Updater Name
                        const cardUpdater = document.getElementById(`card-updater-${e.uid}`);
                        if (cardUpdater) {
                            cardUpdater.innerText = `By: ${e.updatedBy}`;
                        }

                        // 7. Remove Card Dispatch Form
                        const cardForm = document.querySelector(`#card-actions-${e.uid} form[action*="scan-dispatch"]`);
                        if (cardForm) {
                            cardForm.remove();
                        }
                    });
            }
        });
    </script>
    </div> <!-- Closing Alpine x-data container -->
</x-app-layout>
