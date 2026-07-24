@props(['stats'])

<div class="bg-white border border-slate-100/50 p-6 rounded-3xl shadow-sm flex flex-col justify-between h-full relative overflow-hidden group hover:-translate-y-1.5 hover:shadow-lg transition-all duration-300">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-extrabold text-slate-800 text-base font-sans flex items-center gap-2">
            <!-- Same Bar Chart Icon as in target image -->
            <svg class="w-5 h-5 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                <rect x="4" y="11" width="3.5" height="9" rx="1"></rect>
                <rect x="10.25" y="6" width="3.5" height="14" rx="1"></rect>
                <rect x="16.5" y="2" width="3.5" height="18" rx="1"></rect>
            </svg>
            Sales Overview
        </h3>
        <button class="text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 bg-slate-50 hover:bg-slate-100 rounded-full transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
        </button>
    </div>

    <!-- Main Content Row -->
    <div class="flex flex-row items-stretch gap-6 flex-1">
        <!-- Left Stats (Stacked Column) -->
        <div class="flex flex-col gap-3 w-[36%] shrink-0 justify-between">
            <!-- Number of Sales -->
            <div class="bg-[#F8FAFC] border border-slate-100/30 p-4 rounded-2xl flex flex-col justify-between items-start text-left flex-1">
                <div class="flex justify-between items-start w-full">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider font-sans leading-tight">Number of<br>Sales</span>
                    <span class="bg-[#FF5A36] text-white text-[8px] font-black px-1.5 py-0.5 rounded-full whitespace-nowrap flex items-center gap-0.5">
                        3.5% <span>&nearr;</span>
                    </span>
                </div>
                <h4 class="text-2xl font-black text-slate-800 font-sans tracking-tight mt-2 leading-none">
                    {{ number_format($stats['total_sales']) }}
                </h4>
            </div>

            <!-- Total Sales -->
            <div class="bg-[#F8FAFC] border border-slate-100/30 p-4 rounded-2xl flex flex-col justify-between items-start text-left flex-1">
                <div class="flex justify-between items-start w-full">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider font-sans leading-tight">Total Sales</span>
                    <span class="bg-slate-900 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full whitespace-nowrap flex items-center gap-0.5">
                        4.5% <span>&nearr;</span>
                    </span>
                </div>
                <h4 id="stat-total-dispatch" class="text-2xl font-black text-slate-800 font-sans tracking-tight mt-2 leading-none">
                    {{ number_format($stats['total_dispatch']) }}
                </h4>
            </div>
        </div>

        <!-- Right Semi-Circle Gauge -->
        <div class="flex-1 flex flex-col items-center justify-center relative self-center">
            <!-- Curved tachometer meter SVG -->
            <div class="relative w-full flex items-center justify-center">
                <svg class="w-full max-w-[215px] h-auto overflow-visible" viewBox="0 0 100 62">
                    @php
                        $numTicks = 27;
                        $centerX = 50;
                        $centerY = 55;
                        $rInner = 31;
                        $rOuter = 43;
                        $activeCount = round($numTicks * 0.672); // 18 ticks active
                    @endphp
                    @for ($i = 0; $i < $numTicks; $i++)
                        @php
                            // Angle sweeps from 180 degrees (left horizontal) to 0 degrees (right horizontal)
                            $angleDeg = 180 - ($i * (180 / ($numTicks - 1)));
                            $angleRad = deg2rad($angleDeg);
                            $x1 = $centerX + $rInner * cos($angleRad);
                            $y1 = $centerY - $rInner * sin($angleRad);
                            $x2 = $centerX + $rOuter * cos($angleRad);
                            $y2 = $centerY - $rOuter * sin($angleRad);
                            $isActive = $i < $activeCount;
                            $strokeColor = $isActive ? '#FF5A36' : '#E2E8F0';
                        @endphp
                        <line x1="{{ $x1 }}" y1="{{ $y1 }}" x2="{{ $x2 }}" y2="{{ $y2 }}" 
                              stroke="{{ $strokeColor }}" stroke-width="3.5" stroke-linecap="round" />
                    @endfor
                </svg>
                
                <!-- Center labels absolute-positioned at base of semi-circle -->
                <div class="absolute bottom-1 flex flex-col items-center justify-end text-center leading-none">
                    <span class="text-3.5xl font-black text-slate-800 font-sans tracking-tight">67.2%</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1.5">Sales Goal</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Warning/Increase Alert Bar -->
    <div class="bg-[#F8FAFC] border border-slate-100/50 rounded-2xl p-2.5 flex items-center justify-between mt-6 text-left">
        <span class="text-slate-600 text-[10.5px] font-bold font-sans pl-1.5">Your customer volume has increased</span>
        <span class="bg-[#FF5A36] text-white text-[9.5px] font-black px-2 py-0.5 rounded-full flex items-center gap-0.5 shadow-sm shadow-[#FF5A36]/10">
            &nearr; +15%
        </span>
    </div>
</div>
