<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            {{ __('Account Profile Settings') }}
        </h2>
    </x-slot>

    <!-- Custom styling inside layouts/app.blade.php slot -->
    <style>
        .profile-card input[type="text"],
        .profile-card input[type="email"],
        .profile-card input[type="password"] {
            background-color: #f8fafc !important; /* bg-slate-50 */
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            border-radius: 1rem !important; /* rounded-2xl */
            padding: 0.875rem 1.25rem !important; /* py-3.5 px-5 */
            font-size: 0.875rem !important;
            color: #1e293b !important;
            width: 100% !important;
            transition: all 0.2s !important;
        }
        .dark .profile-card input[type="text"],
        .dark .profile-card input[type="email"],
        .dark .profile-card input[type="password"] {
            background-color: rgba(15, 23, 42, 0.6) !important; /* bg-slate-950/60 */
            border: 1px solid rgba(30, 41, 59, 0.8) !important;
            color: #e2e8f0 !important;
        }
        .profile-card input:focus {
            border-color: #FF5A36 !important;
            box-shadow: 0 0 0 2px rgba(255, 90, 54, 0.15) !important;
        }
        .profile-card button,
        .profile-card button[type="submit"] {
            background-color: #0f172a !important; /* bg-slate-950 */
            border-radius: 1rem !important;
            font-weight: 700 !important;
            font-size: 0.825rem !important;
            padding: 0.825rem 1.75rem !important;
            color: white !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            transition: all 0.2s !important;
            border: none !important;
            cursor: pointer !important;
        }
        .dark .profile-card button,
        .dark .profile-card button[type="submit"] {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
        .profile-card button:hover,
        .profile-card button[type="submit"]:hover {
            background-color: #334155 !important;
            transform: scale(1.02) !important;
        }
        .dark .profile-card button:hover,
        .dark .profile-card button[type="submit"]:hover {
            background-color: white !important;
        }
        .profile-card button.bg-red-600 {
            background-color: #ef4444 !important;
        }
        .profile-card button.bg-red-600:hover {
            background-color: #dc2626 !important;
        }
        .profile-card label {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #64748b !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }
        .dark .profile-card label {
            color: #94a3b8 !important;
        }
        .profile-card h2 {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            font-size: 1.125rem !important;
            letter-spacing: -0.01em !important;
        }
        .dark .profile-card h2 {
            color: white !important;
        }
        .profile-card p {
            color: #64748b !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            margin-top: 0.25rem !important;
        }
        .dark .profile-card p {
            color: #94a3b8 !important;
        }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto space-y-8 px-4 sm:px-6">
            <!-- Profile Info Form -->
            <div class="p-8 sm:p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm relative overflow-hidden profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Form -->
            <div class="p-8 sm:p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm relative overflow-hidden profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Form -->
            <div class="p-8 sm:p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2rem] shadow-sm relative overflow-hidden profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
