@php
$role = Auth::user()?->role;
$user = Auth::user();
$isAdmin = $role === 'admin';
$isMahasiswa = $role === 'mahasiswa';
$isDosen = $role === 'dosen';
$roleLabel = match($role) { 'admin' => 'Admin', 'mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', default => ucfirst($role) };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $roleLabel) - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #1A1A2E; min-height: 100vh; }
        .sidebar { width: 250px; background: #0D1F3C; position: fixed; top: 0; left: 0; height: 100vh; z-index: 40; display: flex; flex-direction: column; transition: transform .3s; overflow-y: auto; }
        @media(max-width:1023px){ .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } .overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 35; display: none; } .overlay.show { display: block; } }
        .sidebar .brand { display: flex; align-items: center; gap: 12px; padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,.06); }
        .sidebar .brand .logo { width: 36px; height: 36px; flex-shrink: 0; }
        .sidebar .brand .logo img { width: 100%; height: 100%; object-fit: contain; }
        .sidebar .brand .brand-text { color: #fff; font-weight: 700; font-size: 14px; line-height: 1.3; }
        .sidebar .brand .brand-sub { color: rgba(255,255,255,.4); font-size: 10px; text-transform: uppercase; letter-spacing: .05em; }
        .sidebar nav { flex: 1; padding: 12px 12px; }
        .sidebar .nav-label { color: rgba(255,255,255,.3); font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; padding: 16px 12px 6px; }
        .sidebar nav a { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; font-size: 13px; font-weight: 500; color: rgba(255,255,255,.55); text-decoration: none; transition: all .15s; margin-bottom: 1px; }
        .sidebar nav a:hover { background: rgba(255,255,255,.06); color: #fff; }
        .sidebar nav a.active { background: rgba(59,130,246,.2); color: #fff; }
        .sidebar nav a svg { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar .footer { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.06); }
        .sidebar .footer form { margin: 0; }
        .sidebar .footer button { display: flex; align-items: center; gap: 12px; padding: 12px 16px; width: 100%; border: 1px solid rgba(239,68,68,.3); border-radius: 10px; font-size: 14px; font-weight: 600; color: #fca5a5; background: rgba(239,68,68,.08); cursor: pointer; font-family: 'Inter', sans-serif; transition: all .2s; letter-spacing: .01em; }
        .sidebar .footer button:hover { background: rgba(239,68,68,.2); color: #fff; border-color: rgba(239,68,68,.6); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,.15); }
        .sidebar .footer button svg { width: 20px; height: 20px; transition: transform .2s; }
        .sidebar .footer button:hover svg { transform: translateX(2px); }
        .main { margin-left: 250px; min-height: 100vh; display: flex; flex-direction: column; }
        @media(max-width:1023px){ .main { margin-left: 0; } }
        .topbar { position: sticky; top: 0; z-index: 30; background: #fff; border-bottom: 1px solid #E5E7EB; height: 64px; display: flex; align-items: center; padding: 0 24px; }
        .topbar .menu-btn { display: none; padding: 8px; border: none; background: transparent; color: #6B7280; cursor: pointer; border-radius: 8px; margin-right: 8px; }
        .topbar .menu-btn:hover { background: #F3F4F6; }
        @media(max-width:1023px){ .topbar .menu-btn { display: flex; align-items: center; justify-content: center; } }
        .topbar .search-box { position: relative; flex: 1; max-width: 360px; }
        .topbar .search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9CA3AF; }
        .topbar .search-box input { width: 100%; padding: 8px 12px 8px 36px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; background: #F9FAFB; outline: none; transition: all .2s; }
        .topbar .search-box input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); background: #fff; }
        .topbar .user-area { display: flex; align-items: center; gap: 16px; margin-left: auto; }
        .topbar .user-info { display: flex; align-items: center; gap: 10px; padding-left: 16px; border-left: 1px solid #E5E7EB; }
        .topbar .user-info .avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #1E3A5F, #3B82F6); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
        .topbar .user-info .detail { text-align: right; }
        .topbar .user-info .name { font-size: 13px; font-weight: 600; color: #1A1A2E; line-height: 1.2; }
        .topbar .user-info .role { font-size: 11px; color: #6B7280; }
        .content { flex: 1; padding: 24px; max-width: 1280px; width: 100%; margin: 0 auto; }
        .page-header { display: flex; flex-direction: column; gap: 4px; margin-bottom: 24px; }
        @media(min-width:640px){ .page-header { flex-direction: row; align-items: center; justify-content: space-between; } }
        .page-header h1 { font-size: 22px; font-weight: 700; color: #1A1A2E; letter-spacing: -.02em; }
        .page-header p { font-size: 13px; color: #6B7280; }
        .page-header-actions { display: flex; gap: 8px; margin-top: 8px; }
        @media(min-width:640px){ .page-header-actions { margin-top: 0; } }
        .card { background: #fff; border-radius: 8px; border: 1px solid #E5E7EB; padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .stat-card { background: #fff; border-radius: 8px; border: 1px solid #E5E7EB; padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .stat-card .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .stat-card .stat-value { font-size: 24px; font-weight: 700; color: #1A1A2E; letter-spacing: -.02em; line-height: 1.2; }
        .stat-card .stat-label { font-size: 12px; color: #6B7280; font-weight: 500; margin-top: 2px; }
        .stat-card .stat-sub { font-size: 11px; color: #9CA3AF; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 10px 12px; font-weight: 600; color: #6B7280; border-bottom: 1px solid #E5E7EB; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; cursor: pointer; user-select: none; }
        th:hover { color: #374151; }
        td { padding: 10px 12px; border-bottom: 1px solid #F3F4F6; vertical-align: middle; }
        tr:nth-child(even) td { background: #F9FAFB; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #1E3A5F; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; font-family: 'Inter', sans-serif; transition: all .15s; }
        .btn:hover { background: #162D4D; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-outline { background: transparent; border: 1.5px solid #E5E7EB; color: #6B7280; }
        .btn-outline:hover { background: #F9FAFB; border-color: #D1D5DB; color: #1A1A2E; }
        .btn-danger { background: #EF4444; }
        .btn-danger:hover { background: #DC2626; }
        .btn-success { background: #10B981; }
        .btn-success:hover { background: #059669; }
        .btn-warning { background: #F59E0B; }
        .btn-warning:hover { background: #D97706; }
        .btn-info { background: #3B82F6; }
        .btn-info:hover { background: #2563EB; }
        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .badge-yellow { background: #FFFBEB; color: #92400E; }
        .badge-blue { background: #EEF2FF; color: #1E4FD8; }
        .badge-red { background: #FEF2F2; color: #991B1B; }
        .badge-purple { background: #F5F3FF; color: #6D28D9; }
        .badge-green { background: #ECFDF5; color: #065F46; }
        .badge-gray { background: #F3F4F6; color: #6B7280; }
        .tabs { display: flex; gap: 0; border-bottom: 1px solid #E5E7EB; margin-bottom: 20px; overflow-x: auto; }
        .tabs a { padding: 10px 20px; font-size: 13px; font-weight: 500; color: #6B7280; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -1px; transition: all .15s; white-space: nowrap; }
        .tabs a:hover { color: #1A1A2E; }
        .tabs a.active { color: #1E3A5F; border-bottom-color: #1E3A5F; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1.5px solid #E5E7EB; border-radius: 6px; font-size: 13px; font-family: 'Inter', sans-serif; background: #fff; outline: none; transition: all .2s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media(min-width:640px){ .form-grid { grid-template-columns: 1fr 1fr; } }
        .form-grid .full { grid-column: 1 / -1; }
        .form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 24px; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 12px; align-items: end; margin-bottom: 16px; }
        .toolbar-item { display: flex; flex-direction: column; gap: 4px; }
        .toolbar-item label { font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: .04em; }
        .toolbar-item input, .toolbar-item select { padding: 8px 12px; border: 1.5px solid #E5E7EB; border-radius: 6px; font-size: 13px; font-family: 'Inter', sans-serif; background: #fff; outline: none; }
        .toolbar-item input:focus, .toolbar-item select:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }
        .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }
        .alert-info { background: #EEF2FF; border: 1px solid #C7D2FE; color: #1E4FD8; }
        .pagination { display: flex; justify-content: center; gap: 4px; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 6px 12px; border: 1.5px solid #E5E7EB; border-radius: 6px; font-size: 12px; text-decoration: none; color: #6B7280; transition: all .15s; }
        .pagination a:hover { background: #F9FAFB; border-color: #D1D5DB; }
        .pagination .active { background: #1E3A5F; color: #fff; border-color: #1E3A5F; }
        .pagination .disabled { opacity: .4; pointer-events: none; }
        .action-group { display: flex; gap: 4px; flex-wrap: wrap; }
        .empty-state { text-align: center; padding: 40px 20px; color: #9CA3AF; font-size: 13px; }
        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 20px 0; }
        .detail-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media(min-width:768px){ .detail-grid { grid-template-columns: 1fr 1fr; } }
        .detail-item .label { font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 4px; }
        .detail-item .value { font-size: 14px; font-weight: 500; color: #1A1A2E; }
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:50; display:none; align-items:center; justify-content:center; padding:20px; }
        .modal-overlay.show { display:flex; }
        .modal { background:#fff; border-radius:12px; padding:24px; width:100%; max-width:480px; max-height:90vh; overflow-y:auto; }
        .modal h2 { font-size:16px; font-weight:600; margin:0 0 16px; }
        .error-text { font-size: 12px; color: #EF4444; margin-top: 4px; }
        .info-banner { display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 8px; font-size: 13px; color: #1E4FD8; margin-bottom: 16px; }
        @media(max-width:640px){ .content { padding: 16px; } .page-header h1 { font-size: 18px; } }
    </style>
</head>
<body>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="logo"><img src="/logo.png" alt="Logo"></div>
            <div>
                <div class="brand-text">{{ config('app.name') }}</div>
                <div class="brand-sub">Laboratorium Digital</div>
            </div>
        </div>
        <nav>
            @if($isAdmin)
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.alat.index') }}" class="{{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Manajemen Alat
            </a>
            <a href="{{ route('admin.inventaris.index') }}" class="{{ request()->routeIs('admin.inventaris.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Inventaris Barang
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Peminjaman
            </a>
            <div class="nav-label">Lainnya</div>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                Manajemen User
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Laporan
            </a>
            <a href="{{ route('admin.audit-trail.index') }}" class="{{ request()->routeIs('admin.audit-trail.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Audit Trail
            </a>
            @elseif($isDosen)
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('dosen.dashboard') }}" class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('dosen.katalog.index') }}" class="{{ request()->routeIs('dosen.katalog*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Katalog Alat
            </a>
            <a href="{{ route('dosen.keranjang.index') }}" class="{{ request()->routeIs('dosen.keranjang*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                Keranjang
            </a>
            <div class="nav-label">Informasi</div>
            <a href="{{ route('dosen.peminjaman.index') }}" class="{{ request()->routeIs('dosen.peminjaman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Peminjaman Saya
            </a>
            <a href="{{ route('dosen.profil.index') }}" class="{{ request()->routeIs('dosen.profil.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil
            </a>
            @else
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('mhs.dashboard') }}" class="{{ request()->routeIs('mhs.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('katalog.index') }}" class="{{ request()->routeIs('katalog*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Katalog Alat
            </a>
            <a href="{{ route('keranjang.index') }}" class="{{ request()->routeIs('keranjang*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                Keranjang
            </a>
            <div class="nav-label">Informasi</div>
            <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Peminjaman
            </a>
            <a href="{{ route('peminjaman.riwayat') }}" class="{{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat
            </a>
            <a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil
            </a>
            @endif
        </nav>
        <div class="footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>
    <div class="main">
        <header class="topbar">
            <button class="menu-btn" onclick="toggleSidebar()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="search-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari sesuatu...">
            </div>
            <div class="user-area">
                <div class="user-info">
                    <div class="detail">
                        <div class="name">{{ $user?->nama_lengkap }}</div>
                        <div class="role">{{ $roleLabel }}</div>
                    </div>
                    <div class="avatar">{{ strtoupper(substr($user?->nama_lengkap ?? 'U', 0, 1)) }}</div>
                </div>
            </div>
        </header>
        <main class="content">
            <div class="page-header">
                <div>
                    <h1>@yield('title')</h1>
                    <p>@yield('subtitle', '')</p>
                </div>
                <div class="page-header-actions">
                    @yield('header-actions', '')
                </div>
            </div>
            @if(session('success'))
            <div class="alert alert-success" id="successAlert">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="flex:1">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:16px">&times;</button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="flex:1">{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:16px">&times;</button>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
        if (e.target.id === 'overlay') toggleSidebar();
    });
    function confirmAction(msg) {
        return confirm(msg || 'Apakah Anda yakin?');
    }
    document.addEventListener('DOMContentLoaded', function() {
        var alert = document.getElementById('successAlert');
        if (alert) {
            setTimeout(function() {
                alert.style.transition = 'all .3s';
                alert.style.opacity = '0';
                setTimeout(function() { alert.remove(); }, 300);
            }, 5000);
        }
    });
    </script>
</body>
</html>
