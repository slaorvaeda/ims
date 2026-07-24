<footer class="w-full bg-[#0F172A] text-slate-400 pt-20 pb-8 px-6 border-t border-slate-800">
    <div class="max-w-7xl mx-auto flex flex-col gap-16">
        <!-- Grid layout for columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            
            <!-- Logo and Intro Column (Spans 2 columns on lg screens) -->
            <div class="lg:col-span-2 flex flex-col items-start gap-6">
                <a href="#" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#FF5A36] flex items-center justify-center shadow-lg shadow-[#FF5A36]/10">
                        <!-- Box/Package Icon -->
                        <svg class="w-5.5 h-5.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-white font-sans">IMS</span>
                </a>
                <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                    We provide advanced inventory management and barcode scanning solutions to help businesses manage stock, barcodes, and dispatches.
                </p>
                
                <!-- Newsletter form inside footer -->
                <div class="w-full max-w-sm mt-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-3">Newsletter</p>
                    <form @submit.prevent="alert('Subscribed successfully!')" class="flex gap-2 w-full">
                        <input type="email" required placeholder="Enter your email address" class="flex-1 bg-slate-800 border border-slate-700 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#FF5A36] placeholder:text-slate-500">
                        <button type="submit" class="bg-[#FF5A36] hover:bg-[#E04826] text-white text-sm font-semibold rounded-lg px-5 py-2 transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Links Column 1: Quick Links -->
            <div class="flex flex-col items-start gap-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white font-sans">Quick Links</h4>
                <ul class="flex flex-col gap-2.5 text-sm">
                    <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                    <li><a href="#testimonials" class="hover:text-white transition-colors">Testimonials</a></li>
                    <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#contact" class="hover:text-white transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Links Column 2: Resources & Stay Connected -->
            <div class="flex flex-col items-start gap-6">
                <div class="flex flex-col items-start gap-4">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white font-sans">Resources</h4>
                    <ul class="flex flex-col gap-2.5 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutorials</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="flex flex-col items-start gap-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white font-sans">Stay Connected</h4>
                    <div class="flex items-center gap-3.5 text-slate-400">
                        <a href="#" class="hover:text-white transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                        </a>
                        <a href="#" class="hover:text-white transition-colors" aria-label="LinkedIn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <a href="#" class="hover:text-white transition-colors" aria-label="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Links Column 3: Contact Us -->
            <div class="flex flex-col items-start gap-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white font-sans">Contact Us</h4>
                <div class="flex flex-col gap-3 text-sm text-left">
                    <p class="text-slate-400">Have questions or need assistance? We're here to help!</p>
                    <p class="leading-tight">
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider">Email</span>
                        <a href="mailto:support@yourcompany.com" class="text-[#FF5A36] hover:underline">support@yourcompany.com</a>
                    </p>
                    <p class="leading-tight">
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider">Phone</span>
                        <span class="text-slate-300 font-medium">+1 (123) 456-7890</span>
                    </p>
                    <p class="leading-tight">
                        <span class="text-xs text-slate-500 block uppercase font-bold tracking-wider">Address</span>
                        <span class="text-slate-300">123 Business Lane, Suite 456,<br>Your City, Your Country</span>
                    </p>
                </div>
            </div>

        </div>

        <!-- Footer Bottom divider and Copyright -->
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <p>&copy; 2026 IMS. All Rights Reserved.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <span>&middot;</span>
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
