<section id="home" class="relative w-full overflow-hidden pt-8 pb-16 md:pt-14 md:pb-20 px-6">
    <!-- Mesh Glow Background Elements -->
    <div class="absolute top-[-20%] left-[10%] w-[600px] h-[600px] rounded-full bg-[#FF5A36]/10 blur-[120px] pointer-events-none -z-10"></div>
    <div class="absolute top-[-10%] right-[5%] w-[500px] h-[500px] rounded-full bg-orange-100/50 blur-[100px] pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto flex flex-col items-center text-center" style="perspective: 1000px;">
        <!-- Badge -->
        <div class="hero-badge opacity-0 inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#FF5A36]/10 border border-[#FF5A36]/15 mb-4">
            <span class="w-1.5 h-1.5 rounded-full bg-[#FF5A36]"></span>
            <span class="text-sm font-semibold text-[#FF5A36]">Inventory Tracking with IMS</span>
        </div>

        <!-- Heading -->
        <h1 class="hero-title opacity-0 text-4xl md:text-6xl lg:text-7.5xl font-extrabold tracking-tight text-[#0F172A] max-w-4xl leading-[1.1] mb-4">
            Empower Business with <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF5A36] to-[#FF8C36]">Real-Time Inventory</span>
        </h1>

        <!-- Subtitle -->
        <p class="hero-subtitle opacity-0 text-base md:text-lg text-slate-500 max-w-2xl leading-relaxed mb-3">
            Seamlessly track products, brands, inward scanning, barcode generation, and dispatch fulfillment. Streamline your warehouse operations with IMS.
        </p>

        <!-- Powered By Logos -->
        <div class="hero-subtitle opacity-0 flex flex-col items-center justify-center gap-1.5 mb-5">
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Powered by</span>
            <div class="flex items-center gap-8 bg-transparent pt-0.5">
                <img src="{{ asset('indivolt_logo.png') }}" alt="Indivolt" class="h-6 md:h-7 object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 hover:scale-105 transition-all duration-300 cursor-pointer">
                <span class="w-px h-4 bg-slate-300/60"></span>
                <img src="{{ asset('syntro_logo.png') }}" alt="Syntro" class="h-6 md:h-7 object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 hover:scale-105 transition-all duration-300 cursor-pointer">
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="hero-cta opacity-0 flex flex-col sm:flex-row gap-4 mb-10">
            <a href="{{ route('register') }}" class="px-8 py-3.5 bg-[#FF5A36] hover:bg-[#E04826] text-white text-base font-bold rounded-full shadow-lg shadow-[#FF5A36]/25 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                Get Started
            </a>
            <a href="#features" class="px-8 py-3.5 bg-white hover:bg-slate-50 text-slate-800 text-base font-bold rounded-full border border-slate-200 hover:border-slate-300 shadow-sm hover:shadow transition-all duration-200">
                Explore Features
            </a>
        </div>

        <!-- Dashboard Mockup (HTML/CSS High-Fidelity Replica Customized for IMS) -->
        <div class="hero-mockup opacity-0 w-full max-w-6xl rounded-2xl border border-slate-200/80 bg-slate-50/50 p-2 md:p-3 shadow-2xl backdrop-blur-sm relative z-10 transition-all duration-700 ease-out grayscale-[30%] opacity-90 hover:grayscale-0 hover:opacity-100 hover:border-[#FF5A36]/40 hover:shadow-[0_25px_60px_-15px_rgba(255,90,54,0.2)]">
            <div class="w-full rounded-xl bg-white border border-slate-100 shadow-inner overflow-hidden flex flex-col md:flex-row min-h-[600px] text-left text-xs text-slate-600">
                <!-- Sidebar -->
                <aside class="w-full md:w-16 shrink-0 bg-white border-r border-slate-100 flex md:flex-col items-center py-4 px-2 justify-between gap-4">
                    <div class="flex md:flex-col items-center gap-6 w-full">
                        <!-- Box/Package logo in sidebar -->
                        <div class="w-9 h-9 rounded-xl bg-[#FF5A36] flex items-center justify-center shadow-md shadow-[#FF5A36]/10">
                            <svg class="w-5.5 h-5.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <!-- Menu Items -->
                        <div class="flex md:flex-col gap-4 items-center">
                            <div class="mockup-sidebar-item opacity-0 w-9 h-9 rounded-xl bg-[#FF5A36]/10 text-[#FF5A36] flex items-center justify-center cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div class="mockup-sidebar-item opacity-0 w-9 h-9 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-slate-600 flex items-center justify-center cursor-pointer transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/></svg>
                            </div>
                            <div class="mockup-sidebar-item opacity-0 w-9 h-9 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-slate-600 flex items-center justify-center cursor-pointer transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div class="mockup-sidebar-item opacity-0 w-9 h-9 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-slate-600 flex items-center justify-center cursor-pointer transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="mockup-sidebar-item opacity-0 w-9 h-9 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-slate-600 flex items-center justify-center cursor-pointer transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0v-4a2 2 0 00-2-2h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-slate-600 flex items-center justify-center cursor-pointer transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </aside>

                <!-- Dashboard Content Area -->
                <main class="flex-1 bg-[#F8FAFC] p-4 md:p-6 flex flex-col gap-6">
                    <!-- Top row: Greeting & Stats Row -->
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 font-sans tracking-tight">Hi Ahnaf Ibn Habib</h2>
                            <p class="text-slate-400 text-xs mt-0.5">Hi Richard, How is Your Day? Below We Show Your Sales Report For this Month</p>
                        </div>
                    </div>

                    <!-- Welcome & Cards Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Main Panel (Available Balance & Pending Balance) -->
                        <div class="lg:col-span-2 flex flex-col gap-6">
                            <!-- Cards Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                 <!-- Card 1: Available Balance -->
                                <div class="mockup-metric-card opacity-0 bg-white border border-slate-100 p-6 rounded-[2rem] relative overflow-hidden shadow-sm flex items-center gap-4 group cursor-pointer transition-all duration-300 hover:border-[#FF5A36]/40 hover:shadow-lg hover:shadow-[#FF5A36]/10 hover:-translate-y-0.5">
                                    <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-slate-50 transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-2 group-hover:-translate-y-2 pointer-events-none"></div>
                                    
                                    <!-- Black Icon Circle -->
                                    <div class="w-12 h-12 rounded-2xl bg-[#0F172A] text-white flex items-center justify-center relative z-10 shrink-0">
                                        <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div class="relative z-10 flex-1">
                                        <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Available Balance</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-xl font-black text-slate-800">$27,980.24</p>
                                            <span class="bg-[#0F172A] text-white font-bold text-[9px] px-2 py-0.5 rounded-full">+30%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Pending Balance -->
                                <div class="mockup-metric-card opacity-0 bg-white border border-slate-100 p-6 rounded-[2rem] relative overflow-hidden shadow-sm flex items-center gap-4 group cursor-pointer transition-all duration-300 hover:border-[#FF5A36]/40 hover:shadow-lg hover:shadow-[#FF5A36]/10 hover:-translate-y-0.5">
                                    <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-slate-50 transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-2 group-hover:-translate-y-2 pointer-events-none"></div>
                                    
                                    <!-- Orange Icon Circle -->
                                    <div class="w-12 h-12 rounded-2xl bg-[#FF5A36] text-white flex items-center justify-center relative z-10 shrink-0 shadow-lg shadow-[#FF5A36]/20">
                                        <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div class="relative z-10 flex-1">
                                        <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Pending Balance</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-xl font-black text-slate-800">$27,980.24</p>
                                            <span class="bg-[#1E293B] text-white font-bold text-[9px] px-2 py-0.5 rounded-full">-1%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Sales Overview Card -->
                        <div class="flex flex-col gap-6">
                            <!-- Sales Overview Card (Includes gauge) -->
                            <div class="bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:border-[#FF5A36]/40 hover:shadow-lg hover:shadow-[#FF5A36]/10 hover:-translate-y-0.5">
                                <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-slate-50 transition-all duration-500 ease-out group-hover:scale-125 group-hover:-translate-x-2 group-hover:-translate-y-2 pointer-events-none"></div>
                                
                                <div class="flex justify-between items-center relative z-10">
                                    <h3 class="font-bold text-slate-800 text-xs font-sans flex items-center gap-1.5 uppercase tracking-wider">
                                        <svg class="w-4.5 h-4.5 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0v-4a2 2 0 00-2-2h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        Sales Overview
                                    </h3>
                                    <button class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                </div>

                                <div class="flex items-center justify-between gap-2 py-2 relative z-10">
                                    <!-- Left stats list -->
                                    <div class="flex flex-col gap-4">
                                        <!-- Number of Sales -->
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase">Number of Sales</span>
                                                <span class="bg-[#FFF2EE] text-[#FF5A36] text-[8px] font-black px-1.5 py-0.5 rounded-full">3.5% ↗</span>
                                            </div>
                                            <p class="text-xl font-black text-slate-800">1,304</p>
                                        </div>
                                        <!-- Total Sales -->
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase">Total Sales</span>
                                                <span class="bg-[#1E293B] text-white text-[8px] font-black px-1.5 py-0.5 rounded-full">4.5% ↗</span>
                                            </div>
                                            <p class="text-xl font-black text-slate-800">$25.1K</p>
                                        </div>
                                    </div>

                                    <!-- Right Circular gauge -->
                                    <div class="relative w-28 h-28 flex items-center justify-center shrink-0">
                                        <svg class="w-full h-full transform -rotate-[225deg]" viewBox="0 0 100 100">
                                            <!-- Gauge Background Arc -->
                                            <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent"
                                                    stroke-dasharray="188.4 251.2" stroke-linecap="round"/>
                                            <!-- Gauge Filled Arc (using orange pattern styling) -->
                                            <circle id="fulfillment-circle" cx="50" cy="50" r="40" stroke="#FF5A36" stroke-width="8" fill="transparent"
                                                    stroke-dasharray="188.4 251.2" stroke-dashoffset="188.4" stroke-linecap="round"/>
                                        </svg>
                                        <div class="absolute flex flex-col items-center justify-center text-center mt-3">
                                            <span class="text-xl font-black text-slate-800">67.2%</span>
                                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wide">Sales Goal</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-[#FF5A36]/5 rounded-2xl p-2.5 border border-[#FF5A36]/10 text-center relative z-10">
                                    <p class="text-[#FF5A36] text-[10px] font-bold">Your customer volume has increased <span class="bg-[#FF5A36] text-white px-1.5 py-0.5 rounded-full text-[8px] ml-1">+15%</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Toolbar Row -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border border-slate-100 p-4 rounded-3xl shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-orange-50 flex items-center justify-center text-[#FF5A36] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </span>
                            <h3 class="font-extrabold text-slate-800 text-xs uppercase tracking-wider">Product Analytics</h3>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold">
                            <!-- Store Tabs -->
                            <div class="flex p-0.5 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="px-3 py-1.5 hover:text-slate-800 cursor-pointer">All</span>
                                <span class="px-3 py-1.5 bg-[#0F172A] text-white rounded-lg shadow-sm cursor-pointer">Store-1</span>
                                <span class="px-3 py-1.5 hover:text-slate-800 cursor-pointer">Store-2</span>
                                <span class="px-3 py-1.5 hover:text-slate-800 cursor-pointer">Store-3</span>
                            </div>
                            <!-- Dropdown -->
                            <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl text-slate-600 flex items-center gap-1 cursor-pointer">
                                <span>Month</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <span class="text-slate-300">|</span>
                            <!-- Actions -->
                            <button class="p-1.5 bg-slate-50 border border-slate-100 rounded-xl text-slate-500 hover:text-slate-800">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                            </button>
                            <button class="p-1.5 bg-slate-50 border border-slate-100 rounded-xl text-slate-500 hover:text-slate-800">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4h4m12 4V4h-4M4 16v4h4m12-4v4h-4"/></svg>
                            </button>
                            <!-- Filter -->
                            <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl text-slate-600 flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                <span>Filter</span>
                            </div>
                        </div>
                    </div>

                    <!-- Main Columns Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Col 1: Time Visit Heatmap -->
                        <div class="bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-extrabold text-slate-800 text-[11px] uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Time Visit in hours
                                </h3>
                                <button class="p-1 text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                </button>
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex flex-wrap items-center gap-3 text-[9px] font-bold text-slate-400 tracking-wider">
                                <div class="flex items-center gap-1">
                                    <div class="w-3.5 h-3.5 rounded diagonal-stripes-red-light"></div>
                                    <span>&gt;500</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3.5 h-3.5 rounded diagonal-stripes-red"></div>
                                    <span>&gt;1,000</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3.5 h-3.5 rounded bg-red-100"></div>
                                    <span>&gt;2,000</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3.5 h-3.5 rounded bg-red-500"></div>
                                    <span>&gt;3,000</span>
                                </div>
                            </div>

                            <!-- Heatmap Grid -->
                            <div class="flex flex-col gap-2 mt-2">
                                @php
                                    $heatmapRows = [
                                        '2am' => ['light', 'none', 'light', 'none', 'light', 'none', 'none'],
                                        '1am' => ['orange', 'orange', 'light', 'red', 'none', 'none', 'light'],
                                        '12am' => ['red', 'none', 'red', 'none', 'red', 'none', 'light'],
                                        '11am' => ['none', 'red', 'none', 'red', 'none', 'red', 'none'],
                                        '10am' => ['light', 'none', 'light', 'none', 'light', 'none', 'light'],
                                        '9am' => ['none', 'none', 'none', 'light', 'none', 'none', 'none'],
                                        '8am' => ['light', 'light', 'none', 'none', 'light', 'light', 'none'],
                                    ];
                                @endphp
                                @foreach($heatmapRows as $time => $cols)
                                    <div class="flex gap-2 items-center">
                                        <span class="w-8 shrink-0 text-[10px] text-slate-400 text-right font-bold">{{ $time }}</span>
                                        <div class="flex-1 grid grid-cols-7 gap-1.5">
                                            @foreach($cols as $col)
                                                @if($col === 'red')
                                                    <div class="h-5 rounded-[4px] diagonal-stripes-red"></div>
                                                @elseif($col === 'orange')
                                                    <div class="h-5 rounded-[4px] bg-[#FF5A36]"></div>
                                                @elseif($col === 'light')
                                                    <div class="h-5 rounded-[4px] diagonal-stripes-red-light"></div>
                                                @else
                                                    <div class="h-5 rounded-[4px] bg-slate-50 border border-slate-100/50"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Days labels -->
                                <div class="flex gap-2 items-center mt-1">
                                    <span class="w-8 shrink-0"></span>
                                    <div class="flex-1 grid grid-cols-7 gap-1.5 text-center text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Col 2: Mid-sized cards & Engagement Rate -->
                        <div class="flex flex-col gap-6">
                            <!-- Growth & Total Customer Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Growth Radial Card -->
                                <div class="bg-white border border-slate-100 p-4 rounded-3xl shadow-sm flex flex-col gap-2 relative">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-slate-500 font-extrabold uppercase">Growth</span>
                                        <button class="text-slate-400 hover:text-slate-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                        </button>
                                    </div>
                                    <div class="flex justify-center items-center py-1">
                                        <div class="relative w-20 h-20 flex items-center justify-center">
                                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                                <circle cx="18" cy="18" r="14" stroke="#F8FAFC" stroke-width="3" fill="transparent"/>
                                                <circle cx="18" cy="18" r="14" stroke="#FF5A36" stroke-width="3" fill="transparent"
                                                        stroke-dasharray="88" stroke-dashoffset="60" stroke-linecap="round"/>
                                            </svg>
                                            <div class="absolute flex flex-col items-center">
                                                <span class="text-xs font-black text-slate-800">+32%</span>
                                                <span class="text-[6px] text-slate-400 font-bold uppercase whitespace-nowrap">Growth rate</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Customer Card -->
                                <div class="bg-white border border-slate-100 p-4 rounded-3xl shadow-sm flex flex-col gap-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] text-slate-500 font-extrabold uppercase">Total Customer</span>
                                        <button class="text-slate-400 hover:text-slate-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                        </button>
                                    </div>
                                    <div class="mt-1">
                                        <h4 class="text-2xl font-black text-slate-800 tracking-tight">3,526</h4>
                                        <!-- Progress bar with diagonal stripes -->
                                        <div class="w-full h-4 bg-slate-50 border border-slate-100 rounded-full overflow-hidden mt-2 relative">
                                            <div class="h-full rounded-full diagonal-stripes-orange w-[70%]"></div>
                                        </div>
                                        <p class="text-[9px] text-slate-400 font-bold text-center mt-2 cursor-pointer hover:underline">View Details</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Engagement Rate Chart -->
                            <div class="bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm flex flex-col gap-3">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-extrabold text-slate-800 text-[11px] uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        Engagement Rate
                                    </h3>
                                    <div class="flex items-center gap-1 bg-slate-50 border border-slate-100 p-0.5 rounded-lg text-[8px] font-bold text-slate-500">
                                        <span class="px-2 py-0.5 bg-white rounded shadow-sm text-slate-800">Monthly</span>
                                        <span class="px-2 py-0.5 hover:text-slate-800 cursor-pointer">Annually</span>
                                    </div>
                                </div>

                                <!-- Bars block -->
                                <div class="flex items-end justify-between h-24 px-2 pt-6 gap-2 relative mt-2">
                                    <div class="mockup-chart-bar w-3 bg-slate-100 rounded-t hover:bg-[#FF5A36] transition-colors cursor-pointer" style="height: 0%" data-height="40%"></div>
                                    <div class="mockup-chart-bar w-3 bg-slate-100 rounded-t hover:bg-[#FF5A36] transition-colors cursor-pointer" style="height: 0%" data-height="55%"></div>
                                    <div class="mockup-chart-bar w-3 bg-slate-100 rounded-t hover:bg-[#FF5A36] transition-colors cursor-pointer" style="height: 0%" data-height="30%"></div>
                                    <div class="mockup-chart-bar w-3 bg-slate-100 rounded-t hover:bg-[#FF5A36] transition-colors cursor-pointer" style="height: 0%" data-height="70%"></div>
                                    
                                    <!-- Highlighted May Bar -->
                                    <div class="mockup-chart-bar w-3 bg-[#FF5A36] rounded-t cursor-pointer relative" style="height: 0%" data-height="85%">
                                        <!-- May Tooltip -->
                                        <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-[#0F172A] text-white text-[7px] font-bold px-2 py-1 rounded-xl shadow-lg whitespace-nowrap z-20 flex flex-col items-center gap-0.5 border border-slate-800">
                                            <span class="text-[6px] text-slate-400 font-extrabold uppercase tracking-wide">April 2023</span>
                                            <span class="text-[9px] text-[#FF5A36] font-black">379,502</span>
                                            <span class="bg-[#FF5A36] text-white text-[5px] px-1 rounded-full">+12.8%</span>
                                        </div>
                                        <!-- Connected pointer dot on the tooltip line -->
                                        <div class="absolute -top-1.5 left-1/2 transform -translate-x-1/2 w-2 h-2 rounded-full bg-[#0F172A] border-2 border-white"></div>
                                    </div>
                                    <div class="mockup-chart-bar w-3 bg-slate-100 rounded-t hover:bg-[#FF5A36] transition-colors cursor-pointer" style="height: 0%" data-height="45%"></div>
                                    <div class="mockup-chart-bar w-3 bg-slate-100 rounded-t hover:bg-[#FF5A36] transition-colors cursor-pointer" style="height: 0%" data-height="60%"></div>
                                </div>
                                <div class="flex justify-between text-[8px] text-slate-400 font-bold px-1 mt-1 uppercase tracking-wider">
                                    <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span>
                                </div>
                            </div>
                        </div>

                        <!-- Col 3: Recent Sales & Review Rating -->
                        <div class="flex flex-col gap-6">
                            <!-- Recent Sales Card -->
                            <div class="bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm flex flex-col gap-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-extrabold text-slate-800 text-[11px] uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-[#FF5A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3a4 4 0 0 0 0-8" />
                                        </svg>
                                        Recent Sales
                                    </h3>
                                    <a href="#" class="text-[9px] text-[#FF5A36] font-bold hover:underline uppercase tracking-wider">See all &rarr;</a>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <!-- Sale 1 -->
                                    <div class="mockup-transaction-item opacity-0 flex justify-between items-center p-2.5 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                        <div class="flex items-center gap-2">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Aryan" class="w-8 h-8 rounded-full object-cover border border-[#FF5A36]/20 shadow-sm shrink-0">
                                            <div>
                                                <p class="font-extrabold text-slate-800 text-[10px] leading-tight">Ariyan Di</p>
                                                <p class="text-[8px] text-slate-400 font-semibold mt-0.5">01 Day Ago</p>
                                            </div>
                                        </div>
                                        <span class="text-emerald-600 font-black text-[10px] bg-emerald-50 px-2 py-0.5 rounded-full">+ $60.00</span>
                                    </div>
                                    <!-- Sale 2 -->
                                    <div class="mockup-transaction-item opacity-0 flex justify-between items-center p-2.5 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                        <div class="flex items-center gap-2">
                                            <img src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=100&q=80" alt="Adil" class="w-8 h-8 rounded-full object-cover border border-[#FF5A36]/20 shadow-sm shrink-0">
                                            <div>
                                                <p class="font-extrabold text-slate-800 text-[10px] leading-tight">Adil Is</p>
                                                <p class="text-[8px] text-slate-400 font-semibold mt-0.5">02 Day Ago</p>
                                            </div>
                                        </div>
                                        <span class="text-emerald-600 font-black text-[10px] bg-emerald-50 px-2 py-0.5 rounded-full">+ $90.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Rating (Feedback Smiley Widget) -->
                            <div class="bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm flex flex-col gap-3 relative">
                                <button class="absolute top-4 right-4 w-6 h-6 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Review Rating</span>
                                <h4 class="font-extrabold text-slate-800 text-sm leading-snug max-w-[80%]">Does our dashboard help your business?</h4>
                                
                                <!-- Smiley row -->
                                <div class="flex items-center justify-between gap-1 mt-2">
                                    <!-- Red Active Smiley -->
                                    <button class="w-8.5 h-8.5 rounded-full bg-[#FF5A36] text-white flex items-center justify-center shadow-lg shadow-[#FF5A36]/20 hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                    </button>
                                    
                                    <!-- Neutral/Sad faces (Darker default styling) -->
                                    <button class="w-8.5 h-8.5 rounded-full bg-[#0F172A] text-white flex items-center justify-center hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 11H7v-2h10v2z"/></svg>
                                    </button>
                                    <button class="w-8.5 h-8.5 rounded-full bg-[#0F172A] text-white flex items-center justify-center hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
                                    </button>
                                    <button class="w-8.5 h-8.5 rounded-full bg-[#0F172A] text-white flex items-center justify-center hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-3-8c.83 0 1.5-.67 1.5-1.5S9.83 8.5 9 8.5s-1.5.67-1.5 1.5S8.17 10 9 10zm6 0c.83 0 1.5-.67 1.5-1.5S15.83 8.5 15 8.5s-1.5.67-1.5 1.5s.67 1.5 1.5 1.5zm-3 8c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                                    </button>
                                    <button class="w-8.5 h-8.5 rounded-full bg-[#0F172A] text-white flex items-center justify-center hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 14H7v-2h10v2zm-6.5-6C9.94 10 9.5 9.56 9.5 9s.44-1 1-1 1 .44 1 1-.44 1-1 1zm5 0c-.56 0-1-.44-1-1s.44-1 1-1 1 .44 1 1-.44 1-1 1z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</section>
