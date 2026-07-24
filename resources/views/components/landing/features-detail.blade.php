<section class="w-full py-24 px-6 bg-slate-50/50 border-t border-slate-100 relative">
    <div class="max-w-7xl mx-auto flex flex-col items-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#FF5A36]/10 border border-[#FF5A36]/15 mb-6" data-aos="fade-up">
            <span class="text-xs font-semibold text-[#FF5A36]">Features</span>
        </div>

        <!-- Section Title -->
        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight text-center max-w-2xl leading-tight mb-16" data-aos="fade-up" data-aos-delay="100">
            Powerful Features to Streamline Your Inventory Process
        </h2>

        <!-- Features Grid Container -->
        <div class="flex flex-col gap-8 w-full">
            <!-- Row 1: Two Columns (Fulfillment & Stock Flow) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Card 1: Inventory Fulfillment -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="150">
                    <!-- Visual Mock -->
                    <div class="w-full bg-slate-50/70 rounded-2xl p-6 border border-slate-100 flex items-center justify-center mb-8 h-56 relative overflow-hidden">
                        <div class="relative w-36 h-36 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="40" stroke="#E2E8F0" stroke-width="8" fill="transparent"/>
                                <circle id="features-fulfillment-circle" cx="50" cy="50" r="40" stroke="#FF5A36" stroke-width="8" fill="transparent"
                                        stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute flex flex-col items-center justify-center text-center">
                                <span class="text-2xl font-black text-slate-800">84.2%</span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">Fulfillment Goal</span>
                            </div>
                        </div>
                        <div class="absolute bottom-3 right-3 bg-white border border-slate-100 px-2 py-1 rounded-md text-[9px] text-green-600 font-bold shadow-sm">+15% Efficient</div>
                    </div>
                    <!-- Info -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center text-[#FF5A36]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Inventory Fulfillment</h3>
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Track overall stock status and monitor progress towards fulfillment goals in real time. Our intuitive dashboard makes it simple to see pending vs completed dispatches.
                        </p>
                    </div>
                </div>

                <!-- Card 2: Stock Flow Analytics -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="250">
                    <!-- Visual Mock -->
                    <div class="w-full bg-slate-50/70 rounded-2xl p-6 border border-slate-100 flex flex-col justify-end mb-8 h-56">
                        <!-- Bar chart elements -->
                        <div class="flex items-end justify-between h-28 gap-2 px-4">
                            <div class="features-chart-bar features-chart-bar-1 w-4 bg-slate-200 rounded-t"></div>
                            <div class="features-chart-bar features-chart-bar-2 w-4 bg-slate-200 rounded-t"></div>
                            <div class="features-chart-bar features-chart-bar-3 w-4 bg-slate-200 rounded-t"></div>
                            <div class="features-chart-bar features-chart-bar-4 w-4 bg-[#FF5A36] rounded-t relative">
                                <div class="absolute -top-7 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow whitespace-nowrap">1,402 scans</div>
                            </div>
                            <div class="features-chart-bar features-chart-bar-5 w-4 bg-slate-200 rounded-t"></div>
                            <div class="features-chart-bar features-chart-bar-6 w-4 bg-slate-200 rounded-t"></div>
                        </div>
                        <div class="flex justify-between text-[8px] text-slate-400 font-bold px-4 mt-2">
                            <span>Jan</span>
                            <span>Feb</span>
                            <span>Mar</span>
                            <span>Apr</span>
                            <span>May</span>
                            <span>Jun</span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center text-[#FF5A36]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Stock Flow Analytics</h3>
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Measure product traffic and scanning flow. Keep tabs on monthly inward vs outward dispatch quantities with detailed bar charts.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Row 2: Three Columns (Brand Management, Availability, Scanning Log) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 3: Brand & Product Management -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="150">
                    <!-- Visual Mock -->
                    <div class="w-full bg-slate-50/70 rounded-2xl p-4 border border-slate-100 flex flex-col justify-center mb-6 h-40">
                        <div class="flex flex-col gap-1 w-full text-[8px] font-sans">
                            <div class="flex gap-1.5 items-center">
                                <span class="w-6 text-slate-400 text-right">2am</span>
                                <div class="flex-1 grid grid-cols-6 gap-0.5">
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-slate-100 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/20 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/40 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-slate-100 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-slate-100 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-slate-100 transition-all duration-300"></div>
                                </div>
                            </div>
                            <div class="flex gap-1.5 items-center">
                                <span class="w-6 text-slate-400 text-right">8am</span>
                                <div class="flex-1 grid grid-cols-6 gap-0.5">
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/30 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/60 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/80 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/30 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/10 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-slate-100 transition-all duration-300"></div>
                                </div>
                            </div>
                            <div class="flex gap-1.5 items-center">
                                <span class="w-6 text-slate-400 text-right">12pm</span>
                                <div class="flex-1 grid grid-cols-6 gap-0.5">
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/40 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/80 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/95 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/60 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-[#FF5A36]/20 transition-all duration-300"></div>
                                    <div class="features-heatmap-cell h-4 rounded-sm bg-slate-100 transition-all duration-300"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Info -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6.5 h-6.5 rounded-lg bg-orange-50 flex items-center justify-center text-[#FF5A36]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Brand & Products</h3>
                        </div>
                        <p class="text-xs.5 text-slate-500 leading-relaxed">
                            Group inventory items by brand and track specific product stock levels dynamically.
                        </p>
                    </div>
                </div>

                <!-- Card 4: Availability Rates -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="250">
                    <!-- Visual Mock -->
                    <div class="w-full bg-slate-50/70 rounded-2xl p-4 border border-slate-100 flex items-center justify-center mb-6 h-40">
                        <div class="relative w-24 h-24 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="14" stroke="#E2E8F0" stroke-width="3.5" fill="transparent"/>
                                <circle id="features-avail-circle" cx="18" cy="18" r="14" stroke="#FF5A36" stroke-width="3.5" fill="transparent"
                                        stroke-dasharray="88" stroke-dashoffset="88" stroke-linecap="round"/>
                            </svg>
                            <span class="absolute text-sm font-extrabold text-slate-800">+32%</span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6.5 h-6.5 rounded-lg bg-orange-50 flex items-center justify-center text-[#FF5A36]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Availability Rates</h3>
                        </div>
                        <p class="text-xs.5 text-slate-500 leading-relaxed">
                            Monitor stock availability and track active stock rates over various storage periods.
                        </p>
                    </div>
                </div>

                <!-- Card 5: Barcode Scanning Log -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="350">
                    <!-- Visual Mock -->
                    <div class="w-full bg-slate-50/70 rounded-2xl p-4 border border-slate-100 flex flex-col justify-center gap-2 mb-6 h-40">
                        <div class="features-barcode-widget features-barcode-widget-1 bg-[#1E293B] text-white p-2.5 rounded-xl text-left scale-95 shadow-md transition-all duration-500">
                            <p class="text-[8px] text-slate-400">Inward Scans</p>
                            <p class="text-sm font-bold">27,980 Units</p>
                        </div>
                        <div class="features-barcode-widget features-barcode-widget-2 bg-white p-2.5 rounded-xl border border-slate-100 text-left scale-95 shadow-sm flex justify-between items-center transition-all duration-500">
                            <div>
                                <p class="text-[8px] text-slate-400">Dispatch Scans</p>
                                <p class="text-sm font-bold text-slate-800">4,352 Units</p>
                            </div>
                            <span class="text-[8px] bg-slate-50 text-slate-500 px-1 py-0.5 rounded font-bold">-5%</span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6.5 h-6.5 rounded-lg bg-orange-50 flex items-center justify-center text-[#FF5A36]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Barcode Operations</h3>
                        </div>
                        <p class="text-xs.5 text-slate-500 leading-relaxed">
                            Scan packages instantly to log inward items, cancel dispatches, or generate printable barcode tags.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
