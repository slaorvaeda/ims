<div class="bg-[#F8FAFC] border border-slate-100/50 p-6 rounded-3xl shadow-sm h-full flex flex-col justify-between text-left hover:-translate-y-1.5 hover:shadow-lg transition-all duration-300">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Time Visit in hours
        </h4>
        <button class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 bg-slate-50 hover:bg-slate-100 rounded-full">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </button>
    </div>

    <!-- Legend bar -->
    <div class="flex items-center gap-4 text-[9px] font-bold text-slate-400 mb-6">
        <span class="flex items-center gap-1.5">
            <span class="w-5 h-2.5 rounded bg-stripe-light border border-[#FF5A36]/10"></span>
            &gt;500
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-5 h-2.5 rounded bg-stripe-medium border border-[#FF5A36]/20"></span>
            &gt;1,000
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-5 h-2.5 rounded bg-[#FF5A36]/50"></span>
            &gt;2,000
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-5 h-2.5 rounded bg-[#FF5A36]"></span>
            &gt;3,000
        </span>
    </div>

    <!-- Heatmap Grid Mockup -->
    <div class="overflow-x-auto pb-1 flex-1 flex flex-col justify-between">
        <div class="flex flex-col gap-2 min-w-[320px]">
            <!-- 2am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">2am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                    <div class="h-8 rounded-lg bg-slate-50 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                    <div class="h-8 rounded-lg bg-slate-50 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                    <div class="h-8 rounded-lg bg-slate-50 transition-all hover:ring-2 hover:ring-[#FF5A36]/30"></div>
                </div>
            </div>
            <!-- 1am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">1am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-stripe-medium border border-[#FF5A36]/10"></div>
                    <div class="h-8 rounded-lg bg-stripe-medium border border-[#FF5A36]/10"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-stripe-medium border border-[#FF5A36]/10"></div>
                    <div class="h-8 rounded-lg bg-stripe-medium border border-[#FF5A36]/10"></div>
                </div>
            </div>
            <!-- 12am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">12am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-stripe-medium border border-[#FF5A36]/10"></div>
                </div>
            </div>
            <!-- 11am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">11am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                </div>
            </div>
            <!-- 10am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">10am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                </div>
            </div>
            <!-- 9am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">9am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-[#FF5A36]/50"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                </div>
            </div>
            <!-- 8am -->
            <div class="flex items-center gap-3">
                <span class="w-8 shrink-0 text-slate-400 text-right text-[10px] font-semibold">8am</span>
                <div class="flex-1 grid grid-cols-7 gap-1.5">
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                    <div class="h-8 rounded-lg bg-stripe-light border border-[#FF5A36]/5"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                    <div class="h-8 rounded-lg bg-slate-50"></div>
                </div>
            </div>
            
            <!-- Horizontal days axis -->
            <div class="flex items-center gap-3 mt-1.5">
                <span class="w-8 shrink-0"></span>
                <div class="flex-1 grid grid-cols-7 gap-1.5 text-center text-[10px] font-bold text-slate-400">
                    <span>Sun</span>
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                </div>
            </div>
        </div>
    </div>
</div>
