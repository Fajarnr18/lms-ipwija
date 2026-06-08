<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; background: #F0F2F8; }
        .split { display: flex; width: 100%; min-height: 100vh; }
        .brand-side { display: none; width: 50%; background: #0D1F3C; position: relative; overflow: hidden; align-items: center; justify-content: center; padding: 48px; }
        @media(min-width:1024px){ .brand-side { display: flex; } }
        .brand-side::before { content: ''; position: absolute; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, rgba(30,79,216,.12), transparent 70%); top: -100px; right: -100px; }
        .brand-side::after { content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(30,79,216,.08), transparent 70%); bottom: -80px; left: -80px; }
        .brand-content { position: relative; z-index: 1; text-align: center; color: #fff; max-width: 420px; }
        .brand-icon { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .brand-icon img { width: 100%; height: 100%; object-fit: contain; }
        .brand-content h1 { font-size: 28px; font-weight: 800; margin-bottom: 4px; letter-spacing: -.02em; }
        .brand-content .tagline { color: rgba(255,255,255,.5); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 24px; }
        .brand-content p { color: rgba(255,255,255,.55); line-height: 1.6; font-size: 14px; margin-bottom: 0; }
        .brand-side .copyright { position: absolute; bottom: 24px; color: rgba(255,255,255,.2); font-size: 11px; }

        .form-side { width: 100%; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 32px; background: #F0F2F8; }
        @media(min-width:1024px){ .form-side { width: 50%; } }
        .form-container { width: 100%; max-width: 480px; }
        .mobile-brand { display: block; text-align: center; margin-bottom: 32px; }
        @media(min-width:1024px){ .mobile-brand { display: none; } }
        .mobile-brand .logo { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .mobile-brand .logo img { width: 100%; height: 100%; object-fit: contain; }

        .form-card { background: #fff; border-radius: 16px; padding: 32px; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .form-header { margin-bottom: 24px; }
        .form-header h2 { font-size: 22px; font-weight: 700; color: #1A1A2E; letter-spacing: -.03em; margin-bottom: 4px; }
        .form-header p { font-size: 13px; color: #6B7280; }

        .form-row { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media(min-width:640px){ .form-row { grid-template-columns: 1fr 1fr; } }

        .input-group { margin-bottom: 16px; }
        .input-group.full { grid-column: 1 / -1; }
        .input-group label { display: block; font-size: 12px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .input-group .input-wrap { position: relative; }
        .input-group .input-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #9CA3AF; }
        .input-group input, .input-group select { width: 100%; padding: 10px 12px 10px 40px; border: 1.5px solid #E5E7EB; border-radius: 10px; font-size: 13px; font-family: 'Inter', sans-serif; background: #fff; color: #1A1A2E; outline: none; transition: all .2s; }
        .input-group input:focus, .input-group select:focus { border-color: #1E4FD8; box-shadow: 0 0 0 3px rgba(30,79,216,.1); }
        .input-group input::placeholder { color: #9CA3AF; }
        .input-group select { cursor: pointer; }

        .btn-register { width: 100%; padding: 10px; background: #1E4FD8; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; font-family: 'Inter', sans-serif; }
        .btn-register:hover { background: #1A45C2; }

        .login-link { text-align: center; margin-top: 20px; font-size: 13px; color: #6B7280; }
        .login-link a { color: #1E4FD8; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }

        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; line-height: 1.4; }
        .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }
        .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }

        .term-note { text-align: center; font-size: 11px; color: #9CA3AF; margin-top: 12px; line-height: 1.5; }
        .term-note a { color: #1E4FD8; text-decoration: none; }

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
                <p>Bergabunglah dengan platform manajemen laboratorium untuk kemudahan peminjaman alat dan barang.</p>
            </div>
            <div class="copyright">&copy; {{ date('Y') }} LMS Universitas IPWIJA. All rights reserved.</div>
        </div>

        <div class="form-side">
            <div class="form-container">
                <div class="mobile-brand">
                    <div class="logo"><img src="/logo.png" alt="Logo"></div>
                </div>

                <div class="form-card">
                    <div class="form-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Daftar sebagai mahasiswa atau dosen untuk memulai</p>
                    </div>

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

                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="input-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <div class="input-wrap">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Masukkan nama lengkap" pattern="[A-Za-z\s]+" title="Hanya huruf yang diperbolehkan">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="nim">NIM / NUPTK</label>
                                <div class="input-wrap">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                    <input id="nim" type="text" name="nim" value="{{ old('nim') }}" required placeholder="12 digit NIM / 16 digit NUPTK" pattern="[0-9]{12,16}" title="Harus 12 atau 16 digit angka" maxlength="16" minlength="12">
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="email">Email</label>
                                <div class="input-wrap">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Email aktif">
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="program_studi">Program Studi</label>
                            <div class="input-wrap">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <select id="program_studi" name="program_studi" required>
                                    <option value="">Pilih Program Studi</option>
                                    <option value="Teknik Informatika" @selected(old('program_studi') == 'Teknik Informatika')>Teknik Informatika</option>
                                    <option value="Sistem Informasi" @selected(old('program_studi') == 'Sistem Informasi')>Sistem Informasi</option>
                                    <option value="Manajemen" @selected(old('program_studi') == 'Manajemen')>Manajemen</option>
                                    <option value="Akuntansi" @selected(old('program_studi') == 'Akuntansi')>Akuntansi</option>
                                    <option value="Teknik Elektro" @selected(old('program_studi') == 'Teknik Elektro')>Teknik Elektro</option>
                                    <option value="Teknik Sipil" @selected(old('program_studi') == 'Teknik Sipil')>Teknik Sipil</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="password">Password</label>
                                <div class="input-wrap">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <input id="password" type="password" name="password" required placeholder="Min. 8 karakter">
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="konfirmasi_password">Konfirmasi Password</label>
                                <div class="input-wrap">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <input id="konfirmasi_password" type="password" name="konfirmasi_password" required placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-register">Daftar Sekarang</button>
                    </form>

                    <div class="login-link">
                        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                    </div>

                    <div class="term-note">
                        Dengan mendaftar, Anda menyetujui <a href="#">Ketentuan Layanan</a> dan <a href="#">Kebijakan Privasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
