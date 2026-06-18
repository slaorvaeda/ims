<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Register Inward Item Serial Code') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('inward-item-codes.store') }}" class="space-y-6">
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
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->product_name }} ({{ $product->product_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Starting UID -->
                <div>
                    <label for="start_uid" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Starting UID / Serial Code</label>
                    <input 
                        type="text" 
                        id="start_uid" 
                        name="start_uid" 
                        value="{{ old('start_uid') }}" 
                        placeholder="e.g., Zig0001" 
                        required 
                        oninput="generateSequencePreview()"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    @error('start_uid')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity (Batch Count) -->
                <div>
                    <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Quantity (Batch Count)</label>
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        value="{{ old('quantity', 1) }}" 
                        required 
                        min="1"
                        max="1000"
                        oninput="generateSequencePreview()"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('quantity')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Live Preview of Generated UIDs -->
                <div class="p-5 bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/60 rounded-3xl space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Live Serial Codes Preview</p>
                    <div id="uids-preview-container" class="flex flex-wrap gap-2 max-h-40 overflow-y-auto p-2 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                        <span class="text-xs text-slate-400 italic">Enter Starting UID and Quantity to preview list...</span>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Inventory Status</label>
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

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('inward-item-codes.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md">
                        Register Inward
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to dynamically generate UID preview sequence -->
    <script>
        function generateSequencePreview() {
            const startUidInput = document.getElementById('start_uid');
            const qtyInput = document.getElementById('quantity');
            const previewContainer = document.getElementById('uids-preview-container');

            const startUid = startUidInput.value.trim();
            const qty = parseInt(qtyInput.value) || 0;

            if (!startUid || qty <= 0) {
                previewContainer.innerHTML = '<span class="text-xs text-slate-400 italic">Enter Starting UID and Quantity to preview list...</span>';
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

        // Run once on load
        window.addEventListener('DOMContentLoaded', generateSequencePreview);
    </script>
</x-app-layout>
