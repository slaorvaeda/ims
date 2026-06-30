<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Form Container Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
                @csrf

                <!-- Product ID -->
                <div>
                    <label for="product_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Product ID (Unique Identifier)</label>
                    <input 
                        type="text" 
                        id="product_id" 
                        name="product_id" 
                        value="{{ old('product_id') }}" 
                        placeholder="e.g., A, B, PRD-101" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('product_id')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Name -->
                <div>
                    <label for="product_name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Product Name</label>
                    <input 
                        type="text" 
                        id="product_name" 
                        name="product_name" 
                        value="{{ old('product_name') }}" 
                        placeholder="e.g., Premium Wireless Headphones" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('product_name')
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

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">SKU (Stock Keeping Unit)</label>
                    <input 
                        type="text" 
                        id="sku" 
                        name="sku" 
                        value="{{ old('sku') }}" 
                        placeholder="e.g., WH-1000XM4-BLK" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    @error('sku')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- FSN -->
                <div>
                    <label for="fsn" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">FSN (Flipkart Serial Number - Optional)</label>
                    <input 
                        type="text" 
                        id="fsn" 
                        name="fsn" 
                        value="{{ old('fsn') }}" 
                        placeholder="e.g., FSN-123456" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    @error('fsn')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ASIN -->
                <div>
                    <label for="asin" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">ASIN (Amazon Standard Identification Number - Optional)</label>
                    <input 
                        type="text" 
                        id="asin" 
                        name="asin" 
                        value="{{ old('asin') }}" 
                        placeholder="e.g., B0863TXGM3" 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    @error('asin')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions Form Button Panel -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('products.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
