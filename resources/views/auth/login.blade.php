<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — IMS · Inventory Management System</title>
    <meta name="description" content="Sign in to IMS – The ultimate solution for physical stock and inventory management.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('lofg.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; }

        :root {
            --brand: #FF5A36;
            --brand-light: #FF7A58;
            --brand-dark: #E04020;
            --brand-bg: #FFF3F0;
            --brand-glow: rgba(255,90,54,0.15);
        }

        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #F5F6FA;
            overflow: hidden;
        }

        .font-heading { font-family: 'Outfit', sans-serif; }

        /* ── Animated background blobs ── */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 {
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(255,90,54,0.10) 0%, rgba(255,90,54,0.03) 60%, transparent 100%);
            top: -220px; right: -180px;
            animation: blobDrift1 20s ease-in-out infinite;
        }
        .blob-2 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
            bottom: -200px; left: -150px;
            animation: blobDrift2 25s ease-in-out infinite;
        }
        .blob-3 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(245,158,11,0.07) 0%, transparent 70%);
            top: 35%; left: 35%;
            animation: blobDrift3 16s ease-in-out infinite;
        }

        @keyframes blobDrift1 {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(-60px,50px) scale(1.08); }
            66%      { transform: translate(40px,-30px) scale(0.96); }
        }
        @keyframes blobDrift2 {
            0%,100% { transform: translate(0,0) scale(1); }
            40%      { transform: translate(60px,-70px) scale(1.12); }
            70%      { transform: translate(-35px,45px) scale(0.92); }
        }
        @keyframes blobDrift3 {
            0%,100% { transform: translate(-50%,-50%) scale(1); opacity: 0.6; }
            50%      { transform: translate(-50%,-50%) scale(1.25); opacity: 1; }
        }

        /* ── Dot grid ── */
        .dot-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: radial-gradient(circle, rgba(100,116,139,0.12) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ── Main wrapper ── */
        .page-wrap {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 16px;
        }

        /* ── Card ── */
        .login-card {
            width: 100%;
            max-width: 1200px;
            background: #FFFFFF;
            border-radius: 28px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow:
                0 0 0 1px rgba(0,0,0,0.06),
                0 4px 6px rgba(0,0,0,0.03),
                0 20px 60px rgba(0,0,0,0.08);
            min-height: 650px;
            animation: cardIn 0.6s cubic-bezier(0.16,1,0.3,1) both;
        }
        @media (min-width: 1024px) { .login-card { flex-direction: row; } }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Form panel (left) ── */
        .form-panel {
            width: 100%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #FFFFFF;
            border-right: 1px solid #F1F5F9;
        }
        @media (min-width: 1024px) { .form-panel { width: 42%; padding: 44px 48px; } }
        @media (min-width: 1280px) { .form-panel { width: 40%; } }

        /* ── Logo pill ── */
        .logo-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .logo-icon {
            width: 40px; height: 40px;
            border-radius: 14px;
            background: linear-gradient(135deg, #FF5A36, #FF2D00);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(255,90,54,0.25);
            flex-shrink: 0;
        }

        /* ── Heading badge ── */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FFF3F0;
            border: 1px solid rgba(255,90,54,0.2);
            border-radius: 100px;
            padding: 4px 12px;
            margin-bottom: 14px;
        }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #FF5A36;
            animation: pulseDot 2s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%,100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(1.3); opacity: 0.7; }
        }

        /* ── Inputs ── */
        .form-label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: #64748B;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 7px;
            transition: color 0.25s;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
            transition: color 0.25s;
        }
        .form-input {
            width: 100%;
            padding: 13px 15px 13px 44px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 13px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            color: #0F172A;
            outline: none;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .form-input::placeholder { color: #CBD5E1; }
        .form-input:focus {
            background: #FFFAF9;
            border-color: #FF5A36;
            box-shadow: 0 0 0 3px rgba(255,90,54,0.10), 0 2px 8px rgba(255,90,54,0.06);
        }
        .form-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: #FF5A36; }

        /* ── Custom checkbox ── */
        .custom-check {
            width: 17px; height: 17px;
            appearance: none; -webkit-appearance: none;
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .custom-check:checked {
            background: #FF5A36;
            border-color: #FF5A36;
        }
        .custom-check:checked::after {
            content: '';
            position: absolute;
            left: 4px; top: 1px;
            width: 5px; height: 9px;
            border: 2px solid white;
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        /* ── Submit button ── */
        .btn-signin {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #FF5A36 0%, #FF2D00 100%);
            border: none;
            border-radius: 13px;
            color: white;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.01em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 6px 24px rgba(255,90,54,0.30);
        }
        .btn-signin::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 55%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(255,90,54,0.42);
        }
        .btn-signin:hover::before { opacity: 1; }
        .btn-signin:active { transform: translateY(0); }
        .btn-signin::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 55%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
            transform: skewX(-20deg);
            animation: shimmerBtn 3s ease-in-out infinite;
        }
        @keyframes shimmerBtn {
            0%      { left: -100%; }
            40%,100% { left: 150%; }
        }

        /* ── Staggered entry ── */
        .anim { opacity: 0; }
        .anim.d1 { animation: slideUp 0.65s 0.08s cubic-bezier(0.16,1,0.3,1) forwards; }
        .anim.d2 { animation: slideUp 0.65s 0.16s cubic-bezier(0.16,1,0.3,1) forwards; }
        .anim.d3 { animation: slideUp 0.65s 0.24s cubic-bezier(0.16,1,0.3,1) forwards; }
        .anim.d4 { animation: slideUp 0.65s 0.32s cubic-bezier(0.16,1,0.3,1) forwards; }
        .anim.d5 { animation: slideUp 0.65s 0.40s cubic-bezier(0.16,1,0.3,1) forwards; }
        .anim.d6 { animation: slideUp 0.65s 0.48s cubic-bezier(0.16,1,0.3,1) forwards; }
        .anim.d7 { animation: slideUp 0.65s 0.56s cubic-bezier(0.16,1,0.3,1) forwards; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Right panel (Full Height) ── */
        .visual-panel {
            width: 100%;
            background: #0F172A;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            min-height: 580px;
            border-top: 1px solid #F1F5F9;
            padding: 0;
        }
        @media (min-width: 1024px) {
            .visual-panel {
                width: 58%;
                border-top: none;
                border-left: 1px solid #F1F5F9;
                padding: 0;
                align-self: stretch;
            }
        }
        @media (min-width: 1280px) {
            .visual-panel {
                width: 60%;
            }
        }

        /* Right panel decorative corner blobs */
        .panel-blob-1 {
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(255,90,54,0.09) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .panel-blob-2 {
            position: absolute;
            bottom: -60px; left: -60px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(99,102,241,0.07) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }



        /* ── Divider ── */
        .divider {
            display: flex; align-items: center; gap: 12px;
            color: #94A3B8; font-size: 12px; font-weight: 500;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #E2E8F0;
        }
    </style>
</head>
<body>

    <!-- Animated background blobs -->
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>

    <!-- Dot grid texture -->
    <div class="dot-grid"></div>

    <!-- Particles canvas -->
    <canvas id="particles" style="position:fixed;inset:0;z-index:1;pointer-events:none;"></canvas>

    <div class="page-wrap">
        <div class="login-card">

            <!-- ══════════ LEFT: Form Panel ══════════ -->
            <div class="form-panel">

                <!-- Logo -->
                <div class="anim d1">
                    <div class="logo-pill">
                        <div class="logo-icon">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        </div>
                        <div>
                            <span style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:800;color:#0F172A;letter-spacing:-0.5px;">IMS</span>
                            <span style="font-size:10px;font-weight:700;color:#94A3B8;letter-spacing:0.12em;text-transform:uppercase;margin-left:6px;">Pro</span>
                        </div>
                    </div>
                </div>

                <!-- Form body -->
                <div style="margin: auto 0; max-width: 360px; width: 100%; padding: 24px 0;">

                    <!-- Heading -->
                    <div class="anim d2">
                        <h1 style="font-family:'Outfit',sans-serif;font-size:36px;font-weight:900;color:#0F172A;line-height:1.15;letter-spacing:-0.5px;">
                            Welcome<br>
                            <span style="background: linear-gradient(to right, #FF5A36, #FF9A00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Back 👋</span>
                        </h1>
                        <p style="margin-top:8px;font-size:14px;color:#64748B;font-weight:500;">Sign in to your IMS workspace</p>
                    </div>

                    <!-- Session status -->
                    @if (session('status'))
                        <div style="margin-bottom:16px;margin-top:16px;padding:12px 14px;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;display:flex;align-items:center;gap:8px;font-size:13px;color:#15803D;font-weight:500;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="login-form" style="margin-top:24px;">
                        @csrf

                        <!-- Email -->
                        <div class="anim d3" style="margin-bottom:16px;">
                            <label for="email" class="form-label" id="lbl-email">Email Address</label>
                            <div class="input-wrap">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="you@company.com"
                                    required autofocus autocomplete="username"
                                    class="form-input"
                                    onfocus="document.getElementById('lbl-email').style.color='#FF5A36'; this.parentElement.querySelector('.input-icon').style.color='#FF5A36';"
                                    onblur="document.getElementById('lbl-email').style.color=''; this.parentElement.querySelector('.input-icon').style.color='';"
                                >
                                <span class="input-icon">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                            </div>
                            @error('email')
                                <p style="font-size:12px;color:#EF4444;margin-top:5px;margin-left:2px;font-weight:500;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="anim d4" style="margin-bottom:12px;">
                            <label for="password" class="form-label" id="lbl-password">Password</label>
                            <div class="input-wrap">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="••••••••••"
                                    required autocomplete="current-password"
                                    class="form-input"
                                    style="padding-right: 44px;"
                                    onfocus="document.getElementById('lbl-password').style.color='#FF5A36'; this.parentElement.querySelector('.input-icon').style.color='#FF5A36';"
                                    onblur="document.getElementById('lbl-password').style.color=''; this.parentElement.querySelector('.input-icon').style.color='';"
                                >
                                <span class="input-icon">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <button type="button" onclick="togglePassword()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94A3B8;background:none;border:none;cursor:pointer;padding:0;line-height:1;transition:color 0.2s;" onmouseover="this.style.color='#FF5A36'" onmouseout="this.style.color='#94A3B8'">
                                    <svg id="eye-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p style="font-size:12px;color:#EF4444;margin-top:5px;margin-left:2px;font-weight:500;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember + Forgot -->
                        <div class="anim d5" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                            <label style="display:flex;align-items:center;gap:7px;cursor:pointer;">
                                <input type="checkbox" name="remember" class="custom-check">
                                <span style="font-size:13px;color:#64748B;font-weight:500;">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size:13px;font-weight:700;color:#FF5A36;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#C73B1C'" onmouseout="this.style.color='#FF5A36'">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <div class="anim d6">
                            <button type="submit" class="btn-signin" id="btn-submit">
                                <span style="position:relative;z-index:1;display:flex;align-items:center;justify-content:center;gap:8px;">
                                    <span>Sign In</span>
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="anim d7" style="text-align:center;">
                    <p style="font-size:13px;color:#94A3B8;">
                        Don't have an account?
                        <a href="{{ route('register') }}" style="font-weight:700;color:#FF5A36;text-decoration:none;margin-left:4px;" onmouseover="this.style.color='#C73B1C'" onmouseout="this.style.color='#FF5A36'">Create Account</a>
                    </p>
                </div>
            </div>

            <!-- ══════════ RIGHT: Visual Panel (Full Height) ══════════ -->
            <div class="visual-panel">
                <!-- Full Height Logistics Animation -->
                <div style="width:100%;height:100%;flex:1;display:flex;position:relative;">
                    @include('components.login-animation')
                </div>

            </div>

        </div>
    </div>

    <script>
        // ── Password toggle ──
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><circle cx="12" cy="12" r="3"/>`;
            } else {
                pwd.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>`;
            }
        }

        // ── Loading state on submit ──
        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit');
            btn.innerHTML = `<span style="position:relative;z-index:1;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="16" height="16" class="animate-spin" style="animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Signing in...</span>
            </span>`;
            btn.style.opacity = '0.85';
            btn.style.pointerEvents = 'none';
        });

        // ── Subtle light particles ──
        (function() {
            const canvas = document.getElementById('particles');
            const ctx = canvas.getContext('2d');
            function resize() { canvas.width = innerWidth; canvas.height = innerHeight; }
            resize();
            window.addEventListener('resize', resize);

            const pts = Array.from({length: 40}, () => ({
                x: Math.random() * innerWidth,
                y: Math.random() * innerHeight,
                r: Math.random() * 2.5 + 0.5,
                dx: (Math.random() - 0.5) * 0.3,
                dy: -(Math.random() * 0.4 + 0.15),
                // brand color or soft indigo
                color: Math.random() > 0.5 ? '255,90,54' : '99,102,241',
                opacity: Math.random() * 0.25 + 0.05,
                life: Math.random() * Math.PI * 2
            }));

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                pts.forEach(p => {
                    p.life += 0.007;
                    const a = p.opacity * (0.5 + 0.5 * Math.sin(p.life));
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${p.color},${a})`;
                    ctx.fill();
                    p.x += p.dx;
                    p.y += p.dy;
                    if (p.y < -10) p.y = canvas.height + 10;
                    if (p.x < -10) p.x = canvas.width + 10;
                    if (p.x > canvas.width + 10) p.x = -10;
                });
                requestAnimationFrame(draw);
            }
            draw();

            // Spinner keyframe
            const style = document.createElement('style');
            style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
            document.head.appendChild(style);
        })();
    </script>
</body>
</html>
