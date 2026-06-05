<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;background:#fff;color:#0f172a}
        @media(prefers-color-scheme:dark){body{background:#0a0a0a;color:#ededec}}
        .sidebar{width:260px;background:#fff;border-right:1px solid #f1f5f9;padding:1.5rem 1rem;display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50;overflow-y:auto}
        @media(max-width:768px){.sidebar{display:none}}
        .sidebar .brand{display:flex;align-items:center;gap:.625rem;padding:.5rem .75rem;margin-bottom:1.75rem;color:#0f172a;font-size:1.05rem;font-weight:700;letter-spacing:-.01em}
        .sidebar .brand .brand-icon{width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;color:#fff}
        .sidebar .nav-section{margin-bottom:1.25rem}
        .sidebar .nav-section .nav-label{font-size:.68rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;padding:.25rem .75rem;margin-bottom:.25rem}
        .sidebar nav a{display:flex;align-items:center;gap:.75rem;padding:.55rem .75rem;border-radius:8px;font-size:.85rem;font-weight:500;color:#64748b;text-decoration:none;transition:all .15s;margin-bottom:1px;-webkit-tap-highlight-color:transparent;user-select:none}
        .sidebar nav a:hover{background:#f8fafc;color:#0f172a!important}
        .sidebar nav a:active,.sidebar nav a:focus{background:#eef2ff;color:#4f46e5!important;outline:none}
        .sidebar nav a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff!important;font-weight:500;box-shadow:0 2px 8px rgba(99,102,241,.3)}
        .sidebar nav a.active:active,.sidebar nav a.active:focus{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff!important;outline:none}
        .sidebar .footer{margin-top:auto;padding-top:1rem;border-top:1px solid #f1f5f9}
        .main{margin-left:260px;flex:1;padding:2rem;min-height:100vh}
        @media(max-width:768px){.main{margin-left:0;padding:1rem}}
        .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
        .topbar h1{font-size:1.35rem;font-weight:700;color:#0f172a;letter-spacing:-.02em;margin:0}
        @media(prefers-color-scheme:dark){.topbar h1{color:#ededec}}
        .topbar .user-info{display:flex;align-items:center;gap:.75rem;font-size:.85rem;color:#64748b}
        @media(prefers-color-scheme:dark){.topbar .user-info{color:#a1a09a}}
        .topbar .user-info .role{background:#eef2ff;color:#4f46e5;padding:.175rem .625rem;border-radius:6px;font-size:.72rem;font-weight:600}
        @media(prefers-color-scheme:dark){.topbar .user-info .role{background:rgba(99,102,241,.2);color:#818cf8}}
        .card{background:#fff;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;border:1px solid #f1f5f9}
        @media(prefers-color-scheme:dark){.card{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}}
        input,select,textarea{padding:.55rem .75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.85rem;font-family:'Inter',sans-serif;background:#fff;color:#0f172a;outline:none;box-sizing:border-box;transition:all .2s}
        input:focus,select:focus,textarea:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12)}
        @media(prefers-color-scheme:dark){input,select,textarea{background:#0a0a0a;border-color:#3e3e3a;color:#ededec}input:focus,select:focus,textarea:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.2)}}
        ::placeholder{color:#94a3b8}
        .btn{padding:.55rem 1.1rem;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:500;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;font-family:'Inter',sans-serif;transition:all .2s}
        .btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(99,102,241,.3)}
        .btn:active{transform:translateY(0)}
        .btn-sm{padding:.4rem .85rem;font-size:.78rem}
        .btn-danger{background:linear-gradient(135deg,#ef4444,#dc2626)}.btn-danger:hover{box-shadow:0 4px 12px rgba(239,68,68,.3)}
        .btn-success{background:linear-gradient(135deg,#10b981,#059669)}.btn-success:hover{box-shadow:0 4px 12px rgba(16,185,129,.3)}
        .btn-warning{background:linear-gradient(135deg,#f59e0b,#d97706)}.btn-warning:hover{box-shadow:0 4px 12px rgba(245,158,11,.3)}
        .btn-info{background:linear-gradient(135deg,#6366f1,#4f46e5)}.btn-info:hover{box-shadow:0 4px 12px rgba(99,102,241,.3)}
        .btn-outline{background:transparent;color:#64748b;border:1.5px solid #e2e8f0;box-shadow:none}.btn-outline:hover{background:#f8fafc;border-color:#cbd5e1;transform:none;box-shadow:none}
        @media(prefers-color-scheme:dark){.btn-outline{color:#a1a09a;border-color:#3e3e3a}.btn-outline:hover{background:#272726;border-color:#525252}}
        table{width:100%;border-collapse:collapse;font-size:.85rem}
        th{text-align:left;padding:.7rem .6rem;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;white-space:nowrap;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em}
        @media(prefers-color-scheme:dark){th{color:#a1a09a;border-color:#272726}}
        td{padding:.7rem .6rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        @media(prefers-color-scheme:dark){td{border-color:#272726}}
        .badge{display:inline-block;padding:.175rem .6rem;border-radius:6px;font-size:.72rem;font-weight:600}
        .badge-pending{background:#fef3c7;color:#92400e}
        .badge-approved{background:#eef2ff;color:#4f46e5}
        .badge-rejected{background:#fef2f2;color:#991b1b}
        .badge-borrowed{background:#f3e8ff;color:#6b21a8}
        .badge-returned{background:#d1fae5;color:#065f46}
        .badge-tersedia{background:#d1fae5;color:#065f46}
        .badge-dipinjam{background:#fce4ec;color:#991b1b}
        .badge-rusak{background:#fef3c7;color:#92400e}
        .badge-perbaikan{background:#eef2ff;color:#4f46e5}
        .tabs{display:flex;gap:0;margin-bottom:1.5rem;border-bottom:1px solid #e2e8f0}
        @media(prefers-color-scheme:dark){.tabs{border-color:#3e3e3a}}
        .tabs a{padding:.6rem 1.25rem;font-size:.85rem;font-weight:500;color:#64748b;text-decoration:none;border-bottom:2px solid transparent;margin-bottom:-1px;transition:all .15s}
        .tabs a:hover{color:#0f172a}
        .tabs a.active{color:#4f46e5;border-bottom-color:#6366f1}
        @media(prefers-color-scheme:dark){.tabs a{color:#a1a09a}.tabs a:hover,.tabs a.active{color:#ededec}.tabs a.active{border-bottom-color:#6366f1}}
        .toolbar{display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;align-items:end}
        .toolbar-item{display:flex;flex-direction:column;gap:.25rem}
        .toolbar-item label{font-size:.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.03em}
        .pagination{display:flex;justify-content:center;gap:.25rem;margin-top:1.5rem;align-items:center}
        .pagination a,.pagination span{padding:.4rem .75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.82rem;text-decoration:none;color:#64748b;transition:all .15s}
        .pagination a:hover{background:#f8fafc;border-color:#cbd5e1}
        .pagination .active{background:#6366f1;color:#fff;border-color:#6366f1}
        @media(prefers-color-scheme:dark){.pagination a,.pagination span{color:#a1a09a;border-color:#3e3e3a}.pagination a:hover{background:#272726}.pagination .active{background:#6366f1;color:#fff;border-color:#6366f1}}
        .action-group{display:flex;gap:.375rem;flex-wrap:wrap}
        .alert{padding:.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:.85rem;display:flex;align-items:center;justify-content:space-between;line-height:1.4}
        .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .alert-error,.alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .alert-info{background:#eef2ff;border:1px solid #c7d2fe;color:#4338ca}
        .alert-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .form-group{display:flex;flex-direction:column;gap:.35rem}
        .form-group.full{grid-column:1/-1}
        .form-group label{font-size:.82rem;font-weight:500;color:#475569}
        @media(prefers-color-scheme:dark){.form-group label{color:#ededec}}
        .form-actions{display:flex;gap:.5rem;margin-top:1.5rem;justify-content:end}
        textarea{resize:vertical;min-height:6rem}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        @media(max-width:768px){.grid-2,.form-grid{grid-template-columns:1fr}}
        .mobile-menu{display:none;position:fixed;top:0;left:0;right:0;background:#fff;border-bottom:1px solid #f1f5f9;padding:.85rem 1rem;z-index:100;align-items:center;justify-content:space-between;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        @media(max-width:768px){.mobile-menu{display:flex}}
        .mobile-menu .menu-toggle{background:none;border:none;color:#0f172a;cursor:pointer;font-size:1.25rem;padding:.25rem}
        .sidebar.show{display:flex}
        .overflow-x-auto{overflow-x:auto;-webkit-overflow-scrolling:touch}
        .search-section{background:#fff;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;border:1px solid #f1f5f9}
        @media(prefers-color-scheme:dark){.search-section{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:200;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
        .modal-overlay.show{display:flex}
        .modal{background:#fff;border-radius:12px;padding:1.5rem;max-width:32rem;width:90%;max-height:80vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.15)}
        @media(prefers-color-scheme:dark){.modal{background:#161615}}
        .modal h2{margin:0 0 .35rem;font-size:1.15rem;font-weight:600;color:#0f172a}
        @media(prefers-color-scheme:dark){.modal h2{color:#ededec}}
        .modal p{margin:0 0 1.25rem;font-size:.85rem;color:#64748b}
        @media(prefers-color-scheme:dark){.modal p{color:#a1a09a}}
        .modal-actions{display:flex;gap:.5rem;justify-content:end}
        .grid-3{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem}
        .card-tool{background:#fff;border-radius:12px;padding:1.25rem;transition:all .2s;border:1px solid #f1f5f9}
        .card-tool:hover{border-color:#cbd5e1;box-shadow:0 4px 12px rgba(0,0,0,.04);transform:translateY(-2px)}

        @media(prefers-color-scheme:dark){.card-tool{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}}
        .card-tool h3{margin:0 0 .375rem;font-size:1rem;font-weight:600}
        .card-tool .meta{font-size:.78rem;color:#64748b;margin-bottom:.75rem}
        @media(prefers-color-scheme:dark){.card-tool .meta{color:#a1a09a}}
        .qty-control{display:flex;align-items:center;gap:.375rem}
        .qty-control input{width:4rem;text-align:center}
        .text-right{text-align:right}
        .text-center{text-align:center}
        .mb-1{margin-bottom:.25rem}
        .mb-2{margin-bottom:.5rem}
        .mb-3{margin-bottom:.75rem}
        .mb-4{margin-bottom:1rem}
        .mt-2{margin-top:.5rem}
        .mt-4{margin-top:1rem}
        .fw-500{font-weight:500}
        .fw-600{font-weight:600}
        .gap-2{gap:.5rem}
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem}
        .stat-card{background:#fff;border-radius:12px;padding:1.25rem;border:1px solid #f1f5f9}
        @media(prefers-color-scheme:dark){.stat-card{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}}
        .stat-card .value{font-size:1.75rem;font-weight:700;margin:0 0 .25rem;color:#0f172a;letter-spacing:-.02em}
        @media(prefers-color-scheme:dark){.stat-card .value{color:#ededec}}
        .stat-card .label{font-size:.78rem;color:#64748b;margin:0;font-weight:500}
        @media(prefers-color-scheme:dark){.stat-card .label{color:#a1a09a}}
        .form-horizontal{display:flex;gap:1rem;flex-wrap:wrap;align-items:end}
        @media(max-width:768px){.stats{grid-template-columns:1fr 1fr}}
    </style>
</head>
<body>
    <div class="mobile-menu">
        <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('show')">☰</button>
        <span style="font-weight:600;font-size:.875rem">{{ config('app.name') }}</span>
        <span></span>
    </div>

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">🔬</div>
            {{ config('app.name') }}
        </div>
        <nav>
            @if(Auth::user()?->role === 'admin')
            <div class="nav-section">
                <div class="nav-label">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                <a href="{{ route('admin.tools.index') }}" class="{{ request()->routeIs('admin.tools.*') ? 'active' : '' }}">🔧 Manajemen Alat</a>
                <a href="{{ route('admin.borrowings.index') }}" class="{{ request()->routeIs('admin.borrowings.*') ? 'active' : '' }}">📋 Peminjaman</a>
                <a href="{{ route('admin.items.index') }}" class="{{ request()->routeIs('admin.items.*') ? 'active' : '' }}">📦 Manajemen Barang</a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Lainnya</div>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👥 Manajemen User</a>
                <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">📈 Laporan</a>
                <a href="{{ route('admin.audit.index') }}" class="{{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">📜 Audit Trail</a>
            </div>
            @else
            <div class="nav-section">
                <div class="nav-label">Menu Utama</div>
                <a href="{{ route('mhs.dashboard') }}" class="{{ request()->routeIs('mhs.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
                <a href="{{ route('mhs.catalog.index') }}" class="{{ request()->routeIs('mhs.catalog*') ? 'active' : '' }}">🔬 Katalog Alat</a>
                <a href="{{ route('mhs.cart') }}" class="{{ request()->routeIs('mhs.cart') ? 'active' : '' }}">🛒 Keranjang</a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Informasi</div>
                <a href="{{ route('mhs.borrowings.index') }}" class="{{ request()->routeIs('mhs.borrowings.*') ? 'active' : '' }}">📋 Peminjaman Saya</a>
                <a href="{{ route('mhs.profile') }}" class="{{ request()->routeIs('mhs.profile') ? 'active' : '' }}">👤 Profil</a>
            </div>
            @endif
        </nav>
        <div class="footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline" style="width:100%;justify-content:center">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="user-info">
                <span class="role">{{ ucfirst(Auth::user()?->role) }}</span>
                <span>{{ Auth::user()?->nama_lengkap }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('show');
        }
    });
    function confirmAction(msg) {
        return confirm(msg || 'Yakin?');
    }
    </script>
</body>
</html>
