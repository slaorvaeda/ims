@props(['portals'])

<div 
    x-data="{ 
        open: false,
        isEdit: false,
        storeId: null,
        storeName: '',
        portalId: '',
        portalType: '',
        status: 'active',
        enabledApis: [],
        
        // Amazon specific fields
        amazonClientId: '',
        amazonClientSecret: '',
        amazonRefreshToken: '',
        amazonRegion: 'na',

        // Flipkart specific fields
        flipkartAppId: '',
        flipkartAppSecret: '',
        flipkartUsername: '',
        flipkartPassword: '',

        // Portals list helper
        portalsList: {{ json_encode($portals->map(fn($p) => ['id' => $p->id, 'name' => strtoupper($p->name)])->toArray()) }},

        init() {
            this.$watch('portalId', value => {
                this.updatePortalType(value);
            });
        },

        updatePortalType(id) {
            const portal = this.portalsList.find(p => p.id == id);
            if (portal) {
                if (portal.name.includes('AMAZON')) {
                    this.portalType = 'amazon';
                } else if (portal.name.includes('FLIPKART')) {
                    this.portalType = 'flipkart';
                } else {
                    this.portalType = 'generic';
                }
            } else {
                this.portalType = '';
            }
        },

        openAdd() {
            this.isEdit = false;
            this.storeId = null;
            this.storeName = '';
            this.portalId = '';
            this.portalType = '';
            this.status = 'active';
            this.enabledApis = [];
            
            this.amazonClientId = '';
            this.amazonClientSecret = '';
            this.amazonRefreshToken = '';
            this.amazonRegion = 'na';

            this.flipkartAppId = '';
            this.flipkartAppSecret = '';
            this.flipkartUsername = '';
            this.flipkartPassword = '';

            this.open = true;
        },

        openEdit(data) {
            this.isEdit = true;
            this.storeId = data.id;
            this.storeName = data.name;
            this.portalId = data.portal_id;
            this.updatePortalType(data.portal_id);
            this.status = data.status;
            this.enabledApis = data.enabled_apis || [];

            const creds = data.credentials || {};
            if (this.portalType === 'amazon') {
                this.amazonClientId = creds.client_id || '';
                this.amazonClientSecret = creds.client_secret || '';
                this.amazonRefreshToken = creds.refresh_token || '';
                this.amazonRegion = creds.region || 'na';
            } else if (this.portalType === 'flipkart') {
                this.flipkartAppId = creds.app_id || '';
                this.flipkartAppSecret = creds.app_secret || '';
                this.flipkartUsername = creds.username || '';
                this.flipkartPassword = creds.password || '';
            }

            this.open = true;
        }
    }"
    @open-store-modal.window="openEdit($event.detail)"
    @open-add-store-modal.window="openAdd()"
    x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <!-- Overlay backdrop -->
    <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity" @click="open = false"></div>

    <!-- Modal Content -->
    <div class="flex min-h-screen items-center justify-center p-4 text-center">
        <div 
            class="relative transform overflow-hidden rounded-[28px] bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 text-left shadow-2xl transition-all w-full max-w-lg p-7"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/60 pb-4">
                <h3 class="text-base font-extrabold text-slate-850 dark:text-white" x-text="isEdit ? 'Edit Store Credentials' : 'Connect Store API'"></h3>
                <button @click="open = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-250 hover:bg-slate-50 dark:hover:bg-slate-850 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form -->
            <form :action="isEdit ? '/stores/' + storeId : '/stores'" method="POST" class="mt-5 space-y-4 text-xs">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <!-- Store Name -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Store Name</label>
                    <input 
                        type="text" 
                        name="store_name" 
                        x-model="storeName" 
                        required 
                        placeholder="e.g. Amazon India - Brand Store"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl focus:border-[#FF5A36] focus:ring-1 focus:ring-[#FF5A36]/20 outline-none text-slate-800 dark:text-slate-255"
                    >
                </div>

                <!-- Select Sales Portal -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Sales Portal Channel</label>
                    <select 
                        name="portal_vendor_id" 
                        x-model="portalId" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl focus:border-[#FF5A36] focus:ring-1 focus:ring-[#FF5A36]/20 outline-none text-slate-800 dark:text-slate-255"
                    >
                        <option value="" disabled selected>-- Choose Portal Channel --</option>
                        @foreach($portals as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- DYNAMIC CREDENTIAL FIELDS: AMAZON SP-API -->
                <div x-show="portalType === 'amazon'" class="space-y-3.5 bg-amber-500/5 border border-amber-500/10 rounded-2xl p-4.5 mt-2">
                    <span class="text-[10px] font-extrabold text-amber-600 dark:text-amber-400 uppercase tracking-widest block mb-1">Amazon SP-API Keys</span>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">LWA Client ID</label>
                        <input type="text" name="amazon_client_id" x-model="amazonClientId" :required="portalType === 'amazon'" placeholder="amzn1.application-oa2-client.xxx" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">LWA Client Secret</label>
                        <input type="password" name="amazon_client_secret" x-model="amazonClientSecret" :required="portalType === 'amazon'" placeholder="••••••••••••••••" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">SP-API Refresh Token</label>
                        <textarea rows="2" name="amazon_refresh_token" x-model="amazonRefreshToken" :required="portalType === 'amazon'" placeholder="Atzr|IwEBxxx..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255 resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">AWS region endpoint</label>
                        <select name="amazon_region" x-model="amazonRegion" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                            <option value="na">North America (US, CA, MX, BR)</option>
                            <option value="eu">Europe (UK, DE, FR, IT, ES, IN)</option>
                            <option value="fe">Far East (JP, AU, SG)</option>
                        </select>
                    </div>
                </div>

                <!-- DYNAMIC CREDENTIAL FIELDS: FLIPKART API -->
                <div x-show="portalType === 'flipkart'" class="space-y-3.5 bg-blue-500/5 border border-blue-500/10 rounded-2xl p-4.5 mt-2">
                    <span class="text-[10px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-widest block mb-1">Flipkart Seller API Keys</span>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Application ID</label>
                        <input type="text" name="flipkart_app_id" x-model="flipkartAppId" :required="portalType === 'flipkart'" placeholder="e.g. app-id-1234" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Application Secret</label>
                        <input type="password" name="flipkart_app_secret" x-model="flipkartAppSecret" :required="portalType === 'flipkart'" placeholder="••••••••••••••••" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">API Seller Username</label>
                        <input type="text" name="flipkart_username" x-model="flipkartUsername" :required="portalType === 'flipkart'" placeholder="email@domain.com" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">API Seller Password</label>
                        <input type="password" name="flipkart_password" x-model="flipkartPassword" :required="portalType === 'flipkart'" placeholder="••••••••••••••••" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-xl outline-none focus:border-[#FF5A36] text-slate-800 dark:text-slate-255">
                    </div>
                </div>

                <!-- API INTEGRATION FLAGS -->
                <div class="mt-4 bg-slate-50 dark:bg-slate-850 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl p-4.5">
                    <span class="text-[10px] font-extrabold text-slate-450 dark:text-slate-400 uppercase tracking-wider block mb-2.5">Enabled API Integrations</span>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-slate-100/50 dark:hover:bg-slate-800 rounded-xl transition-all select-none">
                            <input type="checkbox" name="enabled_apis[]" value="orders" x-model="enabledApis" class="rounded text-[#FF5A36] focus:ring-[#FF5A36]">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Orders API</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-slate-100/50 dark:hover:bg-slate-800 rounded-xl transition-all select-none">
                            <input type="checkbox" name="enabled_apis[]" value="inventory" x-model="enabledApis" class="rounded text-[#FF5A36] focus:ring-[#FF5A36]">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Inventory API</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-slate-100/50 dark:hover:bg-slate-800 rounded-xl transition-all select-none">
                            <input type="checkbox" name="enabled_apis[]" value="pricing" x-model="enabledApis" class="rounded text-[#FF5A36] focus:ring-[#FF5A36]">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Pricing API</span>
                        </label>
                    </div>
                </div>

                <!-- Status Settings -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Connection Status</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" x-model="status" class="text-[#FF5A36] focus:ring-[#FF5A36]">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" x-model="status" class="text-[#FF5A36] focus:ring-[#FF5A36]">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Inactive</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons Footer -->
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800/60 pt-4 mt-6">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 font-bold text-slate-650 dark:text-slate-300 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#FF5A36] hover:bg-[#E04826] text-white font-extrabold rounded-xl transition-all shadow-md shadow-[#FF5A36]/15">
                        Save Connections
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
