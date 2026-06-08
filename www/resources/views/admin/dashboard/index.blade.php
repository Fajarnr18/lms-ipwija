@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan Laboratorium')

@section('header-actions')
<a href="{{ route('admin.borrowings.index', ['tab' => 'menunggu']) }}" class="btn btn-sm">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Peminjaman Baru
</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2FF;color:#1E4FD8">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="stat-value">{{ $totalTools }}</div>
        <div class="stat-label">Total Alat</div>
        <div class="stat-sub">&#x25CF; {{ $totalTools > 0 ? round($totalTools * 0.1) : 0 }}% bulan ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2FF;color:#1E4FD8">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="stat-value">{{ $activeBorrowings }}</div>
        <div class="stat-label">Peminjaman Aktif</div>
        <div class="stat-sub">&#x25CF; {{ $pendingBorrowings }} menunggu</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF2F2;color:#EF4444">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $lowStockItems }}</div>
        <div class="stat-label">Stok Rendah</div>
        <div class="stat-sub">&#x25CF; Perlu penambahan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F5F3FF;color:#7C3AED">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Mahasiswa</div>
        <div class="stat-sub">&#x25CF; Terdaftar di sistem</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFFBEB;color:#D97706">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="stat-value">{{ $totalItems }}</div>
        <div class="stat-label">Total Barang</div>
        <div class="stat-sub">&#x25CF; Inventaris</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Grafik Peminjaman per Bulan
            </h3>
            <select style="padding:6px 12px;border:1px solid #E5E7EB;border-radius:6px;font-size:12px;font-family:'Inter',sans-serif;background:#fff;outline:none">
                <option>Tahun {{ date('Y') - 1 }}</option>
                <option selected>Tahun {{ date('Y') }}</option>
            </select>
        </div>
        <div style="height:280px;display:flex;align-items:center;justify-content:center;background:#FAFAFA;border-radius:10px;border:1px dashed #E5E7EB">
            <p style="font-size:13px;color:#9CA3AF;display:flex;align-items:center;gap:8px">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Grafik akan ditampilkan di sini
            </p>
        </div>
    </div>

    <div class="card" style="border-color:#FECACA">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
            <span style="width:8px;height:8px;border-radius:50%;background:#EF4444"></span>
            <h3 style="font-size:14px;font-weight:600;color:#1A1A2E">Stok Rendah</h3>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px">
            @php
                $lowItems = \App\Models\Item::where('stok', '<=', 5)->orderBy('stok')->take(5)->get();
            @endphp
            @forelse($lowItems as $item)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:#FEF2F2;border-radius:8px;border:1px solid #FECACA">
                <div>
                    <div style="font-size:13px;font-weight:600;color:#1A1A2E">{{ $item->nama_barang }}</div>
                    <div style="font-size:11px;color:#EF4444;font-weight:500;margin-top:2px">Sisa: {{ $item->stok }} {{ $item->satuan }}</div>
                </div>
                <a href="{{ route('admin.items.mutation', $item->id_barang) }}" style="padding:6px 12px;font-size:11px;font-weight:500;background:#EEF2FF;color:#1E4FD8;border-radius:6px;text-decoration:none">Tambah</a>
            </div>
            @empty
            <p style="font-size:13px;color:#9CA3AF;text-align:center;padding:16px">Semua stok aman</p>
            @endforelse
            <a href="{{ route('admin.items.index') }}" style="text-align:center;font-size:12px;font-weight:500;color:#1E4FD8;text-decoration:none;padding-top:8px">Lihat Semua Inventaris &rarr;</a>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:20px;margin-bottom:24px">
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Aktivitas Terbaru
            </h3>
            <a href="{{ route('admin.audit.index') }}" style="font-size:12px;font-weight:500;color:#1E4FD8;text-decoration:none">Lihat Semua &rarr;</a>
        </div>
        <div style="display:flex;flex-direction:column">
            @forelse($recentLogs as $log)
            <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid #F3F4F6">
                <div style="width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0;background:{{ match($log->modul) { 'Peminjaman' => '#1E4FD8', 'Alat' => '#10B981', 'Barang' => '#F59E0B', 'User' => '#8B5CF6', default => '#9CA3AF' } }}"></div>
                <div style="flex:1;min-width:0">
                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                        <span style="padding:1px 8px;border-radius:4px;font-size:10px;font-weight:600;background:{{ match($log->modul) { 'Peminjaman' => '#EEF2FF', 'Alat' => '#ECFDF5', 'Barang' => '#FFFBEB', 'User' => '#F5F3FF', default => '#F3F4F6' } }};color:{{ match($log->modul) { 'Peminjaman' => '#1E4FD8', 'Alat' => '#059669', 'Barang' => '#D97706', 'User' => '#7C3AED', default => '#6B7280' } }}">{{ $log->modul }}</span>
                        <span style="font-size:13px;font-weight:500;color:#1A1A2E">{{ $log->aksi }}</span>
                    </div>
                    <div style="font-size:11px;color:#9CA3AF;margin-top:3px">
                        <span>&#x1F464; {{ $log->dilakukan_oleh }}</span>
                        <span style="margin:0 6px">&middot;</span>
                        <span>&#x1F4CD; {{ $log->ip_address }}</span>
                    </div>
                </div>
                <span style="font-size:11px;color:#9CA3AF;white-space:nowrap">{{ $log->time_stamp?->diffForHumans() }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:24px;font-size:13px;color:#9CA3AF">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
