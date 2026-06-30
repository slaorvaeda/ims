<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                {{ __('User Master Directory') }}
            </h2>
            <a href="{{ route('users.create') }}" class="px-5 py-2.5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-sm font-semibold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-150 flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5M4.75 12h14.5"/></svg>
                <span>Add User</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search Panel -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Search users by name or email..." 
                        class="w-full pl-11 pr-5 py-3.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl text-slate-800 dark:text-slate-200 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 dark:focus:ring-indigo-950/30 transition-all"
                    >
                    <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl text-sm transition-all shadow-md shadow-indigo-900/10">
                    Apply Search
                </button>
                @if ($search)
                    <a href="{{ route('users.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-2xl text-sm text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Panel -->
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/60 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Name</th>
                            <th class="py-4 px-6">Email Address</th>
                            <th class="py-4 px-6">Role</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Registered On</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 text-slate-700 dark:text-slate-300 transition-colors">
                                <td class="py-4.5 px-6 font-semibold">{{ $user->id }}</td>
                                <td class="py-4.5 px-6 font-bold text-slate-900 dark:text-white">{{ $user->name }}</td>
                                <td class="py-4.5 px-6">{{ $user->email }}</td>
                                <td class="py-4.5 px-6">
                                    @if ($user->role === 'admin')
                                        <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded-lg border border-amber-100 dark:border-amber-900/40 uppercase tracking-wider">
                                            Admin
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-[10px] font-bold rounded-lg border border-slate-200/80 dark:border-slate-700/30 uppercase tracking-wider">
                                            Operator
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6">
                                    @if ($user->status === 'Active')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/50">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6 text-slate-400 text-xs">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/80">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
