<section id="faq" class="w-full py-24 px-6 bg-slate-50/50 border-t border-slate-100 relative">
    <div class="max-w-4xl mx-auto flex flex-col items-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#FF5A36]/10 border border-[#FF5A36]/15 mb-6" data-aos="fade-up">
            <span class="text-xs font-semibold text-[#FF5A36]">FAQ</span>
        </div>

        <!-- Section Title -->
        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight text-center leading-tight mb-16" data-aos="fade-up" data-aos-delay="100">
            Frequently asked Questions
        </h2>

        <!-- Accordions Container -->
        <div x-data="{ active: 1 }" class="w-full flex flex-col gap-4">
            <!-- Accordion 1 -->
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-200" data-aos="fade-up" data-aos-delay="150">
                <button @click="active = (active === 1 ? null : 1)" 
                        class="w-full px-6 py-5 flex items-center justify-between font-bold text-left text-slate-800 hover:text-[#FF5A36] transition-colors focus:outline-none">
                    <span class="flex items-center gap-4">
                        <span class="text-xs font-semibold text-slate-400">01</span>
                        <span class="text-sm md:text-base">What is this Inventory Management System (IMS) used for?</span>
                    </span>
                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                         :class="{ 'rotate-180 text-[#FF5A36]': active === 1 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 1" 
                     x-cloak
                     class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 pl-14">
                    Our IMS helps you track stock levels, manage products and brands, log inward items, generate custom barcodes, and coordinate dispatches in real time. It serves as a unified control center for warehouse logistics.
                </div>
            </div>

            <!-- Accordion 2 -->
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-200" data-aos="fade-up" data-aos-delay="200">
                <button @click="active = (active === 2 ? null : 2)" 
                        class="w-full px-6 py-5 flex items-center justify-between font-bold text-left text-slate-800 hover:text-[#FF5A36] transition-colors focus:outline-none">
                    <span class="flex items-center gap-4">
                        <span class="text-xs font-semibold text-slate-400">02</span>
                        <span class="text-sm md:text-base">Can I generate and print barcode tags?</span>
                    </span>
                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                         :class="{ 'rotate-180 text-[#FF5A36]': active === 2 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 2" 
                     x-cloak
                     class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 pl-14">
                    Yes! The system includes a built-in Barcode Generator module. You can generate custom barcodes for products, print tags in batches, and scan them directly to update inventory status.
                </div>
            </div>

            <!-- Accordion 3 -->
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-200" data-aos="fade-up" data-aos-delay="250">
                <button @click="active = (active === 3 ? null : 3)" 
                        class="w-full px-6 py-5 flex items-center justify-between font-bold text-left text-slate-800 hover:text-[#FF5A36] transition-colors focus:outline-none">
                    <span class="flex items-center gap-4">
                        <span class="text-xs font-semibold text-slate-400">03</span>
                        <span class="text-sm md:text-base">Does it support hand-held USB and Bluetooth barcode scanners?</span>
                    </span>
                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                         :class="{ 'rotate-180 text-[#FF5A36]': active === 3 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 3" 
                     x-cloak
                     class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 pl-14">
                    Absolutely. The scan-inward and scan-dispatch inputs are designed to auto-submit when a barcode reader sends an Enter key signal. It works seamlessly with any standard USB or Bluetooth hand-held scanner.
                </div>
            </div>

            <!-- Accordion 4 -->
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-200" data-aos="fade-up" data-aos-delay="300">
                <button @click="active = (active === 4 ? null : 4)" 
                        class="w-full px-6 py-5 flex items-center justify-between font-bold text-left text-slate-800 hover:text-[#FF5A36] transition-colors focus:outline-none">
                    <span class="flex items-center gap-4">
                        <span class="text-xs font-semibold text-slate-400">04</span>
                        <span class="text-sm md:text-base">How secure is my inventory database?</span>
                    </span>
                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                         :class="{ 'rotate-180 text-[#FF5A36]': active === 4 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="active === 4" 
                     x-cloak
                     class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 pl-14">
                    Security is our top priority. All database operations and transaction histories are protected by role-based access control, meaning only authorized operators and administrators can view or perform scans.
                </div>
            </div>
        </div>
    </div>
</section>
