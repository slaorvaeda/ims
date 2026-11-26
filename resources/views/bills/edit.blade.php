<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('bills.show', $bill) }}" 
                   class="p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#FF5A36] rounded-2xl transition-all shadow-sm flex items-center justify-center"
                   title="Back to Bill">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                        {{ __('Edit Bill #' . $bill->invoice_number) }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Update invoice details, amounts, line items, or replace the scanned document.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="billEditor()" class="max-w-5xl mx-auto space-y-6">
        
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

        <form method="POST" action="{{ route('bills.update', $bill) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Document Management Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="p-1.5 bg-[#FF5A36]/10 text-[#FF5A36] rounded-xl inline-flex">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </span>
                    <span>1. Scanned Document</span>
                </h3>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/40 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ $bill->file_name }}</span>
                            <span class="text-[11px] text-slate-400">Current file ({{ $bill->file_size_human }})</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ $bill->file_url }}" target="_blank" class="px-3 py-1.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 hover:text-[#FF5A36] transition-colors">
                            Preview Current
                        </a>
                        <label class="px-4 py-1.5 bg-[#FF5A36] hover:bg-[#E04826] text-white text-xs font-semibold rounded-xl cursor-pointer transition-all shadow-sm">
                            <span>Replace Document</span>
                            <input type="file" name="file" accept=".pdf,.png,.jpg,.jpeg,.webp" class="hidden" @change="onNewFile($event)">
                        </label>
                    </div>
                </div>

                <div x-show="newFileName" class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs text-emerald-800 dark:text-emerald-300 flex items-center justify-between" x-cloak>
                    <span>New file selected: <strong x-text="newFileName"></strong></span>
                    <button type="button" @click="clearNewFile()" class="text-rose-500 font-bold hover:underline">Cancel</button>
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
                            <input type="radio" name="bill_type" value="purchase" x-model="billType" class="hidden">
                            <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :class="billType === 'purchase' ? 'border-amber-500 bg-amber-500' : 'border-slate-300 dark:border-slate-600'">
                                <span class="w-2 h-2 rounded-full bg-white" x-show="billType === 'purchase'"></span>
                            </span>
                            <div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Purchase Bill (Incoming)
                                </span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-4 rounded-2xl border cursor-pointer transition-all"
                               :class="billType === 'sale' ? 'border-emerald-500 bg-emerald-500/5 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40'">
                            <input type="radio" name="bill_type" value="sale" x-model="billType" class="hidden">
                            <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :class="billType === 'sale' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-slate-600'">
                                <span class="w-2 h-2 rounded-full bg-white" x-show="billType === 'sale'"></span>
                            </span>
                            <div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Sales Bill (Outgoing)
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Invoice Number, Date, Status, Payment Mode -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Invoice / Bill # <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="invoice_number" 
                               x-model="invoiceNumber" 
                               required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-mono font-bold focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

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

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Payment Mode
                        </label>
                        <input type="text" 
                               name="payment_mode" 
                               x-model="paymentMode" 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>
                </div>
            </div>

            <!-- 3. Party Information (Vendor / Customer) -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="p-1.5 bg-blue-500/10 text-blue-600 rounded-xl inline-flex">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                    <span x-text="billType === 'purchase' ? '3. Vendor / Supplier Details' : '3. Customer / Client / Portal Details'"></span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            <span x-text="billType === 'purchase' ? 'Vendor Name *' : 'Customer / Portal Name *'"></span>
                        </label>
                        <input type="text" 
                               name="party_name" 
                               x-model="partyName" 
                               required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs font-semibold focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            GSTIN / Tax ID
                        </label>
                        <input type="text" 
                               name="party_gstin" 
                               x-model="partyGstin" 
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
                                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Phone / Contact Number
                        </label>
                        <input type="text" 
                               name="party_phone" 
                               x-model="partyPhone" 
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
                                        <input type="text" x-model="item.item_name" class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" x-model="item.hsn" class="w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-xs font-mono">
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

                <input type="hidden" name="line_items" :value="JSON.stringify(lineItems)">

                <!-- Hidden inputs to preserve both FROM (Seller) and TO (Buyer) in database -->
                <input type="hidden" name="seller_name" :value="sellerName">
                <input type="hidden" name="seller_gstin" :value="sellerGstin">
                <input type="hidden" name="seller_address" :value="sellerAddress">
                <input type="hidden" name="seller_phone" :value="sellerPhone">
                <input type="hidden" name="buyer_name" :value="buyerName">
                <input type="hidden" name="buyer_gstin" :value="buyerGstin">
                <input type="hidden" name="buyer_address" :value="buyerAddress">
                <input type="hidden" name="buyer_phone" :value="buyerPhone">

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
                              class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-xl text-slate-800 dark:text-slate-200 text-xs focus:border-[#FF5A36] focus:ring-2 focus:ring-[#FF5A36]/20 transition-all"></textarea>
                </div>
            </div>

            <!-- Submit Action Bar -->
            <div class="flex items-center justify-between p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
                <a href="{{ route('bills.show', $bill) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                    Cancel
                </a>

                <button type="submit" 
                        class="px-8 py-3 bg-[#FF5A36] hover:bg-[#E04826] text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-[#FF5A36]/25 hover:shadow-[#FF5A36]/40 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Update Bill & Return to Split Viewer</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function billEditor() {
            return {
                newFileName: '',
                billType: '{{ $bill->bill_type }}',
                invoiceNumber: '{{ $bill->invoice_number }}',
                invoiceDate: '{{ $bill->invoice_date ? $bill->invoice_date->format('Y-m-d') : '' }}',
                status: '{{ $bill->status }}',
                paymentMode: '{{ $bill->payment_mode ?? 'Bank Transfer' }}',
                partyName: @json($bill->party_name),
                partyGstin: @json($bill->party_gstin ?? ''),
                partyAddress: @json($bill->party_address ?? ''),
                partyPhone: @json($bill->party_phone ?? ''),
                sellerName: @json($bill->seller_name ?? ($bill->from_party['name'] ?? '')),
                sellerGstin: @json($bill->seller_gstin ?? ($bill->from_party['gstin'] ?? '')),
                sellerAddress: @json($bill->seller_address ?? ($bill->from_party['address'] ?? '')),
                sellerPhone: @json($bill->seller_phone ?? ($bill->from_party['phone'] ?? '')),
                buyerName: @json($bill->buyer_name ?? ($bill->to_party['name'] ?? '')),
                buyerGstin: @json($bill->buyer_gstin ?? ($bill->to_party['gstin'] ?? '')),
                buyerAddress: @json($bill->buyer_address ?? ($bill->to_party['address'] ?? '')),
                buyerPhone: @json($bill->buyer_phone ?? ($bill->to_party['phone'] ?? '')),
                subtotal: {{ (float) ($bill->subtotal ?? 0) }},
                taxAmount: {{ (float) ($bill->tax_amount ?? 0) }},
                totalAmount: {{ (float) ($bill->total_amount ?? 0) }},
                notes: @json($bill->notes ?? ''),
                lineItems: @json($bill->line_items ?? []),

                onNewFile(e) {
                    const files = e.target.files;
                    if (files.length > 0) {
                        this.newFileName = files[0].name + ' (' + (files[0].size / (1024 * 1024)).toFixed(2) + ' MB)';
                    }
                },

                clearNewFile() {
                    this.newFileName = '';
                    const fileInput = document.querySelector('input[name="file"]');
                    if (fileInput) fileInput.value = '';
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
                }
            }
        }
    </script>
</x-app-layout>
