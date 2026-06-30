<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Log New Purchase Order') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('purchases.store') }}" class="space-y-6">
                @csrf

                <!-- Product Selection -->
                <div>
                    <label for="product_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Select Product</label>
                    <select 
                        id="product_id" 
                        name="product_id" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                        <option value="">-- Choose a Product --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-brand-id="{{ $product->brand_id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->product_name }} ({{ $product->product_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand Selection -->
                <div>
                    <label for="brand_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Select Brand</label>
                    <select 
                        id="brand_id" 
                        name="brand_id" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                        <option value="">-- Choose a Brand --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }} ({{ $brand->sub ?: 'No Subtitle' }})
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Purchase Date</label>
                    <input 
                        type="date" 
                        id="date" 
                        name="date" 
                        value="{{ old('date', date('Y-m-d')) }}" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('date')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vendor ID -->
                <div>
                    <label for="vendor_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Vendor ID</label>
                    <input 
                        type="text" 
                        id="vendor_id" 
                        name="vendor_id" 
                        value="{{ old('vendor_id') }}" 
                        placeholder="e.g., a1, v-901" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('vendor_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity & Price Container (Grid) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Quantity</label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            value="{{ old('quantity') }}" 
                            placeholder="e.g., 10" 
                            required 
                            min="1"
                            class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                        >
                        @error('quantity')
                            <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Unit Price (₹)</label>
                        <input 
                            type="number" 
                            step="0.01"
                            id="price" 
                            name="price" 
                            value="{{ old('price') }}" 
                            placeholder="e.g., 20.00" 
                            required 
                            min="0"
                            class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                        >
                        @error('price')
                            <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Starting UID & Autocomplete control -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="start_uid" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Starting UID / Serial Code</label>
                        <input 
                            type="text" 
                            id="start_uid" 
                            name="start_uid" 
                            value="{{ old('start_uid') }}" 
                            placeholder="Select a product to auto-fill" 
                            required 
                            readonly
                            oninput="generateSequencePreview()"
                            class="w-full px-5 py-4 bg-slate-100 dark:bg-slate-950/20 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-500 dark:text-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                        >
                        @error('start_uid')
                            <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                        @enderror

                        <!-- Checkbox to unfreeze / enable manual edit -->
                        <div class="flex items-center gap-2.5 mt-3 select-none">
                            <input 
                                type="checkbox" 
                                id="manual_uid_check" 
                                onchange="toggleManualUid()"
                                class="w-4 h-4 text-indigo-600 bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 rounded focus:ring-indigo-500 focus:ring-2"
                            >
                            <label for="manual_uid_check" class="text-xs font-bold text-slate-500 dark:text-slate-400 cursor-pointer">
                                Edit starting UID manually
                            </label>
                        </div>
                    </div>

                    <!-- Inward Inventory Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Inward Inventory Status</label>
                        <select 
                            id="status" 
                            name="status" 
                            required 
                            class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                        >
                            <option value="Good Inventory" {{ old('status', 'Good Inventory') == 'Good Inventory' ? 'selected' : '' }}>Good Inventory</option>
                            <option value="Damaged" {{ old('status') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="Returned" {{ old('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        @error('status')
                            <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Live Preview of Generated UIDs -->
                <div class="p-5 bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/60 rounded-3xl space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Live Serial Codes Preview</p>
                    <div id="uids-preview-container" class="flex flex-wrap gap-2 max-h-40 overflow-y-auto p-2 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                        <span class="text-xs text-slate-400 italic">Select a product and enter quantity to preview list...</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('purchases.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md">
                        Save Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to dynamically generate UID preview sequence and autofill -->
    <script>
        function toggleManualUid() {
            const checkBox = document.getElementById('manual_uid_check');
            const uidInput = document.getElementById('start_uid');

            if (checkBox.checked) {
                // Enable manual edit
                uidInput.removeAttribute('readonly');
                uidInput.classList.remove('bg-slate-100', 'dark:bg-slate-950/20', 'text-slate-500', 'dark:text-slate-400');
                uidInput.classList.add('bg-slate-50', 'dark:bg-slate-950/60', 'text-slate-800', 'dark:text-slate-200');
            } else {
                // Freeze again and restore auto-filled value
                uidInput.setAttribute('readonly', 'readonly');
                uidInput.classList.add('bg-slate-100', 'dark:bg-slate-950/20', 'text-slate-500', 'dark:text-slate-400');
                uidInput.classList.remove('bg-slate-50', 'dark:bg-slate-950/60', 'text-slate-800', 'dark:text-slate-200');
                
                fetchNextUid();
            }
        }

        function fetchNextUid() {
            const brandSelect = document.getElementById('brand_id');
            const uidInput = document.getElementById('start_uid');
            const checkBox = document.getElementById('manual_uid_check');

            if (checkBox.checked) {
                return;
            }

            const brandId = brandSelect.value;
            if (!brandId) {
                uidInput.value = '';
                generateSequencePreview();
                return;
            }

            fetch(`{{ route('purchases.next-uid') }}?brand_id=${brandId}`)
                .then(response => response.json())
                .then(data => {
                    if (!checkBox.checked) {
                        uidInput.value = data.next_uid;
                        generateSequencePreview();
                    }
                })
                .catch(error => {
                    console.error('Error fetching next UID:', error);
                });
        }

        function generateSequencePreview() {
            const startUidInput = document.getElementById('start_uid');
            const qtyInput = document.getElementById('quantity');
            const previewContainer = document.getElementById('uids-preview-container');

            const startUid = startUidInput.value.trim();
            const qty = parseInt(qtyInput.value) || 0;

            if (!startUid || qty <= 0) {
                previewContainer.innerHTML = '<span class="text-xs text-slate-400 italic">Select a brand and enter quantity to preview list...</span>';
                return;
            }

            // Extract trailing numbers
            const match = startUid.match(/^(.*?)(\d+)$/);
            let prefix = startUid;
            let startNum = 1;
            let padLength = 1;

            if (match) {
                prefix = match[1];
                const numberStr = match[2];
                startNum = parseInt(numberStr);
                padLength = numberStr.length;
            }

            let html = '';
            // Limit preview to first 100 to avoid locking page on large counts
            const maxPreview = Math.min(qty, 100);

            for (let i = 0; i < maxPreview; i++) {
                const currentNum = startNum + i;
                const currentNumStr = String(currentNum).padStart(padLength, '0');
                const finalUid = prefix + currentNumStr;

                html += `<span class="px-2.5 py-1.5 bg-slate-100 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-xl font-mono text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm">${finalUid}</span>`;
            }

            if (qty > 100) {
                html += `<span class="text-xs text-slate-400 dark:text-slate-500 italic self-center pl-2">+ ${qty - 100} more items...</span>`;
            }

            previewContainer.innerHTML = html;
        }

        function handleProductChange() {
            const productSelect = document.getElementById('product_id');
            const brandSelect = document.getElementById('brand_id');
            
            if (productSelect.selectedIndex > 0) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const brandId = selectedOption.getAttribute('data-brand-id');
                if (brandId) {
                    brandSelect.value = brandId;
                    fetchNextUid();
                }
            }
        }

        // Attach listeners and setup page load
        window.addEventListener('DOMContentLoaded', () => {
            const productSelect = document.getElementById('product_id');
            const brandSelect = document.getElementById('brand_id');
            const qtyInput = document.getElementById('quantity');
            
            productSelect.addEventListener('change', handleProductChange);
            brandSelect.addEventListener('change', fetchNextUid);
            qtyInput.addEventListener('input', generateSequencePreview);
            
            // Run initially if fields are already filled (e.g. from validation error/old input)
            fetchNextUid();
            generateSequencePreview();
        });
    </script>
</x-app-layout>
