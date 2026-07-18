@props([
    'name',
    'options' => [], // Expects array of objects/arrays with 'value' and 'label'
    'selected' => null,
    'placeholder' => 'Select an option...',
    'class' => '',
])

<div 
    x-data="{
        open: false,
        search: '',
        value: '{{ $selected }}',
        label: '',
        options: @js($options),
        init() {
            const matched = this.options.find(opt => String(opt.value) === String(this.value));
            if (matched) {
                this.label = matched.label;
                this.search = matched.label;
            }
        },
        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(opt => 
                opt.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        select(option) {
            this.value = option.value;
            this.label = option.label;
            this.search = option.label;
            this.open = false;
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.search = '';
            } else {
                this.search = this.label;
            }
        },
        close() {
            this.open = false;
            this.search = this.label;
        }
    }"
    class="relative w-full {{ $class }}"
    @click.outside="close()"
>
    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" x-model="value">

    <!-- Input text area -->
    <div class="relative">
        <input 
            type="text"
            placeholder="{{ $placeholder }}"
            x-model="search"
            @focus="open = true; search = ''"
            class="w-full pl-4 pr-10 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-xs focus:outline-none focus:border-indigo-500 transition-all shadow-sm"
        >
        
        <!-- Toggle button -->
        <button 
            type="button"
            @click="toggle()"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
        >
            <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'transform rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>

    <!-- Options Dropdown panel -->
    <div 
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1.5 w-full max-h-60 overflow-y-auto bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-lg focus:outline-none"
    >
        <ul class="py-1.5 text-xs text-slate-700 dark:text-slate-300">
            <template x-for="option in filteredOptions" :key="option.value">
                <li 
                    @click="select(option)"
                    class="cursor-pointer select-none relative py-2.5 px-4 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold transition-colors duration-150"
                >
                    <span x-text="option.label"></span>
                </li>
            </template>
            
            <template x-if="filteredOptions.length === 0">
                <li class="py-3 px-4 text-slate-400 dark:text-slate-500 text-center font-medium">
                    No options match "<span x-text="search"></span>"
                </li>
            </template>
        </ul>
    </div>
</div>
