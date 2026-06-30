<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Edit Purchase Record #') }}{{ $purchase->id }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

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
                            <option value="{{ $product->id }}" data-brand-id="{{ $product->brand_id }}" {{ old('product_id', $purchase->product_id) == $product->id ? 'selected' : '' }}>
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
                            <option value="{{ $brand->id }}" {{ old('brand_id', $purchase->brand_id) == $brand->id ? 'selected' : '' }}>
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
                        value="{{ old('date', $purchase->date) }}" 
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
                        value="{{ old('vendor_id', $purchase->vendor_id) }}" 
                        placeholder="e.g., a1, v-901" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('vendor_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity & Price Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Quantity</label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            value="{{ old('quantity', $purchase->quantity) }}" 
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
                            value="{{ old('price', $purchase->price) }}" 
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

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('purchases.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md">
                        Update Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productSelect = document.getElementById('product_id');
            const brandSelect = document.getElementById('brand_id');

            productSelect.addEventListener('change', () => {
                if (productSelect.selectedIndex > 0) {
                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                    const brandId = selectedOption.getAttribute('data-brand-id');
                    if (brandId) {
                        brandSelect.value = brandId;
                    }
                }
            });
        });
    </script>
