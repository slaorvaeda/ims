<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('inward-item-codes.index') }}" class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Back to list">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                    {{ __('Serial Code Details') }}
                </h2>
            </div>
            <a href="{{ route('inward-item-codes.edit', $inwardItemCode->id) }}" class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-150 flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-2xl">
        <!-- Detail Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] p-8 shadow-sm space-y-6">
            <!-- UID Badge -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Serial Code (UID)</p>
                    <span class="text-xl font-black font-mono text-slate-900 dark:text-white">{{ $inwardItemCode->uid }}</span>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800/50"></div>

            <!-- Details Grid -->
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <dt class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Product</dt>
                    <dd class="text-sm font-bold text-slate-900 dark:text-white">{{ $inwardItemCode->product->product_name ?? 'Deleted Product' }}</dd>
                    <dd class="text-xs text-slate-400 font-mono">ID: {{ $inwardItemCode->product->product_id ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Status</dt>
                    <dd>
                        @if ($inwardItemCode->status === 'Sold')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">Sold</span>
                        @elseif ($inwardItemCode->status === 'Damaged')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900/50">Damaged</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/50">{{ $inwardItemCode->status }}</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Quantity</dt>
                    <dd class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $inwardItemCode->quantity }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Updated By</dt>
                    <dd class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $inwardItemCode->updated_by ?? 'System' }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Created At</dt>
                    <dd class="text-sm text-slate-600 dark:text-slate-400">{{ $inwardItemCode->created_at->format('Y-m-d H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Last Updated</dt>
                    <dd class="text-sm text-slate-600 dark:text-slate-400">{{ $inwardItemCode->updated_at->diffForHumans() }}</dd>
                </div>
            </dl>

            <!-- Actions -->
            <div class="border-t border-slate-100 dark:border-slate-800/50 pt-6 flex items-center gap-3">
                <a href="{{ route('inward-item-codes.edit', $inwardItemCode->id) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-2xl transition-all flex items-center gap-2 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Record
                </a>
                <a href="{{ route('inward-item-codes.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-2xl transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                    Back to List
                </a>
                <form method="POST" action="{{ route('inward-item-codes.destroy', $inwardItemCode->id) }}" onsubmit="return confirm('Are you sure you want to delete this serial code?');" class="inline ml-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-sm font-semibold rounded-2xl border border-rose-100 dark:border-rose-900/50 transition-all hover:bg-rose-100 dark:hover:bg-rose-950/50">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
