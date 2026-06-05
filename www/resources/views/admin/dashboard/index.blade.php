@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<style>
    .d-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:1rem;margin-bottom:1.75rem}
    .d-stat{position:relative;background:#fff;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid #f1f5f9;overflow:hidden;transition:all .25s}
    .d-stat:hover{transform:translateY(-3px);border-color:#cbd5e1;box-shadow:0 8px 24px rgba(0,0,0,.06)}
    @media(prefers-color-scheme:dark){.d-stat{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}.d-stat:hover{box-shadow:0 8px 24px rgba(0,0,0,.3),0 0 0 1px rgba(99,102,241,.2)}}
    .d-stat .stat-accent{position:absolute;top:0;left:0;width:100%;height:3px}
    .d-stat .stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;margin-bottom:.75rem}
    .d-stat .stat-value{font-size:1.65rem;font-weight:700;color:#0f172a;letter-spacing:-.02em;line-height:1.2;margin-bottom:.15rem}
    @media(prefers-color-scheme:dark){.d-stat .stat-value{color:#ededec}}
    .d-stat .stat-label{font-size:.8rem;color:#64748b;font-weight:500}
    @media(prefers-color-scheme:dark){.d-stat .stat-label{color:#a1a09a}}
    .d-stat .stat-sub{font-size:.7rem;color:#94a3b8;margin-top:.3rem;display:flex;align-items:center;gap:.35rem}
    .d-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem}
    @media(max-width:768px){.d-grid-2{grid-template-columns:1fr}}
    .d-card{background:#fff;border-radius:12px;padding:1.5rem;border:1px solid #f1f5f9}
    @media(prefers-color-scheme:dark){.d-card{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}}
    .d-card .card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
    .d-card .card-header h3{font-size:.92rem;font-weight:600;color:#0f172a;margin:0;display:flex;align-items:center;gap:.5rem}
    @media(prefers-color-scheme:dark){.d-card .card-header h3{color:#ededec}}
    .d-card .card-header a{font-size:.75rem;color:#6366f1;text-decoration:none;font-weight:500;transition:color .15s}
    .d-card .card-header a:hover{color:#4f46e5}
    .quick-actions{display:grid;grid-template-columns:1fr 1fr;gap:.625rem}
    .quick-action{display:flex;align-items:center;gap:.75rem;padding:.85rem 1rem;border-radius:10px;border:1px solid #f1f5f9;text-decoration:none;transition:all .2s;background:#fff}
    .quick-action:hover{border-color:#cbd5e1;box-shadow:0 2px 8px rgba(0,0,0,.04);transform:translateY(-1px)}
    @media(prefers-color-scheme:dark){.quick-action{background:#1c1c1a;border-color:#3e3e3a}.quick-action:hover{background:#272726;border-color:#6366f1}}
    .quick-action .qa-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
    .quick-action .qa-text{font-size:.82rem;font-weight:500;color:#0f172a}
    @media(prefers-color-scheme:dark){.quick-action .qa-text{color:#ededec}}
    .quick-action .qa-sub{font-size:.68rem;color:#94a3b8;margin-top:1px}
    .log-item{display:flex;align-items:flex-start;gap:.75rem;padding:.65rem 0;border-bottom:1px solid #f1f5f9}
    .log-item:last-child{border-bottom:none}
    @media(prefers-color-scheme:dark){.log-item{border-color:#272726}}
    .log-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:5px}
    .log-content{flex:1;min-width:0}
    .log-content .log-aksi{font-size:.82rem;font-weight:500;color:#0f172a;display:flex;align-items:center;gap:.4rem;flex-wrap:wrap}
    @media(prefers-color-scheme:dark){.log-content .log-aksi{color:#ededec}}
    .log-content .log-meta{font-size:.7rem;color:#94a3b8;margin-top:3px}
    .log-content .log-meta span{margin-right:.6rem}
    .log-time{font-size:.7rem;color:#94a3b8;white-space:nowrap;flex-shrink:0;margin-top:5px}
    .mod-badge{display:inline-block;padding:1px 7px;border-radius:4px;font-size:.65rem;font-weight:600}
</style>

<div class="d-stats">
    <div class="d-stat">
        <div class="stat-accent" style="background:linear-gradient(90deg,#6366f1,#818cf8)"></div>
        <div class="stat-icon" style="background:#eef2ff;color:#4f46e5">🔧</div>
        <div class="stat-value">{{ $totalTools }}</div>
        <div class="stat-label">Total Alat Laboratorium</div>
        <div class="stat-sub">● {{ \App\Models\Tool::tersedia()->count() }} tersedia</div>
    </div>
    <div class="d-stat">
        <div class="stat-accent" style="background:linear-gradient(90deg,#f59e0b,#fbbf24)"></div>
        <div class="stat-icon" style="background:#fffbeb;color:#d97706">⏳</div>
        <div class="stat-value">{{ $pendingBorrowings }}</div>
        <div class="stat-label">Menunggu Persetujuan</div>
        <div class="stat-sub">● {{ $activeBorrowings }} peminjaman aktif</div>
    </div>
    <div class="d-stat">
        <div class="stat-accent" style="background:linear-gradient(90deg,#10b981,#34d399)"></div>
        <div class="stat-icon" style="background:#ecfdf5;color:#059669">📦</div>
        <div class="stat-value">{{ $totalItems }}</div>
        <div class="stat-label">Total Barang</div>
        <div class="stat-sub">● {{ $lowStockItems }} stok rendah</div>
    </div>
    <div class="d-stat">
        <div class="stat-accent" style="background:linear-gradient(90deg,#ec4899,#f472b6)"></div>
        <div class="stat-icon" style="background:#fdf2f8;color:#db2777">👥</div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Mahasiswa</div>
        <div class="stat-sub">● Terdaftar di sistem</div>
    </div>
</div>

<div class="d-grid-2">
    <div class="d-card">
        <div class="card-header">
            <h3>⚡ Aksi Cepat</h3>
        </div>
        <div class="quick-actions">
            <a href="{{ route('admin.borrowings.index', ['tab' => 'menunggu']) }}" class="quick-action">
                <div class="qa-icon" style="background:#eef2ff;color:#4f46e5">📋</div>
                <div>
                    <div class="qa-text">Peminjaman Baru</div>
                    <div class="qa-sub">{{ $pendingBorrowings }} perlu disetujui</div>
                </div>
            </a>
            <a href="{{ route('admin.tools.create') }}" class="quick-action">
                <div class="qa-icon" style="background:#ecfdf5;color:#059669">➕</div>
                <div>
                    <div class="qa-text">Tambah Alat</div>
                    <div class="qa-sub">Register alat baru</div>
                </div>
            </a>
            <a href="{{ route('admin.items.create') }}" class="quick-action">
                <div class="qa-icon" style="background:#fffbeb;color:#d97706">📦</div>
                <div>
                    <div class="qa-text">Tambah Barang</div>
                    <div class="qa-sub">Input barang baru</div>
                </div>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="quick-action">
                <div class="qa-icon" style="background:#fdf2f8;color:#db2777">📊</div>
                <div>
                    <div class="qa-text">Export Laporan</div>
                    <div class="qa-sub">CSV peminjaman & barang</div>
                </div>
            </a>
        </div>
    </div>

    <div class="d-card">
        <div class="card-header">
            <h3>📜 Aktivitas Terbaru</h3>
            <a href="{{ route('admin.audit.index') }}">Lihat semua →</a>
        </div>
        <div>
            @forelse($recentLogs as $log)
            <div class="log-item">
                <div class="log-dot" style="background:{{ match($log->modul) { 'Peminjaman' => '#6366f1', 'Alat' => '#10b981', 'Barang' => '#f59e0b', 'User' => '#ec4899', default => '#94a3b8' } }}"></div>
                <div class="log-content">
                    <div class="log-aksi">
                        @php
                            $badgeStyle = match($log->modul) {
                                'Peminjaman' => '#eef2ff;color:#4f46e5',
                                'Alat' => '#ecfdf5;color:#059669',
                                'Barang' => '#fffbeb;color:#d97706',
                                'User' => '#fdf2f8;color:#db2777',
                                default => '#f1f5f9;color:#475569',
                            };
                        @endphp
                        <span class="mod-badge" style="background:{{ $badgeStyle }}">{{ $log->modul }}</span>
                        {{ $log->aksi }}
                    </div>
                    <div class="log-meta">
                        <span>👤 {{ $log->dilakukan_oleh }}</span>
                        <span>📍 {{ $log->ip_address }}</span>
                    </div>
                </div>
                <div class="log-time">{{ $log->time_stamp?->diffForHumans() }}</div>
            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.85rem">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
