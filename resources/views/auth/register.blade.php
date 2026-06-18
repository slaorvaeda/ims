<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - IMS</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN Fallback for robust rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f8ff 0%, #eef3ff 100%);
        }
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .soft-gradient-bg {
            background: radial-gradient(circle at 10% 20%, rgba(216, 241, 230, 0.46) 0.1%, rgba(233, 226, 251, 0.43) 90.1%);
        }
        .pattern-bg {
            background-color: #0c0f19;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(16, 24, 48, 0.9) 0%, #080b11 100%),
                repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.01) 0px, rgba(255, 255, 255, 0.01) 1px, transparent 1px, transparent 10px),
                repeating-linear-gradient(-45deg, rgba(255, 255, 255, 0.01) 0px, rgba(255, 255, 255, 0.01) 1px, transparent 1px, transparent 10px);
        }
        .input-shadow {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-6 lg:p-8">
    <main class="w-full max-w-7xl bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 flex flex-col lg:flex-row min-h-[90vh]">
        
        <!-- Left Side: Register Form (approx 45%) -->
        <section class="w-full lg:w-[47%] p-8 md:p-12 lg:p-16 flex flex-col justify-between soft-gradient-bg min-h-[600px] lg:min-h-0">
            <!-- Top Bar / Logo -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-black rounded-full flex items-center justify-center shadow-md">
                    <img src="/logo.png" alt="">
                </div>
                <span class="text-xl font-bold text-slate-900 tracking-tight font-heading">IMS</span>
            </div>

            <!-- Register Form Container -->
            <div class="my-auto max-w-md w-full mx-auto py-8">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight font-heading mb-2">Create Account</h2>
                <p class="text-slate-500 text-sm mb-8">Please fill in details below to register</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name Input -->
                    <div>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                placeholder="Full Name" 
                                required 
                                autofocus 
                                autocomplete="name"
                                class="w-full px-5 py-4 bg-white border border-gray-200/80 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 text-slate-800 text-sm transition-all input-shadow outline-none"
                            >
                        </div>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <div class="relative">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                placeholder="Email" 
                                required 
                                autocomplete="username"
                                class="w-full px-5 py-4 bg-white border border-gray-200/80 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 text-slate-800 text-sm transition-all input-shadow outline-none"
                            >
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Password" 
                                required 
                                autocomplete="new-password"
                                class="w-full px-5 py-4 bg-white border border-gray-200/80 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 text-slate-800 text-sm transition-all input-shadow outline-none pr-12"
                            >
                            <!-- Password Toggle Eye Icon -->
                            <button type="button" onclick="togglePassword('password', 'eye-icon-pwd')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg id="eye-icon-pwd" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Input -->
                    <div>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Confirm Password" 
                                required 
                                autocomplete="new-password"
                                class="w-full px-5 py-4 bg-white border border-gray-200/80 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 text-slate-800 text-sm transition-all input-shadow outline-none pr-12"
                            >
                            <!-- Password Toggle Eye Icon -->
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg id="eye-icon-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-rose-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="w-full py-4 bg-slate-950 hover:bg-slate-800 active:bg-slate-900 text-white font-semibold rounded-xl text-sm transition-all shadow-md shadow-slate-900/10 mt-6">
                        Sign up
                    </button>
                </form>

            </div>

            <!-- Sign In Link -->
            <div class="text-center text-xs text-slate-500">
                Already registered? 
                <a href="{{ route('login') }}" class="font-bold text-slate-800 hover:underline">Sign In</a>
            </div>
        </section>

        <!-- Right Side: Dark Character Illustration Card (approx 55%) -->
        <section class="w-full lg:w-[53%] p-8 flex flex-col justify-between pattern-bg rounded-[2.5rem] lg:rounded-l-none overflow-hidden m-2 lg:m-0 min-h-[500px] lg:min-h-0">
            <div></div> <!-- Spacer -->

            <!-- Illustration SVG Details with fixed constraint -->
            <div class="relative flex justify-center items-center my-4 flex-1">
                <!-- Character Image constrained to avoid stretching layout -->
                <div class="relative z-10 max-w-sm w-full flex justify-center">
                    <img src="/login.svg" alt="IMS illustration" class="max-h-[380px] md:max-h-[420px] w-auto object-contain">
                </div>
            </div>

            <!-- Lower Copy -->
            <div class="text-center max-w-lg mx-auto pb-6 z-10 px-4">
                <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white font-heading mb-3">
                    Manage your Money Anywhere
                </h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    you can Manage your Money on the go with Quicken on the web
                </p>

                <!-- Custom dots indicators -->
                <div class="flex items-center justify-center gap-2 mt-8">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-700 transition-all"></span>
                    <span class="w-6 h-1.5 rounded-full bg-emerald-400 transition-all"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-700 transition-all"></span>
                </div>
            </div>
        </section>
        
    </main>

    <!-- Script for toggle password visibility -->
    <script>
        function togglePassword(inputId, iconId) {
            const pwdInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                // Switch to eye icon
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><circle cx="12" cy="12" r="3" />`;
            } else {
                pwdInput.type = 'password';
                // Switch back to eye-slash icon
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />`;
            }
        }
    </script>
</body>
</html>
