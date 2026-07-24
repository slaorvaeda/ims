<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>IMS | Inventory Management System</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('lofg.png') }}">

        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @endif

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif !important;
            }
            [x-cloak] { display: none !important; }
            
            /* Diagonal stripe background styles for high-fidelity mockup */
            .diagonal-stripes-orange {
                background: repeating-linear-gradient(-45deg, #FF5A36, #FF5A36 3.5px, #ff7354 3.5px, #ff7354 7px);
            }
            .diagonal-stripes-light {
                background: repeating-linear-gradient(-45deg, rgba(255,90,54,0.18), rgba(255,90,54,0.18) 3.5px, rgba(255,90,54,0.07) 3.5px, rgba(255,90,54,0.07) 7px);
            }
            .diagonal-stripes-slate {
                background: repeating-linear-gradient(-45deg, #f8fafc, #f8fafc 4px, #f1f5f9 4px, #f1f5f9 8px);
            }
            .diagonal-stripes-red-light {
                background: repeating-linear-gradient(-45deg, rgba(239,68,68,0.18), rgba(239,68,68,0.18) 3.5px, rgba(239,68,68,0.07) 3.5px, rgba(239,68,68,0.07) 7px);
            }
            .diagonal-stripes-red {
                background: repeating-linear-gradient(-45deg, #ef4444, #ef4444 3.5px, #f87171 3.5px, #f87171 7px);
            }
            
            /* Circular Gauge in features detail */
            #features-fulfillment-circle {
                stroke-dashoffset: 251.2;
                transition: stroke-dashoffset 1.5s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .aos-animate #features-fulfillment-circle {
                stroke-dashoffset: 82.4;
            }
            
            /* Bar Chart columns in features detail */
            .features-chart-bar {
                height: 0%;
                transition: height 1.2s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .aos-animate .features-chart-bar-1 { height: 40%; transition-delay: 0.1s; }
            .aos-animate .features-chart-bar-2 { height: 65%; transition-delay: 0.2s; }
            .aos-animate .features-chart-bar-3 { height: 30%; transition-delay: 0.3s; }
            .aos-animate .features-chart-bar-4 { height: 85%; transition-delay: 0.4s; }
            .aos-animate .features-chart-bar-5 { height: 50%; transition-delay: 0.5s; }
            .aos-animate .features-chart-bar-6 { height: 70%; transition-delay: 0.6s; }

            /* Heatmap grid items in Brand & Products */
            .features-heatmap-cell {
                transform: scale(0.3);
                opacity: 0;
                transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.6s ease-out;
            }
            .aos-animate .features-heatmap-cell {
                transform: scale(1);
                opacity: 1;
            }

            /* Availability Rates circle */
            #features-avail-circle {
                stroke-dashoffset: 88;
                transition: stroke-dashoffset 1.5s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .aos-animate #features-avail-circle {
                stroke-dashoffset: 60;
            }

            /* Barcode Scans stat widgets */
            .features-barcode-widget {
                transform: translateY(20px);
                opacity: 0;
                transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.8s ease-out;
            }
            .aos-animate .features-barcode-widget-1 { transform: translateY(0); opacity: 1; transition-delay: 0.1s; }
            .aos-animate .features-barcode-widget-2 { transform: translateY(0); opacity: 1; transition-delay: 0.3s; }
            
            /* Custom Keyframe Animations */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .animate-slide-up {
                animation: slideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        </style>
    </head>
    <body class="bg-white text-slate-800 antialiased selection:bg-[#FF5A36] selection:text-white overflow-x-hidden">
        <!-- Navbar -->
        <x-landing.navbar />

        <!-- Hero Section -->
        <x-landing.hero />

        <!-- Trusted Showcase -->
        <x-landing.trusted />

        <!-- Why Choose Section -->
        <x-landing.why-choose />

        <!-- Detailed Features Section -->
        <x-landing.features-detail />

        <!-- Testimonials Section -->
        <x-landing.testimonials />

        <!-- FAQ Section -->
        <x-landing.faq />

        <!-- CTA Section -->
        <x-landing.cta />

        <!-- Footer Section -->
        <x-landing.footer />

        <!-- GSAP and Animation Script -->
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                gsap.registerPlugin(ScrollTrigger);

                const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

                // 1. Hero Text Elements Animation
                tl.to('.hero-badge', { opacity: 1, y: 0, duration: 0.8, startAt: { y: -20 } })
                  .to('.hero-title', { opacity: 1, y: 0, duration: 1, startAt: { y: 30 } }, '-=0.6')
                  .to('.hero-subtitle', { opacity: 1, y: 0, duration: 0.8, startAt: { y: 20 } }, '-=0.7')
                  .to('.hero-cta', { opacity: 1, y: 0, duration: 0.8, startAt: { y: 15 } }, '-=0.7');

                // 2. Webview Mockup Animation
                tl.to('.hero-mockup', { 
                    opacity: 1, 
                    scale: 1, 
                    y: 0, 
                    duration: 1.2, 
                    startAt: { scale: 0.96, y: 40 },
                    ease: 'back.out(1.1)',
                    onComplete: () => {
                        // After entry, enable scroll trigger on the box for 3D tilt and translate parallax
                        gsap.to('.hero-mockup', {
                            y: -80,
                            rotationX: -10,
                            scale: 0.97,
                            scrollTrigger: {
                                trigger: '#home',
                                start: 'top top',
                                end: 'bottom top',
                                scrub: 1.2
                            }
                        });
                    }
                }, '-=0.6');

                // 3. Stagger Sidebar Menu Icons
                tl.to('.mockup-sidebar-item', {
                    opacity: 1,
                    x: 0,
                    stagger: 0.08,
                    duration: 0.6,
                    startAt: { x: -20 }
                }, '-=0.8');

                // 4. Stagger Content Metric Cards
                tl.to('.mockup-metric-card', {
                    opacity: 1,
                    scale: 1,
                    stagger: 0.15,
                    duration: 0.7,
                    startAt: { scale: 0.9, opacity: 0 }
                }, '-=0.6');

                // 5. Radial Progress Wheel - strokeDashoffset from 188.4 to 61.8 (67.2% filled of the 188.4 arc)
                tl.to('#fulfillment-circle', {
                    strokeDashoffset: 61.8,
                    duration: 1.5,
                    ease: 'power2.inOut'
                }, '-=0.5');

                // 6. Stagger Growth Chart Bar Columns
                const bars = document.querySelectorAll('.mockup-chart-bar');
                if (bars.length > 0) {
                    tl.to(bars, {
                        height: (index, target) => target.getAttribute('data-height') || '0%',
                        stagger: 0.06,
                        duration: 0.8,
                        ease: 'back.out(1.5)'
                    }, '-=1.2');
                }

                // 7. Recent Transactions List
                tl.to('.mockup-transaction-item', {
                    opacity: 1,
                    y: 0,
                    stagger: 0.12,
                    duration: 0.6,
                    startAt: { y: 15 }
                }, '-=0.8');
            });
        </script>

        <!-- AOS JS -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                AOS.init({
                    duration: 900,
                    easing: 'ease-out-quad',
                    once: false,
                    offset: 100
                });
            });
        </script>
    </body>
</html>
