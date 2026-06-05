<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;background:#f8fafc}
        .split{display:flex;width:100%;min-height:100vh}
        .brand-side{display:none;width:50%;background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0f172a 100%);position:relative;overflow:hidden;align-items:center;justify-content:center;padding:3rem}
        @media(min-width:1024px){.brand-side{display:flex}}
        .brand-side::before{content:'';position:absolute;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.15),transparent 70%);top:-100px;right:-100px}
        .brand-side::after{content:'';position:absolute;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(168,85,247,.1),transparent 70%);bottom:-80px;left:-80px}
        .brand-content{position:relative;z-index:1;text-align:center;color:#fff;max-width:420px}
        .brand-icon{width:72px;height:72px;background:linear-gradient(135deg,#6366f1,#a855f7);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2rem;box-shadow:0 8px 32px rgba(99,102,241,.35)}
        .brand-content h1{font-size:2rem;font-weight:700;margin-bottom:.5rem;letter-spacing:-.02em}
        .brand-content p{color:rgba(255,255,255,.6);line-height:1.6;font-size:.95rem;margin-bottom:2rem}
        .feature-list{text-align:left;display:flex;flex-direction:column;gap:.75rem}
        .feature-item{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:rgba(255,255,255,.05);border-radius:10px;border:1px solid rgba(255,255,255,.08);font-size:.875rem;color:rgba(255,255,255,.8)}
        .feature-item span{width:28px;height:28px;border-radius:8px;background:rgba(99,102,241,.2);display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0}
        .form-side{width:100%;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
        @media(min-width:1024px){.form-side{width:50%}}
        .form-container{width:100%;max-width:420px}
        .form-header{margin-bottom:1.75rem}
        .form-header h2{font-size:1.625rem;font-weight:700;color:#0f172a;letter-spacing:-.03em;margin-bottom:.375rem}
        .form-header p{color:#64748b;font-size:.9rem}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
        .input-group{position:relative;margin-bottom:.875rem}
        .input-group.full{grid-column:1/-1}
        .input-group label{display:block;font-size:.8rem;font-weight:500;color:#334155;margin-bottom:.375rem;letter-spacing:.01em}
        .input-group .input-wrap{position:relative}
        .input-group .input-wrap .icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.95rem;pointer-events:none;line-height:1}
        .input-group input{width:100%;padding:.6rem .85rem .6rem 2.3rem;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.85rem;font-family:'Inter',sans-serif;background:#fff;color:#0f172a;outline:none;transition:all .2s}
        .input-group input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12)}
        .input-group input::placeholder{color:#94a3b8}
        .btn-register{width:100%;padding:.75rem;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:600;cursor:pointer;transition:all .25s;font-family:'Inter',sans-serif;margin-top:.25rem}
        .btn-register:hover{transform:translateY(-1px);box-shadow:0 4px 16px rgba(99,102,241,.35)}
        .btn-register:active{transform:translateY(0)}
        .login-link{text-align:center;margin-top:1.5rem;font-size:.875rem;color:#64748b}
        .login-link a{color:#6366f1;text-decoration:none;font-weight:600;transition:color .2s}
        .login-link a:hover{color:#4f46e5}
        .alert{padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.85rem;line-height:1.4}
        .alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .term-note{text-align:center;font-size:.75rem;color:#94a3b8;margin-top:1rem;line-height:1.5}
        .term-note a{color:#6366f1;text-decoration:none}
    </style>
</head>
<body>
    <div class="split">
        <div class="brand-side">
            <div class="brand-content">
                <div class="brand-icon">🔬</div>
                <h1>LabTool Management</h1>
                <p>Bergabunglah dengan platform manajemen laboratorium untuk kemudahan peminjaman alat dan barang.</p>
                <div class="feature-list">
                    <div class="feature-item"><span>📋</span> Ajukan peminjaman alat laboratorium dengan mudah</div>
                    <div class="feature-item"><span>✅</span> Kelola persetujuan dan pengembalian dalam satu平台</div>
                    <div class="feature-item"><span>📊</span> Pantau stok alat dan barang secara real-time</div>
                    <div class="feature-item"><span>📜</span> Audit trail otomatis untuk setiap transaksi</div>
                </div>
            </div>
        </div>

        <div class="form-side">
            <div class="form-container">
                <div class="form-header">
                    <h2>Buat Akun</h2>
                    <p>Daftar sebagai mahasiswa untuk memulai</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-group full">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <div class="input-wrap">
                            <span class="icon">👤</span>
                            <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="nim">NIM</label>
                            <div class="input-wrap">
                                <span class="icon">🆔</span>
                                <input id="nim" type="text" name="nim" value="{{ old('nim') }}" required placeholder="Nomor induk">
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="email">Email</label>
                            <div class="input-wrap">
                                <span class="icon">📧</span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Email aktif">
                            </div>
                        </div>
                    </div>

                    <div class="input-group full">
                        <label for="program_studi">Program Studi</label>
                        <div class="input-wrap">
                            <span class="icon">📚</span>
                            <input id="program_studi" type="text" name="program_studi" value="{{ old('program_studi') }}" required placeholder="Contoh: Teknik Informatika">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="password">Password</label>
                            <div class="input-wrap">
                                <span class="icon">🔒</span>
                                <input id="password" type="password" name="password" required placeholder="Min. 8 karakter">
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="konfirmasi_password">Konfirmasi</label>
                            <div class="input-wrap">
                                <span class="icon">🔐</span>
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
</body>
</html>
