<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-slate-850 dark:text-white leading-tight">
                    {{ __('Connected Store Channels') }}
                </h2>
                <p class="text-xs font-semibold text-slate-450 dark:text-slate-500 mt-1">
                    Manage API integrations and secure credentials for Amazon SP-API and Flipkart.
                </p>
            </div>
            
            <button 
                @click="$dispatch('open-add-store-modal')"
                class="px-5 py-2.5 bg-[#FF5A36] hover:bg-[#E04826] text-white text-xs font-extrabold rounded-2xl transition-all shadow-md shadow-[#FF5A36]/15 flex items-center gap-2 self-start sm:self-auto"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Connect Store API
            </button>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Success Alert Notification -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50/80 dark:bg-emerald-950/20 border border-emerald-100/50 dark:border-emerald-900/40 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs font-bold shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Alert Notification -->
        @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-rose-50/80 dark:bg-rose-950/20 border border-rose-100/50 dark:border-rose-900/40 rounded-2xl text-rose-800 dark:text-rose-300 text-xs font-bold shadow-sm">
                <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="space-y-1">
                    <p class="font-extrabold">Failed to save store connections:</p>
                    <ul class="list-disc pl-4 font-semibold text-[11px] space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Stores Card Grid -->
        @if($stores->isEmpty())
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-850 rounded-3xl flex items-center justify-center mx-auto border border-slate-200/50 dark:border-slate-800/60 mb-5 text-slate-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="font-extrabold text-slate-850 dark:text-white text-base leading-snug tracking-tight">No Connected Stores</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold max-w-sm mx-auto mt-2.5">
                    Connect your Amazon Selling Partner API (SP-API) or Flipkart Seller API credentials to activate automated stock synchronization.
                </p>
                <button 
                    @click="$dispatch('open-add-store-modal')"
                    class="px-5 py-2.5 bg-[#FF5A36] hover:bg-[#E04826] text-white text-xs font-extrabold rounded-2xl transition-all shadow-md shadow-[#FF5A36]/15 flex items-center gap-2 mx-auto mt-6"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Connect Your First Store
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stores as $store)
                    <x-stores.store-card :store="$store" :portals="$portals" />
                @endforeach
            </div>
        @endif

        <!-- Dynamic Store Modals -->
        <x-stores.config-form :portals="$portals" />
    </div>
</x-app-layout>
