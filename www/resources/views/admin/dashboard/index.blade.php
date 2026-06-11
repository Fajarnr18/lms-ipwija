@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan Sistem Laboratorium')

@section('header-actions')
<a href="{{ route('admin.peminjaman.index', ['tab' => 'menunggu']) }}" class="btn btn-sm">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Peminjaman Baru
</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:24px">
    <div class="stat-card" style="border-left:4px solid #3B82F6;position:relative">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E">{{ $totalTools }}</div>
                <div class="stat-label" style="color:#6B7280">Total Alat</div>
            </div>
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#3B82F6,#2563EB);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #8B5CF6;position:relative">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E">{{ $activeBorrowings }}</div>
                <div class="stat-label" style="color:#6B7280">Peminjaman Aktif</div>
                <div class="stat-sub" style="color:#9CA3AF;margin-top:2px">{{ $pendingBorrowings }} menunggu</div>
            </div>
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#8B5CF6,#7C3AED);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #EF4444;position:relative">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E">{{ $lowStockTools->count() + $lowStockItems->count() }}</div>
                <div class="stat-label" style="color:#6B7280">Stok Rendah</div>
                <div class="stat-sub" style="color:#9CA3AF;margin-top:2px">{{ $lowStockTools->count() }} alat, {{ $lowStockItems->count() }} barang</div>
            </div>
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#EF4444,#DC2626);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #10B981;position:relative">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E">{{ $totalMahasiswa }}</div>
                <div class="stat-label" style="color:#6B7280">Total Mahasiswa</div>
            </div>
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#10B981,#059669);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #F59E0B;position:relative">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E">{{ $totalDosen }}</div>
                <div class="stat-label" style="color:#6B7280">Total Dosen</div>
            </div>
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#F59E0B,#D97706);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Grafik Peminjam per Bulan
            </h3>
        </div>
        <div style="display:flex;align-items:end;gap:8px;height:140px;padding:0 8px">
            @foreach($chartLabels as $i => $label)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;height:100%;justify-content:end">
                <div style="font-size:10px;font-weight:600;color:#1E3A5F;margin-bottom:4px">{{ $chartData[$i] }}</div>
                <div style="width:100%;max-width:40px;background:#3B82F6;border-radius:4px 4px 0 0;height:{{ max($chartData[$i] * 12, 4) }}px;min-height:4px;transition:height .3s"></div>
                <div style="font-size:9px;color:#6B7280;margin-top:6px;white-space:nowrap">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Stok Rendah
            </h3>
            <a href="{{ route('admin.alat.index') }}" style="font-size:12px;font-weight:500;color:#3B82F6;text-decoration:none">Lihat Semua &rarr;</a>
        </div>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockTools as $tool)
                    <tr>
                        <td style="font-weight:500;color:#1A1A2E">{{ $tool->nama_alat }}</td>
                        <td>{{ $tool->kategori }}</td>
                        <td><span class="badge badge-red">{{ $tool->stok_tersedia }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3"><div class="empty-state">Semua stok aman.</div></td>
                    </tr>
                    @endforelse
                    @forelse($lowStockItems as $item)
                    <tr>
                        <td style="font-weight:500;color:#1A1A2E">{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td><span class="badge badge-red">{{ $item->stok }}</span></td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
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
            <a href="{{ route('admin.audit-trail.index') }}" style="font-size:12px;font-weight:500;color:#3B82F6;text-decoration:none">Lihat Semua &rarr;</a>
        </div>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Pelaku</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                    <tr>
                        <td style="font-size:12px;color:#6B7280;white-space:nowrap">{{ $log->time_stamp?->diffForHumans() }}</td>
                        <td>
                            <span class="badge" style="background:{{ match($log->modul) { 'PEMINJAMAN' => '#EEF2FF', 'ALAT' => '#ECFDF5', 'INVENTARIS' => '#FFFBEB', 'USER' => '#F5F3FF', default => '#F3F4F6' } }};color:{{ match($log->modul) { 'PEMINJAMAN' => '#1E4FD8', 'ALAT' => '#059669', 'INVENTARIS' => '#D97706', 'USER' => '#7C3AED', default => '#6B7280' } }}">
                                {{ $log->modul }}
                            </span>
                        </td>
                        <td><span style="font-weight:500;color:#1A1A2E">{{ $log->aksi }}</span></td>
                        <td>{{ $log->dilakukan_oleh }}</td>
                        <td><code style="font-size:12px;background:#F3F4F6;padding:2px 8px;border-radius:4px;color:#6B7280">{{ $log->ip_address }}</code></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5"><div class="empty-state">Belum ada aktivitas.</div></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
