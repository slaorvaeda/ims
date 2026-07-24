<div x-data="{ show: true }" x-show="show" class="bg-[#F8FAFC] border border-slate-100/50 p-6 rounded-3xl shadow-sm flex flex-col justify-between w-full relative overflow-hidden group hover:-translate-y-1.5 hover:shadow-lg transition-all duration-300">
    <!-- Close Button -->
    <button @click="show = false" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block text-left mb-3">Review Rating</span>
    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight leading-tight text-left mb-6 font-sans">
        Does our dashboard help your business?
    </h3>

    <!-- Emojis row -->
    <div class="flex items-center justify-between gap-2 mt-2">
        <!-- Angry face -->
        <button class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-[#FF5A36] hover:scale-110 transition-all focus:outline-none" title="Terrible">
            <span class="text-lg">😠</span>
        </button>
        <!-- Sad face -->
        <button class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-[#FF5A36] hover:scale-110 transition-all focus:outline-none" title="Bad">
            <span class="text-lg">🙁</span>
        </button>
        <!-- Neutral face -->
        <button class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-[#FF5A36] hover:scale-110 transition-all focus:outline-none" title="Neutral">
            <span class="text-lg">😐</span>
        </button>
        <!-- Happy face -->
        <button class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-[#FF5A36] hover:scale-110 transition-all focus:outline-none" title="Good">
            <span class="text-lg">🙂</span>
        </button>
        <!-- Extremely Happy face -->
        <button class="w-10 h-10 rounded-full bg-[#FF5A36] text-white flex items-center justify-center hover:bg-[#E04826] hover:scale-110 transition-all focus:outline-none" title="Excellent">
            <span class="text-lg">😊</span>
        </button>
    </div>
</div>
