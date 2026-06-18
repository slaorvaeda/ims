<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Dispatch Stock Unit') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('dispatch-item-codes.store') }}" class="space-y-6">
                @csrf

                <!-- UID / Serial Selection -->
                <div>
                    <label for="uid_select" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Select Serial Code (UID) to Dispatch</label>
                    <select 
                        id="uid_select" 
                        name="uid" 
                        required 
                        onchange="updateProductSelection()"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                        <option value="">-- Choose a UID from Stock --</option>
                        @foreach ($availableInwardItems as $item)
                            <option value="{{ $item->uid }}" data-product-id="{{ $item->product_id }}" {{ old('uid') == $item->uid ? 'selected' : '' }}>
                                {{ $item->uid }} ({{ $item->product->product_name ?? 'Product' }})
                            </option>
                        @endforeach
                    </select>
                    @error('uid')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Selection (Readonly/automatically selected based on UID) -->
                <div>
                    <label for="product_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Linked Product</label>
                    <select 
                        id="product_id" 
                        name="product_id" 
                        required 
                        class="w-full px-5 py-4 bg-slate-100 dark:bg-slate-950/20 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-500 dark:text-slate-400 text-sm cursor-not-allowed outline-none"
                    >
                        <option value="">-- Autofilled Product --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->product_name }} ({{ $product->product_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity (-1) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Quantity</label>
                    <input 
                        type="text" 
                        value="-1 (Automatic)" 
                        readonly 
                        class="w-full px-5 py-4 bg-slate-100 dark:bg-slate-950/20 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-500 dark:text-slate-400 text-sm cursor-not-allowed outline-none"
                    >
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Dispatch Status</label>
                    <select 
                        id="status" 
                        name="status" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                        <option value="Sold" {{ old('status', 'Sold') == 'Sold' ? 'selected' : '' }}>Sold</option>
                        <option value="Dispatched" {{ old('status') == 'Dispatched' ? 'selected' : '' }}>Dispatched</option>
                        <option value="Damaged Out" {{ old('status') == 'Damaged Out' ? 'selected' : '' }}>Damaged Out</option>
                    </select>
                    @error('status')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('dispatch-item-codes.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md">
                        Record Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to automatically select product based on UID selection -->
    <script>
        function updateProductSelection() {
            const uidSelect = document.getElementById('uid_select');
            const productSelect = document.getElementById('product_id');
            
            const selectedOption = uidSelect.options[uidSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.productId) {
                productSelect.value = selectedOption.dataset.productId;
            } else {
                productSelect.value = "";
            }
        }
        
        // Run once on load to populate if old input exists
        window.addEventListener('DOMContentLoaded', updateProductSelection);
    </script>
</x-app-layout>
