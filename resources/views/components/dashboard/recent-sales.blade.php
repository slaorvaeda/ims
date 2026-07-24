<div class="bg-[#F8FAFC] border border-slate-100/50 p-6 rounded-3xl shadow-sm flex flex-col justify-between w-full relative overflow-hidden group hover:-translate-y-1.5 hover:shadow-lg transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-slate-800 text-sm font-sans flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3a4 4 0 0 0 0-8" />
            </svg>
            Recent Sales
        </h3>
        <a href="{{ route('sales.index') }}" class="text-[11px] text-[#FF5A36] font-bold hover:underline">See all &rsaquo;</a>
    </div>

    <!-- Sales List -->
    <div class="flex flex-col gap-4">
        <!-- Aryan Di -->
        <div class="flex items-center justify-between border-b border-slate-50 pb-3">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Aryan Di" class="w-10 h-10 rounded-full object-cover border border-slate-100">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div class="text-left leading-tight">
                    <p class="font-bold text-slate-800 text-xs">Aryan Di</p>
                    <span class="text-[10px] text-slate-400 font-semibold">01 Day Ago</span>
                </div>
            </div>
            <span class="text-green-600 font-black text-xs bg-green-50 px-2.5 py-1 rounded-full">+ $60.00</span>
        </div>

        <!-- Adil Is -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=100&q=80" alt="Adil Is" class="w-10 h-10 rounded-full object-cover border border-slate-100">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div class="text-left leading-tight">
                    <p class="font-bold text-slate-800 text-xs">Adil Is</p>
                    <span class="text-[10px] text-slate-400 font-semibold">02 Day Ago</span>
                </div>
            </div>
            <span class="text-green-600 font-black text-xs bg-green-50 px-2.5 py-1 rounded-full">+ $90.00</span>
        </div>
    </div>
</div>
