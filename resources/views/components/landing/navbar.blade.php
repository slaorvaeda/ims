<nav x-data="{ open: false }" class="w-full max-w-7xl mx-auto px-6 py-4 flex items-center justify-between relative z-50">
    <!-- Logo -->
    <a href="#" class="flex items-center gap-2.5">
        <div class="w-10 h-10 rounded-xl bg-[#FF5A36] flex items-center justify-center shadow-lg shadow-[#FF5A36]/20">
            <!-- Box/Package Icon SVG -->
            <svg class="w-5.5 h-5.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
        </div>
        <span class="text-xl font-black tracking-tight text-[#0F172A] font-sans">IMS</span>
    </a>

    <!-- Desktop Navigation Links -->
    <div class="hidden md:flex items-center gap-8 bg-white/60 backdrop-blur-md px-6 py-2.5 rounded-full border border-slate-100 shadow-sm">
        <a href="#home" class="text-sm font-medium text-[#475569] hover:text-[#FF5A36] transition-colors">Home</a>
        <a href="#features" class="text-sm font-medium text-[#475569] hover:text-[#FF5A36] transition-colors">Features</a>
        <a href="#testimonials" class="text-sm font-medium text-[#475569] hover:text-[#FF5A36] transition-colors">Testimonials</a>
        <a href="#faq" class="text-sm font-medium text-[#475569] hover:text-[#FF5A36] transition-colors">FAQ</a>
    </div>

    <!-- Right Side Actions -->
    <div class="hidden md:flex items-center gap-4">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-[#475569] hover:text-[#FF5A36] transition-colors mr-2">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-[#475569] hover:text-[#FF5A36] transition-colors mr-2">
                    Log in
                </a>
            @endauth
        @endif
        <a href="#contact" class="px-6 py-2.5 bg-[#FF5A36] hover:bg-[#E04826] text-white text-sm font-semibold rounded-full shadow-md shadow-[#FF5A36]/15 hover:shadow-lg transition-all duration-200">
            Contact Us
        </a>
    </div>

    <!-- Mobile Menu Button -->
    <button @click="open = !open" class="md:hidden p-2 text-[#475569] hover:text-[#FF5A36] transition-colors" aria-label="Toggle Menu">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Mobile Navigation Drawer -->
    <div x-show="open" 
         x-cloak
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="absolute top-16 left-6 right-6 p-6 bg-white rounded-2xl border border-slate-100 shadow-xl flex flex-col gap-4 md:hidden z-50">
        <a href="#home" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">Home</a>
        <a href="#features" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">Features</a>
        <a href="#testimonials" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">Testimonials</a>
        <a href="#faq" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">FAQ</a>
        
        <hr class="border-slate-100 my-1">
        
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" @click="open = false" class="text-base font-semibold text-[#1E293B] hover:text-[#FF5A36] py-1">
                        Register
                    </a>
                @endif
            @endauth
        @endif
        
        <a href="#contact" @click="open = false" class="w-full text-center px-6 py-3 bg-[#FF5A36] text-white font-semibold rounded-full shadow-md shadow-[#FF5A36]/15 hover:bg-[#E04826] transition-colors mt-2">
            Contact Us
        </a>
    </div>
</nav>
