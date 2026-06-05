<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - {{ config('app.name') }}</title>
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
        .form-container{width:100%;max-width:400px}
        .form-header{margin-bottom:2rem}
        .form-header h2{font-size:1.625rem;font-weight:700;color:#0f172a;letter-spacing:-.03em;margin-bottom:.375rem}
        .form-header p{color:#64748b;font-size:.9rem}
        .role-switch{display:flex;background:#f1f5f9;border-radius:10px;padding:3px;margin-bottom:1.75rem;gap:3px}
        .role-btn{flex:1;padding:.6rem 1rem;border:none;border-radius:8px;font-size:.85rem;font-weight:500;cursor:pointer;transition:all .25s;background:transparent;color:#64748b;font-family:'Inter',sans-serif;position:relative}
        .role-btn.active{background:#fff;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,.08);font-weight:600}
        .role-btn:not(.active):hover{color:#334155}
        .input-group{position:relative;margin-bottom:1rem}
        .input-group label{display:block;font-size:.8rem;font-weight:500;color:#334155;margin-bottom:.375rem;letter-spacing:.01em}
        .input-group .input-wrap{position:relative}
        .input-group .input-wrap .icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:1rem;pointer-events:none;line-height:1}
        .input-group input{width:100%;padding:.65rem .85rem .65rem 2.4rem;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.875rem;font-family:'Inter',sans-serif;background:#fff;color:#0f172a;outline:none;transition:all .2s}
        .input-group input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12)}
        .input-group input::placeholder{color:#94a3b8}
        .input-group .toggle-pw{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.9rem;padding:4px}
        .forgot-row{display:flex;justify-content:flex-end;margin-bottom:1.25rem}
        .forgot-row a{font-size:.8rem;color:#6366f1;text-decoration:none;font-weight:500;transition:color .2s}
        .forgot-row a:hover{color:#4f46e5}
        .btn-login{width:100%;padding:.75rem;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:600;cursor:pointer;transition:all .25s;font-family:'Inter',sans-serif;position:relative;overflow:hidden}
        .btn-login:hover{transform:translateY(-1px);box-shadow:0 4px 16px rgba(99,102,241,.35)}
        .btn-login:active{transform:translateY(0)}
        .btn-login.loading{pointer-events:none}
        .btn-login .spinner{display:none;width:18px;height:18px;border:2.5px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;position:absolute;left:50%;top:50%;margin:-9px 0 0 -9px}
        .btn-login.loading .spinner{display:block}
        .btn-login.loading .btn-text{opacity:0}
        @keyframes spin{to{transform:rotate(360deg)}}
        .checkbox-row{display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem}
        .checkbox-row input[type=checkbox]{width:16px;height:16px;accent-color:#6366f1;cursor:pointer;margin:0}
        .checkbox-row label{font-size:.82rem;color:#475569;cursor:pointer;user-select:none}
        .divider{display:flex;align-items:center;gap:1rem;margin:1.5rem 0;color:#94a3b8;font-size:.8rem}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e2e8f0}
        .register-link{text-align:center;font-size:.875rem;color:#64748b}
        .register-link a{color:#6366f1;text-decoration:none;font-weight:600;transition:color .2s}
        .register-link a:hover{color:#4f46e5}
        .alert{padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.85rem;display:flex;align-items:center;gap:.5rem;line-height:1.4}
        .alert-error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .alert-error::before{content:'⚠️';font-size:.9rem}
        .creds-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;font-size:.8rem;color:#64748b;line-height:1.6;margin-top:1.25rem}
        .creds-box strong{color:#0f172a}
        .creds-box code{background:#e2e8f0;padding:1px 6px;border-radius:4px;font-size:.75rem;font-family:'Inter',monospace}
    </style>
</head>
<body>
    <div class="split">
        <div class="brand-side">
            <div class="brand-content">
                <div class="brand-icon">🔬</div>
                <h1>LabTool Management</h1>
                <p>Sistem manajemen peminjaman alat dan barang laboratorium yang terintegrasi untuk kemudahan administrasi.</p>
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
                    <h2>Selamat Datang</h2>
                    <p>Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <div class="role-switch" id="roleSwitch">
                    <button class="role-btn active" data-role="admin" onclick="switchRole('admin')">👤 Admin</button>
                    <button class="role-btn" data-role="mahasiswa" onclick="switchRole('mahasiswa')">🎓 Mahasiswa</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.85rem">✅ {{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" onsubmit="document.querySelector('.btn-login').classList.add('loading')">
                    @csrf
                    <input type="hidden" name="role_hint" id="roleHint" value="admin">

                    <div class="input-group">
                        <label for="email">Email atau NIM</label>
                        <div class="input-wrap">
                            <span class="icon">📧</span>
                            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@lab.com atau NIM">
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <span class="icon">🔒</span>
                            <input id="password" type="password" name="password" required placeholder="Masukkan password">
                            <button type="button" class="toggle-pw" onclick="togglePassword()" id="toggleBtn">👁</button>
                        </div>
                    </div>

                    <div class="checkbox-row">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>

                    <div class="forgot-row">
                        <a href="#">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <span class="spinner"></span>
                        <span class="btn-text">Masuk</span>
                    </button>
                </form>

                <div class="divider">atau</div>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                </div>

                <div class="creds-box" id="credsBox">
                    <strong>🔑 Test Credentials</strong><br>
                    <span id="credsText">Admin: <code>admin@test.com</code> / <code>password</code></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchRole(role) {
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            document.querySelector(`.role-btn[data-role="${role}"]`).classList.add('active');
            document.getElementById('roleHint').value = role;

            const creds = document.getElementById('credsText');
            if (role === 'admin') {
                creds.innerHTML = 'Admin: <code>admin@test.com</code> / <code>password</code>';
            } else {
                creds.innerHTML = 'Mahasiswa: <code>mhs@test.com</code> / <code>password</code>';
            }
        }

        function togglePassword() {
            const pw = document.getElementById('password');
            const btn = document.getElementById('toggleBtn');
            if (pw.type === 'password') {
                pw.type = 'text';
                btn.textContent = '🙈';
            } else {
                pw.type = 'password';
                btn.textContent = '👁';
            }
        }
    </script>
</body>
</html>
