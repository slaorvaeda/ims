<section class="w-full py-16 px-6 bg-slate-50/40 border-y border-slate-100 relative">
    <div class="max-w-7xl mx-auto flex flex-col items-center">
        <!-- Section Heading -->
        <p class="text-xs md:text-sm font-bold tracking-widest text-slate-400 uppercase text-center mb-8" data-aos="fade-up">
            We are working with
        </p>

        <!-- Brand Logos Grid -->
        <div class="flex flex-wrap items-center justify-center gap-x-14 md:gap-x-20 gap-y-10" data-aos="fade-up" data-aos-delay="100">
            <!-- Flipkart -->
            <img src="{{ asset('flipkart.png') }}" alt="Flipkart" class="h-11 md:h-16 max-h-16 object-contain grayscale opacity-70 hover:grayscale-0 hover:opacity-100 hover:scale-110 transition-all duration-300 cursor-pointer">

            <!-- Amazon -->
            <img src="{{ asset('amazon.png') }}" alt="Amazon" class="h-11 md:h-16 max-h-16 object-contain grayscale opacity-70 hover:grayscale-0 hover:opacity-100 hover:scale-110 transition-all duration-300 cursor-pointer">

            <!-- JioMart -->
            <div class="flex items-center gap-2 grayscale hover:grayscale-0 opacity-70 hover:opacity-100 transition-all cursor-pointer group">
                <span class="w-9 h-9 rounded-full bg-[#0083CA] text-white flex items-center justify-center font-extrabold text-xs group-hover:scale-110 transition-transform">Jio</span>
                <span class="text-2xl md:text-3xl font-black font-sans text-slate-700 tracking-tight group-hover:text-[#0083CA] transition-colors">Mart</span>
            </div>

            <!-- Zigma -->
            <img src="{{ asset('zigma.avif') }}" alt="Zigma" class="h-11 md:h-16 max-h-16 object-contain grayscale opacity-70 hover:grayscale-0 hover:opacity-100 hover:scale-110 transition-all duration-300 cursor-pointer">
        </div>
    </div>
</section>
