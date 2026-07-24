<div class="mb-6 text-left">
    <style>
        @keyframes wave-animation {
            0% { transform: rotate( 0.0deg) }
            10% { transform: rotate(14.0deg) }
            20% { transform: rotate(-8.0deg) }
            30% { transform: rotate(14.0deg) }
            40% { transform: rotate(-4.0deg) }
            50% { transform: rotate(10.0deg) }
            60% { transform: rotate( 0.0deg) }
            100% { transform: rotate( 0.0deg) }
        }
        .animate-wave {
            animation: wave-animation 2.5s infinite;
            transform-origin: 70% 70%;
            display: inline-block;
        }
        .animate-wave:hover {
            animation: wave-animation 0.6s infinite;
        }
    </style>
    <h1 class="text-3.5xl font-extrabold tracking-tight text-[#0F172A] font-sans leading-tight flex items-center gap-2">
        <span>Hi {{ Auth::user()->name }}</span>
        <span class="animate-wave cursor-pointer" title="Wave!">👋</span>
    </h1>
    <p class="text-slate-400 text-sm mt-1 min-h-[20px] flex items-center" 
       x-data="{ 
           text: 'How is Your Day? Below We Show Your Sales Report For this Month.', 
           displayedText: '', 
           index: 0, 
           showCursor: true,
           typeText() {
               if (this.index < this.text.length) {
                   this.displayedText += this.text.charAt(this.index);
                   this.index++;
                   setTimeout(() => this.typeText(), 30);
               } else {
                   // Fade out the cursor after a small delay once typing is finished
                   setTimeout(() => { this.showCursor = false; }, 1000);
               }
           }
       }" 
       x-init="typeText()">
        <span x-text="displayedText"></span>
        <span x-show="showCursor" class="inline-block animate-pulse text-[#FF5A36] font-extrabold ml-0.5">|</span>
    </p>
</div>
