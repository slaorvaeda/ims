<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Add New System Operator') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6" x-data="{ role: '{{ old('role', 'operator') }}' }">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="e.g., Sitaram" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('name')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="e.g., sitaram@example.com" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('email')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    @error('password')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">User Status</label>
                    <select 
                        id="status" 
                        name="status" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">User Role</label>
                    <select 
                        id="role" 
                        name="role" 
                        required 
                        x-model="role"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                        <option value="operator" {{ old('role', 'operator') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Operator Permissions Checklist -->
                <div x-show="role === 'operator'" x-transition class="space-y-4 p-5 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-800/50 rounded-2xl">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Operator Permissions Matrix</label>
                    
                    <div class="space-y-4">
                        @php
                            $modules = [
                                'products' => [
                                    'title' => 'Products Master',
                                    'actions' => [
                                        'view' => 'View List',
                                        'create' => 'Create/Edit',
                                        'import' => 'Import (Excel/CSV)',
                                        'export' => 'Export (Excel/CSV)',
                                    ]
                                ],
                                'purchases' => [
                                    'title' => 'Purchase History',
                                    'actions' => [
                                        'view' => 'View List',
                                        'create' => 'Create/Edit',
                                        'import' => 'Import (Excel/CSV)',
                                        'export' => 'Export (Excel/CSV)',
                                    ]
                                ],
                                'inward_item_codes' => [
                                    'title' => 'Inward Serial Codes',
                                    'actions' => [
                                        'view' => 'View List',
                                        'scan' => 'Scan (Dispatch)',
                                        'import' => 'Import (Excel/CSV)',
                                        'export' => 'Export (Excel/CSV)',
                                    ]
                                ],
                                'sales' => [
                                    'title' => 'Sales Registry',
                                    'actions' => [
                                        'view' => 'View List',
                                        'create' => 'Create/Edit',
                                        'import' => 'Import (Excel/CSV)',
                                        'export' => 'Export (Excel/CSV)',
                                    ]
                                ],
                                'dispatch_item_codes' => [
                                    'title' => 'Dispatch Serial Codes',
                                    'actions' => [
                                        'view' => 'View List',
                                        'scan' => 'Scan (Cancel)',
                                        'import' => 'Import (Excel/CSV)',
                                        'export' => 'Export (Excel/CSV)',
                                    ]
                                ],
                            ];
                        @endphp

                        @foreach($modules as $moduleKey => $moduleData)
                            <div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/60 rounded-xl p-4 space-y-3">
                                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-2">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $moduleData['title'] }}</span>
                                    <div class="flex items-center gap-3 text-[10px] text-slate-400 font-semibold select-none">
                                        <button type="button" @click="
                                            document.querySelectorAll('[data-module={{ $moduleKey }}]').forEach(el => el.checked = true);
                                        " class="hover:text-[#FF5A36] transition-all">Select All</button>
                                        <span>|</span>
                                        <button type="button" @click="
                                            document.querySelectorAll('[data-module={{ $moduleKey }}]').forEach(el => el.checked = false);
                                        " class="hover:text-rose-500 transition-all">Clear</button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    @foreach($moduleData['actions'] as $actionKey => $actionLabel)
                                        @php
                                            $permissionValue = "{$moduleKey}.{$actionKey}";
                                            $oldPermissions = old('permissions', []);
                                            // By default, check all for new users
                                            $isChecked = empty($oldPermissions) || in_array($permissionValue, $oldPermissions);
                                        @endphp
                                        <label class="flex items-center gap-2 cursor-pointer py-1">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissionValue }}" data-module="{{ $moduleKey }}"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   class="w-4 h-4 rounded text-[#FF5A36] focus:ring-[#FF5A36] border-slate-350 dark:border-slate-700 dark:bg-slate-950">
                                            <span class="font-semibold text-slate-650 dark:text-slate-350 text-[11px]">{{ $actionLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Barcode Generator -->
                        <div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/60 rounded-xl p-4 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Barcode Generator</span>
                            @php
                                $barcodeChecked = empty($oldPermissions) || in_array('barcodes.view', $oldPermissions);
                            @endphp
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="barcodes.view" data-module="barcodes"
                                       {{ $barcodeChecked ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-[#FF5A36] focus:ring-[#FF5A36] border-slate-350 dark:border-slate-700 dark:bg-slate-950">
                                <span class="font-semibold text-slate-650 dark:text-slate-300 text-[11px]">Enable Access</span>
                            </label>
                        </div>
                    </div>
                    @error('permissions')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                    <a href="{{ route('users.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-200 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-semibold rounded-2xl text-sm transition-all hover:bg-slate-800 dark:hover:bg-slate-100 shadow-md">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
