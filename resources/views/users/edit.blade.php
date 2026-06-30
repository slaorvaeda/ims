<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6" x-data="{ role: '{{ old('role', $user->role) }}' }">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
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
                        value="{{ old('email', $user->email) }}" 
                        placeholder="e.g., sitaram@example.com" 
                        required 
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    @error('email')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Change Password (Leave blank to keep current)</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all font-mono"
                    >
                    @error('password')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
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
                        <option value="Active" {{ old('status', $user->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $user->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Operator Permissions Checklist -->
                <div x-show="role === 'operator'" x-transition class="space-y-4 p-5 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-800/50 rounded-2xl">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Operator Permissions</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                            $availablePermissions = [
                                'products' => 'Products Master',
                                'purchases' => 'Purchase History',
                                'inward_item_codes' => 'Inward Serial Codes',
                                'sales' => 'Sales History',
                                'dispatch_item_codes' => 'Dispatch Serial Codes',
                                'barcodes' => 'Barcode Generator',
                            ];
                        @endphp
                        @foreach($availablePermissions as $value => $label)
                            <label class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all">
                                <input type="checkbox" name="permissions[]" value="{{ $value }}" 
                                       {{ in_array($value, old('permissions', is_array($user->permissions) ? $user->permissions : ['products', 'purchases', 'inward_item_codes', 'sales', 'dispatch_item_codes', 'barcodes'])) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $label }}</span>
                            </label>
                        @endforeach
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
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
