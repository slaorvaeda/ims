@props(['store', 'portals'])

<div class="relative bg-white/95 dark:bg-slate-900/95 border border-slate-200/60 dark:border-slate-800/80 rounded-3xl p-6 shadow-sm hover:shadow-md hover:border-slate-350 dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between min-h-[220px]">
    
    <!-- Top Row: Portal Badge & Status -->
    <div class="flex justify-between items-start">
        <div class="flex items-center gap-3">
            @php
                $portalName = strtoupper($store->portalVendor->name ?? '');
                $isAmazon = str_contains($portalName, 'AMAZON');
                $isFlipkart = str_contains($portalName, 'FLIPKART');
            @endphp

            @if($isAmazon)
                <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center border border-amber-100 dark:border-amber-900/50">
                    <span class="text-lg">a</span>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-600 dark:text-amber-400">Amazon SP-API</span>
                    <h4 class="font-extrabold text-slate-850 dark:text-white text-base leading-snug tracking-tight">{{ $store->store_name }}</h4>
                </div>
            @elseif($isFlipkart)
                <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                    <span class="text-lg">F</span>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600 dark:text-blue-400">Flipkart Portal</span>
                    <h4 class="font-extrabold text-slate-850 dark:text-white text-base leading-snug tracking-tight">{{ $store->store_name }}</h4>
                </div>
            @else
                <div class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $store->portalVendor->name ?? 'Custom' }}</span>
                    <h4 class="font-bold text-slate-850 dark:text-white text-base leading-snug tracking-tight">{{ $store->store_name }}</h4>
                </div>
            @endif
        </div>

        <!-- Status Pill -->
        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-xl transition-all duration-200 {{ $store->status === 'active' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/30' : 'bg-slate-50 text-slate-400 dark:bg-slate-850 dark:text-slate-500 border border-slate-100 dark:border-slate-800' }}">
            {{ $store->status }}
        </span>
    </div>

    <!-- Active Integrations / APIs list -->
    <div class="my-5">
        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Active Integrations</span>
        <div class="flex flex-wrap gap-2.5 mt-2">
            @php
                $apis = $store->enabled_apis ?: [];
            @endphp
            
            <div class="flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ in_array('orders', $apis) ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 border border-slate-200/50 dark:border-slate-700/60' : 'bg-slate-50 text-slate-350 dark:bg-slate-900/50 dark:text-slate-700' }}">
                <span class="{{ in_array('orders', $apis) ? 'text-emerald-500' : 'text-slate-300 dark:text-slate-800' }}">●</span> Orders Sync
            </div>
            
            <div class="flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ in_array('inventory', $apis) ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 border border-slate-200/50 dark:border-slate-700/60' : 'bg-slate-50 text-slate-350 dark:bg-slate-900/50 dark:text-slate-700' }}">
                <span class="{{ in_array('inventory', $apis) ? 'text-emerald-500' : 'text-slate-300 dark:text-slate-800' }}">●</span> Inventory Sync
            </div>

            <div class="flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ in_array('pricing', $apis) ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 border border-slate-200/50 dark:border-slate-700/60' : 'bg-slate-50 text-slate-350 dark:bg-slate-900/50 dark:text-slate-700' }}">
                <span class="{{ in_array('pricing', $apis) ? 'text-emerald-500' : 'text-slate-300 dark:text-slate-800' }}">●</span> Pricing Track
            </div>
        </div>
    </div>

    <!-- Bottom Actions Row -->
    <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800/80 pt-4 mt-auto">
        <span class="text-[9px] text-slate-400 dark:text-slate-500 font-medium">Synced: {{ $store->updated_at->diffForHumans() }}</span>
        
        <div class="flex gap-2">
            <!-- Edit Trigger Button -->
            <button 
                @click="$dispatch('open-store-modal', { 
                    id: {{ $store->id }}, 
                    name: '{{ addslashes($store->store_name) }}',
                    portal_id: {{ $store->portal_vendor_id }},
                    status: '{{ $store->status }}',
                    enabled_apis: {{ json_encode($apis) }},
                    credentials: {{ json_encode($store->credentials ?: []) }}
                })"
                class="p-2 text-slate-400 hover:text-[#FF5A36] dark:hover:text-[#FF5A36] hover:bg-slate-50 dark:hover:bg-slate-850 rounded-xl transition-all"
                title="Edit Configuration"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>

            <!-- Delete Form -->
            <form action="{{ route('stores.destroy', $store->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this store configuration?');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="p-2 text-slate-400 hover:text-rose-500 dark:hover:text-rose-500 hover:bg-slate-50 dark:hover:bg-slate-850 rounded-xl transition-all"
                    title="Delete Store"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
    </div>

</div>
