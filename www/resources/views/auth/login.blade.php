<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; background: #F0F2F8; }
        .split { display: flex; width: 100%; min-height: 100vh; }
        .brand-side { display: none; width: 50%; background: #0D1F3C; position: relative; overflow: hidden; align-items: flex-start; justify-content: flex-start; padding: 48px; padding-top: 60px; }
        @media(min-width:1024px){ .brand-side { display: flex; } }
        .brand-side::before { content: ''; position: absolute; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, rgba(30,79,216,.12), transparent 70%); top: -100px; right: -100px; }
        .brand-side::after { content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(30,79,216,.08), transparent 70%); bottom: -80px; left: -80px; }
        .brand-content { position: relative; z-index: 1; text-align: center; color: #fff; max-width: 420px; margin: 0 auto; }
        .brand-icon { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .brand-icon img { width: 100%; height: 100%; object-fit: contain; }
        .brand-content h1 { font-size: 28px; font-weight: 800; margin-bottom: 4px; letter-spacing: -.02em; }
        .brand-content .tagline { color: rgba(255,255,255,.5); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 24px; }
        .brand-content p { color: rgba(255,255,255,.55); line-height: 1.6; font-size: 14px; margin-bottom: 0; }
        .brand-side .copyright { position: absolute; bottom: 24px; left: 0; right: 0; text-align: center; color: rgba(255,255,255,.2); font-size: 11px; }

        .form-side { width: 100%; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 32px; background: #F0F2F8; }
        @media(min-width:1024px){ .form-side { width: 50%; } }
        .form-container { width: 100%; max-width: 400px; }
        .mobile-brand { display: block; text-align: center; margin-bottom: 32px; }
        @media(min-width:1024px){ .mobile-brand { display: none; } }
        .mobile-brand .logo { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .mobile-brand .logo img { width: 100%; height: 100%; object-fit: contain; }
        .mobile-brand h1 { font-size: 18px; font-weight: 700; color: #1A1A2E; }
        .mobile-brand .tagline { font-size: 11px; color: #6B7280; }

        .form-card { background: #fff; border-radius: 16px; padding: 32px; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .form-header { margin-bottom: 24px; }
        .form-header h2 { font-size: 22px; font-weight: 700; color: #1A1A2E; letter-spacing: -.03em; margin-bottom: 4px; }
        .form-header p { font-size: 13px; color: #6B7280; }

        .input-group { margin-bottom: 16px; }
        .input-group label { display: block; font-size: 12px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .input-group .input-wrap { position: relative; border: 1.5px solid #E5E7EB; border-radius: 10px; background: #fff; }
        .input-group .input-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: #9CA3AF; }
        .input-group input { width: 100%; padding: 10px 12px 10px 40px; border: none; border-radius: 0; font-size: 13px; font-family: 'Inter', sans-serif; background: transparent; color: #1A1A2E; outline: none; transition: all .2s; }
        .input-group .input-wrap:focus-within { border-color: #1E4FD8; box-shadow: 0 0 0 3px rgba(30,79,216,.1); }
        .input-group input:focus { outline: none; }
        .input-group input::placeholder { color: #9CA3AF; }
        .input-group .toggle-pw { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9CA3AF; cursor: pointer; padding: 2px; display: flex; align-items: center; justify-content: center; }
        .input-group .input-wrap .pw-input { padding-right: 48px; }

        .checkbox-row { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
        .checkbox-row input[type=checkbox] { width: 16px; height: 16px; accent-color: #1E4FD8; cursor: pointer; margin: 0; }
        .checkbox-row label { font-size: 13px; color: #4B5563; cursor: pointer; }

        .forgot-row { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .forgot-row a { font-size: 12px; color: #1E4FD8; text-decoration: none; font-weight: 500; }
        .forgot-row a:hover { text-decoration: underline; }

        .btn-login { width: 100%; padding: 10px; background: #1E4FD8; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-login:hover { background: #1A45C2; }
        .btn-login.loading { pointer-events: none; opacity: .7; }
        .btn-login .spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
        .btn-login.loading .spinner { display: block; }
        .btn-login .btn-icon { flex-shrink: 0; }
        .btn-login.loading .btn-icon { display: none; }
        .btn-login.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .divider { display: flex; align-items: center; gap: 16px; margin: 24px 0; color: #9CA3AF; font-size: 12px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #E5E7EB; }

        .register-link { text-align: center; font-size: 13px; color: #6B7280; }
        .register-link a { color: #1E4FD8; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }

        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; line-height: 1.4; }
        .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }
        .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }

        .creds-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 12px 16px; font-size: 12px; color: #6B7280; line-height: 1.6; margin-top: 20px; }
        .creds-box strong { color: #1A1A2E; }
        .creds-box code { background: #E5E7EB; padding: 1px 6px; border-radius: 4px; font-size: 11px; font-family: 'Inter', monospace; }

        .error-text { font-size: 12px; color: #EF4444; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="split">
        <div class="brand-side">
            <div class="brand-content">
                <div class="brand-icon"><img src="/logo.png" alt="Logo"></div>
                <h1>LMS Universitas IPWIJA</h1>
                <div class="tagline">Laboratorium Berbasis Digital</div>
                <p>Optimalkan riset dan praktikum Anda dengan sistem manajemen laboratorium terintegrasi.</p>
            </div>
            <div class="copyright">&copy; {{ date('Y') }} LMS Universitas IPWIJA. All rights reserved.</div>
        </div>

        <div class="form-side">
            <div class="form-container">
                <div class="mobile-brand">
                    <div class="logo"><img src="/logo.png" alt="Logo"></div>
                    <h1>LMS Universitas IPWIJA</h1>
                    <div class="tagline">Laboratorium Berbasis Digital</div>
                </div>

                <div class="form-card">
                    <div class="form-header">
                        <h2>Selamat Datang</h2>
                        <p>Masuk ke akun Anda untuk melanjutkan</p>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">
                        <span>&#10003;</span> {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-error">
                        <span>&#9888;</span>
                        <div>
                            @foreach ($errors->all() as $error)
                            {{ $error }}@if(!$loop->last)<br>@endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" onsubmit="document.querySelector('.btn-login').classList.add('loading')">
                        @csrf

                        <div class="input-group">
                            <label for="email">Email / NUPTK / NIM</label>
                            <div class="input-wrap">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@test.com / 16 digit NUPTK / 12 digit NIM">
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="password">Kata Sandi</label>
                            <div class="input-wrap">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <input id="password" type="password" name="password" required placeholder="Masukkan kata sandi" class="pw-input">
                                <button type="button" class="toggle-pw" onclick="togglePassword()" id="toggleBtn">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                            <label class="checkbox-row" style="margin-bottom:0">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember" style="font-size:13px;color:#4B5563;cursor:pointer">Ingat saya</label>
                            </label>
                            <div class="forgot-row" style="margin-bottom:0">
                                <a href="#">Lupa password?</a>
                            </div>
                        </div>

                        <button type="submit" class="btn-login"><svg class="btn-icon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span class="spinner"></span>
                            <span class="btn-text">Masuk</span>
                        </button>
                    </form>

                    <div class="divider">atau</div>

                    <div class="register-link">
                        Belum memiliki akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    </div>

                    <div class="creds-box">
                        <strong>Test Credentials</strong><br>
                        Admin: <code>admin@test.com</code> / <code>password</code><br>
                        Dosen: <code>1234567890123456</code> / <code>password</code><br>
                        Mahasiswa: <code>123456789012</code> / <code>password</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

    function togglePassword() {
        var pw = document.getElementById('password');
        var btn = document.getElementById('toggleBtn');
        if (pw.type === 'password') {
            pw.type = 'text';
            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-2.887m2.184-2.158A9.96 9.96 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.066 10.066 0 01-2.162 3.253"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/></svg>';
        } else {
            pw.type = 'password';
            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
        }
    }
    </script>
</body>
</html>
