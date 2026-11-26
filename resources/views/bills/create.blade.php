<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('bills.index', ['type' => $defaultType]) }}" 
                   class="p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#FF5A36] rounded-2xl transition-all shadow-sm flex items-center justify-center"
                   title="Back to Bills">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                        {{ __('Upload & Scan Bill') }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Upload a scanned PDF or photo invoice. Our AI automatically extracts fields into structured data.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="billUploader()" class="max-w-5xl mx-auto space-y-6">
        
        <!-- Error Alerts -->
        @if ($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/50 rounded-2xl text-rose-800 dark:text-rose-300 text-xs shadow-sm space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bills.store') }}" enctype="multipart/form-data" class="space-y-6" @submit="beforeSubmit($event)">
            @csrf

            <!-- 1. Document Upload & Live AI Scanner Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="p-1.5 bg-[#FF5A36]/10 text-[#FF5A36] rounded-xl inline-flex">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </span>
                            <span>1. Upload Scanned Invoice Document</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Accepts PDF documents or clear high-res photos (PNG, JPG, WebP) up to 25MB.</p>
                    </div>

                    <!-- Scan status pill -->
                    <div x-show="isScanning" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs font-semibold animate-pulse" x-cloak>
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>AI Scanning in progress...</span>
                    </div>

                    <div x-show="scanSuccess && !isScanning" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs font-semibold" x-cloak>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>AI Auto-Extracted!</span>
                    </div>
                </div>

                <!-- Drag & Drop Zone -->
                <div class="relative border-2 border-dashed rounded-3xl p-8 text-center transition-all duration-200"
                     :class="dragOver ? 'border-[#FF5A36] bg-[#FF5A36]/5' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/30 hover:border-slate-300 dark:hover:border-slate-700'"
                     @dragover.prevent="dragOver = true"
                     @dragleave.prevent="dragOver = false"
                     @drop.prevent="handleDrop($event)">
                    
                    <input type="file" 
                           id="fileInput" 
                           name="file" 
                           accept=".pdf,.png,.jpg,.jpeg,.webp" 
                           class="hidden" 
                           @change="handleFileSelect($event)" 
                           required>

                    <template x-if="!fileSelected">
                        <div class="space-y-3 cursor-pointer" @click="document.getElementById('fileInput').click()">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-[#FF5A36] shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-slate-800 dark:text-white">Click to choose or drag & drop invoice PDF</span>
                                <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG or WebP (Max 25 MB)</p>
                            </div>
                            <button type="button" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                                Browse Files
                            </button>
                        </div>
                    </template>

                    <template x-if="fileSelected">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 text-left">
                            <div class="flex items-center gap-3">
                                <span class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/40 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </span>
                                <div>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white block" x-text="fileName"></span>
                                    <span class="text-xs text-slate-400" x-text="fileSize"></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="triggerAiScan()" 
                                        :disabled="isScanning"
                                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 shadow-md transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span x-text="isScanning ? 'Scanning...' : 'Re-scan with AI'"></span>
                                </button>

                                <button type="button" 
                                        @click="removeFile()" 
                                        class="p-2 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 text-slate-400 rounded-xl transition-all"
                                        title="Remove file">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 2. Invoice Classification & Primary Info -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-5">
                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="p-1.5 bg-amber-500/10 text-amber-600 rounded-xl inline-flex">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                    <span>2. Bill Type & Invoice Details</span>
                </h3>

                <!-- Bill Type Radio Options -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Bill Category <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex items-center gap-3 p-4 rounded-2xl border cursor-pointer transition-all"
                               :class="billType === 'purchase' ? 'border-amber-500 bg-amber-500/5 ring-2 ring-amber-500/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40'">
                            <input type="radio" name="bill_type" value="purchase" x-model="billType" @change="onBillTypeChange('purchase')" class="hidden">
                            <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :class="billType === 'purchase' ? 'border-amber-500 bg-amber-500' : 'border-slate-300 dark:border-slate-600'">
                                <span class="w-2 h-2 rounded-full bg-white" x-show="billType === 'purchase'"></span>
                            </span>
                            <div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Purchase Bill (Incoming)
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block mt-0.5">Invoice from Vendor / Manufacturer / Raw Material Supplier</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-4 rounded-2xl border cursor-pointer transition-all"
                               :class="billType === 'sale' ? 'border-emerald-500 bg-emerald-500/5 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40'">
                            <input type="radio" name="bill_type" value="sale" x-model="billType" @change="onBillTypeChange('sale')" class="hidden">
                            <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :class="billType === 'sale' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-slate-600'">
                                <span class="w-2 h-2 rounded-full bg-white" x-show="billType === 'sale'"></span>
                            </span>
                            <div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Sales Bill (Outgoing)
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block mt-0.5">Tax invoice to Customer, Flipkart, Amazon, JioMart or B2B Client</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Invoice Number, Date, Status, Payment Mode -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Invoice # -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Invoice / Bill # <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="invoice_number" 
                               x-model="invoiceNumber" 
                               required 
                               placeholder="e.g. SAE/26-27/1326 or INV-0042"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-mono font-bold focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

                    <!-- Invoice Date -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Invoice Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" 
                               name="invoice_date" 
                               x-model="invoiceDate" 
                               required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Payment Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" 
                                x-model="status" 
                                required
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Partially Paid">Partially Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Payment Mode
                        </label>
                        <input type="text" 
                               name="payment_mode" 
                               x-model="paymentMode" 
                               placeholder="e.g. Bank Transfer / RTGS / Online"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>
                </div>
            </div>

            <!-- 3. Party Information (Vendor / Customer) -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 bg-blue-500/10 text-blue-600 rounded-xl inline-flex">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <span x-text="billType === 'purchase' ? '3. Vendor / Supplier Details' : '3. Customer / Client / Portal Details'"></span>
                    </h3>

                    <div x-show="seller?.name || buyer?.name" class="text-xs text-slate-400 flex items-center gap-1" x-cloak>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        <span>AI Party Auto-Detected</span>
                    </div>
                </div>

                <!-- Smart Detected Parties Inspector (Visible once AI extracts parties) -->
                <div x-show="seller?.name || buyer?.name" class="p-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl space-y-3" x-cloak>
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            AI Detected Invoice Parties
                        </span>
                        <span class="text-[11px] text-slate-400">Click a card to apply that entity as the main party</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Vendor / Supplier Card (Seller) -->
                        <div @click="applyParty('seller')" 
                             class="p-3.5 rounded-xl border transition-all cursor-pointer relative"
                             :class="(partyName === (seller?.name || '') && (seller?.name || '')) ? 'border-amber-500 bg-amber-500/10 ring-2 ring-amber-500/20 shadow-sm' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700'">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md"
                                      :class="seller?.is_our_company ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300'">
                                    <span x-text="seller?.is_our_company ? 'Indivolt (Host Company)' : 'Vendor / Supplier (Seller)'"></span>
                                </span>
                                <span x-show="partyName === (seller?.name || '') && (seller?.name || '')" class="text-[10px] font-bold text-amber-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Active Party
                                </span>
                            </div>
                            <div class="font-bold text-xs text-slate-900 dark:text-white truncate" x-text="seller?.name || 'Vendor not detected'"></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-0.5" x-text="seller?.gstin ? 'GSTIN: ' + seller.gstin : 'No GSTIN listed'"></div>
                            <div x-show="seller?.address" class="text-[10px] text-slate-400 truncate mt-1" x-text="seller?.address"></div>
                        </div>

                        <!-- Customer / Buyer Card -->
                        <div @click="applyParty('buyer')" 
                             class="p-3.5 rounded-xl border transition-all cursor-pointer relative"
                             :class="(partyName === (buyer?.name || '') && (buyer?.name || '')) ? 'border-emerald-500 bg-emerald-500/10 ring-2 ring-emerald-500/20 shadow-sm' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700'">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md"
                                      :class="buyer?.is_our_company ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300'">
                                    <span x-text="buyer?.is_our_company ? 'Indivolt (Host Company)' : 'Customer / Portal (Buyer)'"></span>
                                </span>
                                <span x-show="partyName === (buyer?.name || '') && (buyer?.name || '')" class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Active Party
                                </span>
                            </div>
                            <div class="font-bold text-xs text-slate-900 dark:text-white truncate" x-text="buyer?.name || 'Customer not detected'"></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-0.5" x-text="buyer?.gstin ? 'GSTIN: ' + buyer.gstin : 'No GSTIN listed'"></div>
                            <div x-show="buyer?.address" class="text-[10px] text-slate-400 truncate mt-1" x-text="buyer?.address"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            <span x-text="billType === 'purchase' ? 'Vendor Name *' : 'Customer / Portal Name *'"></span>
                        </label>
                        <input type="text" 
                               name="party_name" 
                               x-model="partyName" 
                               required 
                               placeholder="e.g. Syntro Appliances Pvt Ltd / Flipkart India"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-semibold focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            GSTIN / Tax ID
                        </label>
                        <input type="text" 
                               name="party_gstin" 
                               x-model="partyGstin" 
                               placeholder="e.g. 07AABCS1429B1Z"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-mono focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Billing Address
                        </label>
                        <textarea name="party_address" 
                                  x-model="partyAddress" 
                                  rows="2" 
                                  placeholder="Address mentioned on the invoice"
                                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Phone / Contact Number
                        </label>
                        <input type="text" 
                               name="party_phone" 
                               x-model="partyPhone" 
                               placeholder="e.g. +91 9876543210"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>
                </div>
            </div>

            <!-- 4. Financial Amounts & Line Items -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="p-1.5 bg-emerald-500/10 text-emerald-600 rounded-xl inline-flex">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span>4. Financial Totals & Line Items</span>
                    </h3>

                    <button type="button" 
                            @click="addLineItem()" 
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                        <span>Add Row</span>
                    </button>
                </div>

                <!-- Line Items Table -->
                <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-2xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/60 text-slate-400 font-semibold border-b border-slate-100 dark:border-slate-800">
                                <th class="py-2.5 px-3">Item Description</th>
                                <th class="py-2.5 px-3 w-28">HSN / SAC</th>
                                <th class="py-2.5 px-3 w-20 text-center">Qty</th>
                                <th class="py-2.5 px-3 w-28 text-right">Unit Rate (₹)</th>
                                <th class="py-2.5 px-2 w-20 text-center">Disc %</th>
                                <th class="py-2.5 px-3 w-32 text-right">Amount (₹)</th>
                                <th class="py-2.5 px-2 w-10 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            <template x-for="(item, index) in lineItems" :key="index">
                                <tr>
                                    <td class="p-2">
                                        <input type="text" x-model="item.item_name" placeholder="Product / Service description" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" x-model="item.hsn" placeholder="HSN" class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs font-mono">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="any" min="0" x-model.number="item.quantity" @input="recalcRow(item)" placeholder="Qty" class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs text-center font-mono">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" x-model.number="item.rate" @input="recalcRow(item)" min="0" placeholder="0.00" class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs text-right font-mono">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="any" min="0" max="100" x-model.number="item.discount_percent" @input="recalcRow(item)" placeholder="0" class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs text-center font-mono">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" step="0.01" x-model.number="item.amount" @input="recalcTotalsFromRows()" min="0" class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs text-right font-mono font-semibold">
                                        <template x-if="item.discount_percent > 0">
                                            <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-mono text-right mt-0.5" x-text="'-₹' + (((parseFloat(item.quantity) || 0) * (parseFloat(item.rate) || 0) * (parseFloat(item.discount_percent) || 0)) / 100).toFixed(2) + ' (' + item.discount_percent + '%)'"></div>
                                        </template>
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" @click="removeLineItem(index)" class="p-1 text-slate-400 hover:text-rose-500 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Hidden inputs to submit line items & raw extracted data -->
                <input type="hidden" name="line_items" :value="JSON.stringify(lineItems)">
                <input type="hidden" name="raw_extracted_data" :value="JSON.stringify(rawExtractedData)">

                <!-- Hidden inputs to store both FROM (Seller) and TO (Buyer) in database -->
                <input type="hidden" name="seller_name" :value="getResolvedSeller().name">
                <input type="hidden" name="seller_gstin" :value="getResolvedSeller().gstin">
                <input type="hidden" name="seller_address" :value="getResolvedSeller().address">
                <input type="hidden" name="seller_phone" :value="getResolvedSeller().phone">

                <input type="hidden" name="buyer_name" :value="getResolvedBuyer().name">
                <input type="hidden" name="buyer_gstin" :value="getResolvedBuyer().gstin">
                <input type="hidden" name="buyer_address" :value="getResolvedBuyer().address">
                <input type="hidden" name="buyer_phone" :value="getResolvedBuyer().phone">

                <!-- Totals Grid (Subtotal, Tax, Grand Total) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Subtotal (Taxable Amount ₹)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               name="subtotal" 
                               x-model.number="subtotal" 
                               @input="recalcGrandTotal()"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-mono font-bold focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Tax / GST Amount (₹)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               name="tax_amount" 
                               x-model.number="taxAmount" 
                               @input="recalcGrandTotal()"
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-mono font-bold focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-900 dark:text-white mb-1">
                            Grand Total (₹) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               step="0.01" 
                               name="total_amount" 
                               x-model.number="totalAmount" 
                               required 
                               class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-950 border-2 border-[#FF5A36] rounded-xl text-slate-900 dark:text-white text-sm font-mono font-extrabold focus:outline-none focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>
                </div>

                <!-- Notes / Remarks -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Notes / Remarks
                    </label>
                    <textarea name="notes" 
                              x-model="notes" 
                              rows="2" 
                              placeholder="Any additional remarks, batch details, or notes about this invoice"
                              class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"></textarea>
                </div>
            </div>

            <!-- Submit Action Bar -->
            <div class="flex items-center justify-between p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
                <a href="{{ route('bills.index', ['type' => $defaultType]) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                    Cancel
                </a>

                <button type="submit" 
                        class="px-8 py-3 bg-[#FF5A36] hover:bg-[#E04826] text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-[#FF5A36]/25 hover:shadow-[#FF5A36]/40 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Bill & Open Split Viewer</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Alpine Component Controller -->
    <script>
        function billUploader() {
            return {
                dragOver: false,
                fileSelected: false,
                fileName: '',
                fileSize: '',
                fileObj: null,
                isScanning: false,
                scanSuccess: false,

                // Form reactive fields
                billType: '{{ $defaultType }}',
                invoiceNumber: '',
                invoiceDate: '{{ now()->format('Y-m-d') }}',
                status: 'Paid',
                paymentMode: 'Bank Transfer',
                partyName: '',
                partyGstin: '',
                partyAddress: '',
                partyPhone: '',
                seller: null,
                buyer: null,
                subtotal: 0.00,
                taxAmount: 0.00,
                totalAmount: 0.00,
                notes: '',
                lineItems: [],
                rawExtractedData: null,

                handleDrop(e) {
                    this.dragOver = false;
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        this.setFile(files[0]);
                        // Set file input files
                        const dt = new DataTransfer();
                        dt.items.add(files[0]);
                        document.getElementById('fileInput').files = dt.files;
                    }
                },

                handleFileSelect(e) {
                    const files = e.target.files;
                    if (files.length > 0) {
                        this.setFile(files[0]);
                    }
                },

                setFile(file) {
                    this.fileObj = file;
                    this.fileSelected = true;
                    this.fileName = file.name;
                    this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                    
                    // Auto-trigger AI scan on file selection!
                    this.triggerAiScan();
                },

                removeFile() {
                    this.fileSelected = false;
                    this.fileName = '';
                    this.fileSize = '';
                    this.fileObj = null;
                    this.scanSuccess = false;
                    document.getElementById('fileInput').value = '';
                },

                async triggerAiScan() {
                    if (!this.fileObj) return;

                    this.isScanning = true;
                    this.scanSuccess = false;

                    const formData = new FormData();
                    formData.append('file', this.fileObj);
                    formData.append('preferred_type', this.billType);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const response = await fetch('{{ route('bills.scan-ai') }}', {
                            method: 'POST',
                            body: formData
                        });

                        const res = await response.json();
                        if (res.success && res.data) {
                            const d = res.data;
                            this.rawExtractedData = d;
                            this.scanSuccess = true;
                            this.seller = d.seller || null;
                            this.buyer = d.buyer || null;

                            if (d.bill_type) this.billType = d.bill_type;
                            if (d.invoice_number) this.invoiceNumber = d.invoice_number;
                            if (d.invoice_date) this.invoiceDate = d.invoice_date;
                            if (d.party_name) this.partyName = d.party_name;
                            if (d.party_gstin) this.partyGstin = d.party_gstin;
                            if (d.party_address) this.partyAddress = d.party_address;
                            if (d.party_phone) this.partyPhone = d.party_phone;
                            if (d.status) this.status = d.status;
                            if (d.payment_mode) this.paymentMode = d.payment_mode;
                            if (d.notes) this.notes = d.notes;

                            if (d.subtotal) this.subtotal = parseFloat(d.subtotal) || 0;
                            if (d.tax_amount) this.taxAmount = parseFloat(d.tax_amount) || 0;
                            if (d.total_amount) this.totalAmount = parseFloat(d.total_amount) || 0;

                            if (Array.isArray(d.items) && d.items.length > 0) {
                                this.lineItems = d.items.map(it => ({
                                    item_name: it.item_name || '',
                                    hsn: it.hsn || '',
                                    quantity: (it.quantity !== undefined && it.quantity !== null && !isNaN(parseFloat(it.quantity))) ? parseFloat(it.quantity) : 1,
                                    rate: parseFloat(it.rate) || 0,
                                    amount: parseFloat(it.amount) || ((parseFloat(it.quantity) || 1) * (parseFloat(it.rate) || 0))
                                }));
                            }
                        }
                    } catch (err) {
                        console.error('Scan error:', err);
                    } finally {
                        this.isScanning = false;
                    }
                },

                applyParty(type) {
                    if (type === 'seller' && this.seller && this.seller.name) {
                        this.partyName = this.seller.name;
                        this.partyGstin = this.seller.gstin || '';
                        this.partyAddress = this.seller.address || '';
                        this.partyPhone = this.seller.phone || '';
                    } else if (type === 'buyer' && this.buyer && this.buyer.name) {
                        this.partyName = this.buyer.name;
                        this.partyGstin = this.buyer.gstin || '';
                        this.partyAddress = this.buyer.address || '';
                        this.partyPhone = this.buyer.phone || '';
                    }
                },

                onBillTypeChange(newType) {
                    this.billType = newType;
                    if (newType === 'purchase' && this.seller && this.seller.name) {
                        this.applyParty('seller');
                    } else if (newType === 'sale' && this.buyer && this.buyer.name) {
                        this.applyParty('buyer');
                    }
                },

                addLineItem() {
                    this.lineItems.push({
                        item_name: '',
                        hsn: '',
                        quantity: 1,
                        rate: 0,
                        discount_percent: 0,
                        amount: 0
                    });
                },

                removeLineItem(index) {
                    this.lineItems.splice(index, 1);
                    this.recalcTotalsFromRows();
                },

                recalcRow(item) {
                    const q = parseFloat(item.quantity) || 0;
                    const r = parseFloat(item.rate) || 0;
                    const disc = parseFloat(item.discount_percent) || 0;
                    const gross = q * r;
                    const discAmount = gross * (disc / 100);
                    item.amount = parseFloat((gross - discAmount).toFixed(2));
                    this.recalcTotalsFromRows();
                },

                recalcTotalsFromRows() {
                    if (this.lineItems.length > 0) {
                        const sum = this.lineItems.reduce((acc, it) => acc + (parseFloat(it.amount) || 0), 0);
                        this.subtotal = parseFloat(sum.toFixed(2));
                        this.recalcGrandTotal();
                    }
                },

                recalcGrandTotal() {
                    const sub = parseFloat(this.subtotal) || 0;
                    const tax = parseFloat(this.taxAmount) || 0;
                    if (sub > 0 || tax > 0) {
                        this.totalAmount = parseFloat((sub + tax).toFixed(2));
                    }
                },

                getResolvedSeller() {
                    if (this.billType === 'purchase') {
                        return {
                            name: (this.seller && this.seller.name) ? this.seller.name : (this.partyName || ''),
                            gstin: (this.seller && this.seller.gstin) ? this.seller.gstin : (this.partyGstin || ''),
                            address: (this.seller && this.seller.address) ? this.seller.address : (this.partyAddress || ''),
                            phone: (this.seller && this.seller.phone) ? this.seller.phone : (this.partyPhone || ''),
                        };
                    } else {
                        return {
                            name: (this.seller && this.seller.name) ? this.seller.name : 'INDIVOLT APPLIANCES & ELECTRICALS',
                            gstin: (this.seller && this.seller.gstin) ? this.seller.gstin : '06AALFI9222R1ZO',
                            address: (this.seller && this.seller.address) ? this.seller.address : 'Sector-43, Basement, 1286P, Near Shalom Hills School, Gurugram, Haryana, 122002',
                            phone: (this.seller && this.seller.phone) ? this.seller.phone : '',
                        };
                    }
                },

                getResolvedBuyer() {
                    if (this.billType === 'sale') {
                        return {
                            name: (this.buyer && this.buyer.name) ? this.buyer.name : (this.partyName || ''),
                            gstin: (this.buyer && this.buyer.gstin) ? this.buyer.gstin : (this.partyGstin || ''),
                            address: (this.buyer && this.buyer.address) ? this.buyer.address : (this.partyAddress || ''),
                            phone: (this.buyer && this.buyer.phone) ? this.buyer.phone : (this.partyPhone || ''),
                        };
                    } else {
                        return {
                            name: (this.buyer && this.buyer.name) ? this.buyer.name : 'INDIVOLT APPLIANCES & ELECTRICALS',
                            gstin: (this.buyer && this.buyer.gstin) ? this.buyer.gstin : '06AALFI9222R1ZO',
                            address: (this.buyer && this.buyer.address) ? this.buyer.address : 'Sector-43, Basement, 1286P, Near Shalom Hills School, Gurugram, Haryana, 122002',
                            phone: (this.buyer && this.buyer.phone) ? this.buyer.phone : '',
                        };
                    }
                },

                beforeSubmit(e) {
                    if (!this.fileSelected) {
                        e.preventDefault();
                        alert('Please choose a scanned document file to upload.');
                    }
                }
            }
        }
    </script>
</x-app-layout>
