@props(['stats', 'chartMonthlyPurchases', 'chartMonthlySales', 'chartMonthlyMonths'])

<div class="flex flex-col gap-3 w-full h-full justify-between">
    <!-- Top Row: Growth and Total Customer -->
    <div class="grid grid-cols-2 gap-4 w-full">
        <!-- Growth Card -->
        <div class="bg-[#F8FAFC] border border-slate-100/50 p-5 rounded-3xl shadow-sm flex flex-col justify-between items-center text-center relative overflow-hidden group h-full hover:-translate-y-1 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-center w-full mb-2">
                <span class="text-slate-400 text-xs font-bold uppercase tracking-wider font-sans">Growth</span>
                <button class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-full">
                    <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </button>
            </div>
    
            <div class="flex-1 flex items-center justify-center w-full py-1">
                <div class="relative w-22 h-22 flex items-center justify-center">
                    <!-- Circular gauge with orange and black segments -->
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <!-- Background light pink/coral circle -->
                        <circle cx="18" cy="18" r="14" stroke="#FFF0ED" stroke-width="3.5" fill="transparent"/>
                        <!-- Orange Sweeping progress arc -->
                        <circle cx="18" cy="18" r="14" stroke="#FF5A36" stroke-width="3.5" fill="transparent"
                                stroke-dasharray="88" stroke-dashoffset="26" stroke-linecap="round"/>
                        <!-- Black Sweeping indicator arc -->
                        <circle cx="18" cy="18" r="14" stroke="#0F172A" stroke-width="3.5" fill="transparent"
                                stroke-dasharray="88" stroke-dashoffset="76" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute flex flex-col items-center justify-center">
                        <span class="text-lg font-extrabold text-slate-800 font-sans tracking-tight">+32%</span>
                        <span class="text-[7.5px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Growth rate</span>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Total Customer (Stock/SKU Catalog) Card -->
        <div class="bg-[#F8FAFC] border border-slate-100/50 p-5 rounded-3xl shadow-sm flex flex-col justify-between items-start text-left relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-center w-full mb-2">
                <span class="text-slate-400 text-xs font-bold uppercase tracking-wider font-sans">Total Customer</span>
                <button class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-full">
                    <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </button>
            </div>
    
            <div class="w-full mt-1">
                <h3 class="text-3xl font-black text-slate-800 font-sans tracking-tight">
                    3526
                </h3>
                
                <!-- Styled Progress Bar with stripes -->
                <div class="w-full bg-slate-50 h-5 rounded-full mt-3 overflow-hidden p-1 border border-slate-100">
                    <div class="bg-stripe-orange h-full rounded-full w-[65%]" title="Fulfillment: 65%"></div>
                </div>
            </div>
    
            <button @click="showStockModal = true" class="w-full text-center mt-3 py-1.5 text-slate-500 hover:text-slate-800 text-xs font-extrabold rounded-xl hover:bg-slate-50 transition-colors">
                View Details
            </button>
        </div>
    </div>
    
    <!-- Bottom Row: Engagement Rate (Reate) -->
    <div class="bg-[#F8FAFC] border border-slate-100/50 p-6 rounded-3xl shadow-sm flex flex-col justify-between text-left relative overflow-hidden group w-full mt-0 hover:-translate-y-1.5 hover:shadow-lg transition-all duration-300">
    <div class="flex justify-between items-center w-full mb-6">
        <h4 class="font-bold text-slate-800 text-sm font-sans flex items-center gap-1.5">
            <svg class="w-4.5 h-4.5 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Engagement Rate
        </h4>
        <div class="flex items-center gap-1 bg-slate-50 p-0.5 rounded-lg text-[9px] font-bold text-slate-500 border border-slate-100">
            <span class="px-2.5 py-1 bg-white rounded shadow-sm text-slate-800">Monthly</span>
            <span class="px-2.5 py-1">Annually</span>
        </div>
    </div>

    <!-- Column Chart with stripes and highlighted column -->
    <div class="w-full">
        <!-- Grid and columns -->
        <div class="relative h-32 flex items-end justify-between gap-3 px-2">
            <!-- Y-Axis labels (Absolute background lines) -->
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[9px] font-bold text-slate-300">
                <div class="border-b border-dashed border-slate-100 w-full pb-0.5 text-right">12%</div>
                <div class="border-b border-dashed border-slate-100 w-full pb-0.5 text-right">10%</div>
                <div class="border-b border-dashed border-slate-100 w-full pb-0.5 text-right">7.5%</div>
                <div class="border-b border-dashed border-slate-100 w-full pb-0.5 text-right">5%</div>
                <div class="border-b border-dashed border-slate-100 w-full pb-0.5 text-right">2.5%</div>
                <div class="w-full text-right">0%</div>
            </div>

            <!-- Jan (Stripe) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <div class="w-6 rounded-t-lg bg-stripe-medium border border-[#FF5A36]/15 h-[25%] hover:scale-105 transition-transform duration-150 cursor-pointer"></div>
            </div>
            <!-- Feb (Stripe) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <div class="w-6 rounded-t-lg bg-stripe-medium border border-[#FF5A36]/15 h-[35%] hover:scale-105 transition-transform duration-150 cursor-pointer"></div>
            </div>
            <!-- Mar (Stripe) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <div class="w-6 rounded-t-lg bg-stripe-medium border border-[#FF5A36]/15 h-[50%] hover:scale-105 transition-transform duration-150 cursor-pointer"></div>
            </div>
            <!-- Apr (Stripe) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <div class="w-6 rounded-t-lg bg-stripe-medium border border-[#FF5A36]/15 h-[40%] hover:scale-105 transition-transform duration-150 cursor-pointer"></div>
            </div>
            <!-- May (SOLID HIGHLIGHTED WITH TOOLTIP) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <!-- Tooltip sitting on top of the 68% high bar -->
                <div class="absolute bottom-[68%] left-1/2 transform -translate-x-1/2 flex flex-col items-center z-20 mb-1">
                    <div class="bg-slate-900 text-white rounded-xl py-1 px-2.5 shadow-md flex flex-col items-center leading-none text-center">
                        <span class="bg-[#10B981] text-white text-[7px] font-black px-1 py-0.5 rounded-full mb-1">+12.8%</span>
                        <span class="text-[8px] text-slate-400 font-semibold font-sans">April, 2023</span>
                        <span class="text-[10px] font-black text-white mt-0.5">379,502</span>
                    </div>
                    <!-- Tooltip arrow -->
                    <div class="w-2 h-2 bg-slate-900 transform rotate-45 -mt-1 shadow-sm"></div>
                    <!-- Indicator Dot -->
                    <div class="w-3 h-3 rounded-full bg-white border-2 border-slate-900 -mt-1 shadow flex items-center justify-center">
                        <div class="w-1 h-1 rounded-full bg-slate-900"></div>
                    </div>
                </div>
                <!-- Column bar -->
                <div class="w-6 rounded-t-lg bg-[#FF5A36] h-[68%] hover:scale-105 transition-transform duration-150 cursor-pointer shadow-lg shadow-[#FF5A36]/10"></div>
            </div>
            <!-- Jun (Stripe) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <div class="w-6 rounded-t-lg bg-stripe-medium border border-[#FF5A36]/15 h-[42%] hover:scale-105 transition-transform duration-150 cursor-pointer"></div>
            </div>
            <!-- Jul (Stripe) -->
            <div class="flex-1 flex flex-col items-center relative h-full justify-end z-10">
                <div class="w-6 rounded-t-lg bg-stripe-medium border border-[#FF5A36]/15 h-[52%] hover:scale-105 transition-transform duration-150 cursor-pointer"></div>
            </div>
        </div>

        <!-- Axis labels -->
        <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-3 px-3">
            <span>Jan</span>
            <span>Feb</span>
            <span>Mar</span>
            <span>Apr</span>
            <span>May</span>
            <span>Jun</span>
            <span>Jul</span>
        </div>
    </div>
</div>
</div>
