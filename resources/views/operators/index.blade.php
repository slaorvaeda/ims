<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('Operator Management') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 rounded-full border border-indigo-100/50 dark:border-indigo-900/50 shadow-sm">
                Brands, Portals & Vendors
            </span>
        </div>
    </x-slot>

    <div class="space-y-8" x-data="{
        showCreateBrandModal: false,
        showCreatePortalVendorModal: false,
        showEditBrandModal: false,
        brandToEdit: { id: '', name: '', sub: '' },
        showEditPortalVendorModal: false,
        portalVendorToEdit: { id: '', name: '', type: 'Portal' }
    }">
        
        <!-- Top Control Bar -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-heading font-bold text-base text-slate-800 dark:text-white">Quick Actions</h3>
                <p class="text-xs text-slate-400 mt-1">Create new brands or portals/vendors instantly</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <button @click="showCreateBrandModal = true" class="flex-1 sm:flex-none px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create Brand
                </button>
                <button @click="showCreatePortalVendorModal = true" class="flex-1 sm:flex-none px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-emerald-900/10 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create Portal / Vendor
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl text-emerald-800 dark:text-emerald-400 text-xs font-semibold shadow-sm flex items-center gap-2 animate-in fade-in duration-150">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Layout for 2 Boxes (Left and Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Left Box: Brand Management -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <div>
                        <h4 class="font-heading font-bold text-base text-slate-800 dark:text-white uppercase tracking-wider">
                            Brand Directory
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">Total registered brands: {{ $brands->count() }}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800/80">
                    <div class="max-h-[500px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="py-3 px-4">Brand Name</th>
                                    <th class="py-3 px-4">Subtitle</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                @forelse($brands as $brand)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">{{ $brand->name }}</td>
                                        <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 font-medium">{{ $brand->sub ?: '-' }}</td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="brandToEdit = { id: '{{ $brand->id }}', name: '{{ addslashes($brand->name) }}', sub: '{{ addslashes($brand->sub) }}' }; showEditBrandModal = true" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition-all" title="Edit Brand">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <form action="{{ route('operators.destroy-brand', $brand) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this brand?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 hover:text-rose-900 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-all" title="Delete Brand">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                            No brands registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Box: Portal / Vendor Management -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <div>
                        <h4 class="font-heading font-bold text-base text-slate-800 dark:text-white uppercase tracking-wider">
                            Portals & Vendors Directory
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">Total registered partners: {{ $portalVendors->count() }}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800/80">
                    <div class="max-h-[500px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                    <th class="py-3 px-4">Partner Name</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                @forelse($portalVendors as $pv)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">{{ $pv->name }}</td>
                                        <td class="py-3.5 px-4">
                                            @if($pv->type === 'Portal')
                                                <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-bold border border-blue-100/50 dark:border-blue-900/40">
                                                    {{ $pv->type }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-bold border border-emerald-100/50 dark:border-emerald-900/40">
                                                    {{ $pv->type }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="portalVendorToEdit = { id: '{{ $pv->id }}', name: '{{ addslashes($pv->name) }}', type: '{{ $pv->type }}' }; showEditPortalVendorModal = true" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition-all" title="Edit Partner">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <form action="{{ route('operators.destroy-portal-vendor', $pv) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this partner?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 hover:text-rose-900 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-all" title="Delete Partner">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                            No portals or vendors registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Create Brand Modal -->
        <div x-show="showCreateBrandModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showCreateBrandModal = false" class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Register New Brand</h3>
                    <button @click="showCreateBrandModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('operators.store-brand') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Brand Name</label>
                        <input type="text" name="name" required placeholder="e.g. Puma, Apple, Samsung" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Subtitle</label>
                        <input type="text" name="sub" placeholder="e.g. Originals, Performance, Air" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="showCreateBrandModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10">
                            Register Brand
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Brand Modal -->
        <div x-show="showEditBrandModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showEditBrandModal = false" class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Edit Brand</h3>
                    <button @click="showEditBrandModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="`{{ url('/operators/brands') }}/${brandToEdit.id}`" method="POST" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Brand Name</label>
                        <input type="text" name="name" required x-model="brandToEdit.name" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Subtitle</label>
                        <input type="text" name="sub" x-model="brandToEdit.sub" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditBrandModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10">
                            Update Brand
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Create Portal / Vendor Modal -->
        <div x-show="showCreatePortalVendorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showCreatePortalVendorModal = false" class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Register New Portal or Vendor</h3>
                    <button @click="showCreatePortalVendorModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('operators.store-portal-vendor') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Partner Name</label>
                        <input type="text" name="name" required placeholder="e.g. Amazon, Flipkart, Vendor A" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Partner Type</label>
                        <select name="type" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                            <option value="Portal">Portal</option>
                            <option value="Vendor">Vendor</option>
                        </select>
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="showCreatePortalVendorModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-emerald-900/10">
                            Register Partner
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Portal / Vendor Modal -->
        <div x-show="showEditPortalVendorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm animate-in fade-in duration-200" x-cloak style="display: none;">
            <div @click.away="showEditPortalVendorModal = false" class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-xl flex flex-col animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Edit Portal or Vendor</h3>
                    <button @click="showEditPortalVendorModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="`{{ url('/operators/portal-vendors') }}/${portalVendorToEdit.id}`" method="POST" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Partner Name</label>
                        <input type="text" name="name" required x-model="portalVendorToEdit.name" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Partner Type</label>
                        <select name="type" required x-model="portalVendorToEdit.type" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-700 dark:text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition-all">
                            <option value="Portal">Portal</option>
                            <option value="Vendor">Vendor</option>
                        </select>
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditPortalVendorModal = false" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-xs transition-all shadow-md shadow-indigo-900/10">
                            Update Partner
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
