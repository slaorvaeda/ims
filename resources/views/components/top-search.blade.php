<div x-data="topOmnisearch()" 
     @click.away="close()" 
     class="relative max-w-xs xl:max-w-lg w-full mx-auto"
     x-cloak>
    
    <!-- Top Search Input Form -->
    <form method="GET" action="{{ route('search.index') }}" @submit="handleSubmit($event)">
        <div class="relative flex items-center">
            <!-- Left Search Icon -->
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <template x-if="!isLoading">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </template>
                <template x-if="isLoading">
                    <svg class="w-4 h-4 animate-spin text-[#FF5A36]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
            </div>

            <!-- Input Field -->
            <input 
                type="text" 
                name="q"
                x-ref="searchInput"
                x-model="query"
                @input="onInput()"
                @focus="onFocus()"
                @keydown.down.prevent="navigateDown()"
                @keydown.up.prevent="navigateUp()"
                @keydown.enter="onEnter($event)"
                @keydown.escape="close()"
                placeholder="Search bills, products, purchases, barcodes... (Press /)" 
                autocomplete="off"
                class="w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm rounded-full py-2.5 pl-10 pr-16 text-xs focus:ring-2 focus:ring-[#FF5A36]/25 focus:border-[#FF5A36] text-slate-700 dark:text-slate-200 font-medium placeholder-slate-400 transition-all focus:outline-none"
            />

            <!-- Right Badge / Clear Button -->
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1.5">
                <button type="button" 
                        x-show="query.length > 0" 
                        @click="clear()" 
                        class="p-1 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        title="Clear search">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md shadow-xs">/</kbd>
            </div>
        </div>
    </form>

    <!-- Live Dropdown Results Popup -->
    <div x-show="isOpen && query.trim().length > 0" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
         class="absolute left-0 right-0 mt-2 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/90 dark:border-slate-800/90 rounded-2xl shadow-2xl z-50 overflow-hidden max-h-[75vh] flex flex-col"
         style="display: none;">
        
        <!-- Filter Tabs inside Dropdown -->
        <div class="flex items-center gap-1 p-2 bg-slate-50/80 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800 overflow-x-auto text-[11px] shrink-0">
            <button type="button" @click="activeCategory = 'all'" 
                    class="px-2.5 py-1 rounded-lg font-bold transition-all whitespace-nowrap"
                    :class="activeCategory === 'all' ? 'bg-[#FF5A36] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">
                All (<span x-text="results.total || 0"></span>)
            </button>
            <template x-if="results.bills && results.bills.length > 0">
                <button type="button" @click="activeCategory = 'bills'" 
                        class="px-2.5 py-1 rounded-lg font-semibold transition-all whitespace-nowrap"
                        :class="activeCategory === 'bills' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">
                    Bills (<span x-text="results.bills.length"></span>)
                </button>
            </template>
            <template x-if="results.products && results.products.length > 0">
                <button type="button" @click="activeCategory = 'products'" 
                        class="px-2.5 py-1 rounded-lg font-semibold transition-all whitespace-nowrap"
                        :class="activeCategory === 'products' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">
                    Products (<span x-text="results.products.length"></span>)
                </button>
            </template>
            <template x-if="results.purchases && results.purchases.length > 0">
                <button type="button" @click="activeCategory = 'purchases'" 
                        class="px-2.5 py-1 rounded-lg font-semibold transition-all whitespace-nowrap"
                        :class="activeCategory === 'purchases' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">
                    Purchases (<span x-text="results.purchases.length"></span>)
                </button>
            </template>
            <template x-if="results.sales && results.sales.length > 0">
                <button type="button" @click="activeCategory = 'sales'" 
                        class="px-2.5 py-1 rounded-lg font-semibold transition-all whitespace-nowrap"
                        :class="activeCategory === 'sales' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">
                    Sales (<span x-text="results.sales.length"></span>)
                </button>
            </template>
            <template x-if="results.barcodes && results.barcodes.length > 0">
                <button type="button" @click="activeCategory = 'barcodes'" 
                        class="px-2.5 py-1 rounded-lg font-semibold transition-all whitespace-nowrap"
                        :class="activeCategory === 'barcodes' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">
                    Barcodes (<span x-text="results.barcodes.length"></span>)
                </button>
            </template>
        </div>

        <!-- Scrollable Results List -->
        <div class="overflow-y-auto flex-1 divide-y divide-slate-100 dark:divide-slate-800/60 p-2 space-y-1">
            
            <!-- Empty state when no matches -->
            <template x-if="!isLoading && results.total === 0">
                <div class="p-6 text-center text-xs text-slate-400">
                    <svg class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>No results found for "<strong class="text-slate-700 dark:text-slate-200" x-text="query"></strong>"</span>
                    <p class="text-[11px] text-slate-400 mt-1">Try invoice #, vendor name, product name, or SKU.</p>
                </div>
            </template>

            <!-- Flat item list from flattenedResults -->
            <template x-for="(item, index) in filteredResults" :key="index">
                <a :href="item.url" 
                   @mouseenter="selectedIndex = index"
                   class="flex items-center justify-between p-2.5 rounded-xl transition-all cursor-pointer group"
                   :class="selectedIndex === index ? 'bg-slate-100 dark:bg-slate-800/80 text-slate-900 dark:text-white' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-700 dark:text-slate-300'">
                    
                    <div class="flex items-center gap-3 min-w-0 pr-2">
                        <!-- Icon Badge -->
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                              :class="{
                                  'bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400': item.badge_type === 'purchase',
                                  'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400': item.badge_type === 'sale',
                                  'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400': item.badge_type === 'product',
                                  'bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400': item.badge_type === 'inward' || item.badge_type === 'dispatch',
                                  'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300': item.badge_type === 'user'
                              }">
                            <template x-if="item.badge_type === 'purchase' || item.badge_type === 'sale'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </template>
                            <template x-if="item.badge_type === 'product'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </template>
                            <template x-if="item.badge_type === 'inward' || item.badge_type === 'dispatch'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </template>
                            <template x-if="item.badge_type === 'user'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </template>
                        </span>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs truncate" x-text="item.title"></span>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold shrink-0 uppercase tracking-wider"
                                      :class="{
                                          'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300': item.badge_type === 'purchase',
                                          'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300': item.badge_type === 'sale',
                                          'bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300': item.badge_type === 'product',
                                          'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300': item.badge_type === 'inward' || item.badge_type === 'dispatch',
                                          'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300': item.badge_type === 'user'
                                      }"
                                      x-text="item.badge"></span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate" x-text="item.subtitle"></div>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block" x-text="item.meta"></span>
                    </div>
                </a>
            </template>
        </div>

        <!-- Footer Action -->
        <div class="p-2.5 bg-slate-50 dark:bg-slate-950/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400 shrink-0">
            <div class="flex items-center gap-2">
                <span>Navigate: <kbd class="px-1 bg-white dark:bg-slate-800 border rounded">↑</kbd> <kbd class="px-1 bg-white dark:bg-slate-800 border rounded">↓</kbd></span>
                <span>Select: <kbd class="px-1 bg-white dark:bg-slate-800 border rounded">↵</kbd></span>
                <span>Close: <kbd class="px-1 bg-white dark:bg-slate-800 border rounded">esc</kbd></span>
            </div>

            <a :href="'{{ route('search.index') }}?q=' + encodeURIComponent(query)" class="font-bold text-[#FF5A36] hover:underline flex items-center gap-1">
                <span>View all results</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</div>

<script>
    function topOmnisearch() {
        return {
            query: '',
            isOpen: false,
            isLoading: false,
            activeCategory: 'all',
            selectedIndex: -1,
            debounceTimer: null,
            results: {
                total: 0,
                bills: [],
                products: [],
                purchases: [],
                sales: [],
                barcodes: [],
                users: []
            },

            init() {
                // Global keyboard shortcut '/' or 'Cmd+K' / 'Ctrl+K'
                window.addEventListener('keydown', (e) => {
                    const tag = document.activeElement ? document.activeElement.tagName : '';
                    const isInput = tag === 'INPUT' || tag === 'TEXTAREA' || document.activeElement.isContentEditable;
                    
                    if (!isInput && (e.key === '/' || ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k'))) {
                        e.preventDefault();
                        this.$refs.searchInput.focus();
                        this.isOpen = true;
                    }
                });
            },

            onFocus() {
                if (this.query.trim().length > 0) {
                    this.isOpen = true;
                }
            },

            onInput() {
                clearTimeout(this.debounceTimer);
                const q = this.query.trim();

                if (q.length === 0) {
                    this.isOpen = false;
                    this.results = { total: 0, bills: [], products: [], purchases: [], sales: [], barcodes: [], users: [] };
                    return;
                }

                this.isOpen = true;
                this.isLoading = true;

                this.debounceTimer = setTimeout(() => {
                    this.fetchLive();
                }, 200);
            },

            async fetchLive() {
                try {
                    const res = await fetch('{{ route('search.live') }}?q=' + encodeURIComponent(this.query));
                    if (res.ok) {
                        const data = await res.json();
                        this.results = data;
                        this.selectedIndex = -1;
                    }
                } catch (e) {
                    console.error('Omnisearch error:', e);
                } finally {
                    this.isLoading = false;
                }
            },

            get filteredResults() {
                if (this.activeCategory === 'bills') return this.results.bills || [];
                if (this.activeCategory === 'products') return this.results.products || [];
                if (this.activeCategory === 'purchases') return this.results.purchases || [];
                if (this.activeCategory === 'sales') return this.results.sales || [];
                if (this.activeCategory === 'barcodes') return this.results.barcodes || [];
                if (this.activeCategory === 'users') return this.results.users || [];

                // 'all': combine all up to 15 items
                return [
                    ...(this.results.bills || []),
                    ...(this.results.products || []),
                    ...(this.results.purchases || []),
                    ...(this.results.sales || []),
                    ...(this.results.barcodes || []),
                    ...(this.results.users || [])
                ].slice(0, 15);
            },

            navigateDown() {
                const list = this.filteredResults;
                if (list.length === 0) return;
                this.selectedIndex = (this.selectedIndex + 1) % list.length;
            },

            navigateUp() {
                const list = this.filteredResults;
                if (list.length === 0) return;
                this.selectedIndex = (this.selectedIndex - 1 + list.length) % list.length;
            },

            onEnter(e) {
                const list = this.filteredResults;
                if (this.selectedIndex >= 0 && this.selectedIndex < list.length) {
                    e.preventDefault();
                    window.location.href = list[this.selectedIndex].url;
                }
            },

            handleSubmit(e) {
                if (this.query.trim().length === 0) {
                    e.preventDefault();
                }
            },

            clear() {
                this.query = '';
                this.isOpen = false;
                this.selectedIndex = -1;
                this.results = { total: 0, bills: [], products: [], purchases: [], sales: [], barcodes: [], users: [] };
                this.$refs.searchInput.focus();
            },

            close() {
                this.isOpen = false;
                this.selectedIndex = -1;
            }
        };
    }
</script>
