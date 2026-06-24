@extends('layouts.app')
@section('title', 'Manajemen Pengembalian')
@section('subtitle', 'Kelola verifikasi pengembalian alat dan pantau status inventaris terkini.')

@section('header-actions')
<form method="GET" action="{{ route('admin.peminjaman.aktif') }}" style="display:flex;gap:8px;align-items:center">
    <div class="search-box" style="position:relative;max-width:320px">
        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" placeholder="Cari ID Peminjaman..." value="{{ request('search') }}" style="width:100%;padding:8px 12px 8px 36px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none" onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.background='#F9FAFB'">
    </div>
    <button type="submit" class="btn btn-sm btn-outline" style="display:flex;align-items:center;gap:6px">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        Filter
    </button>
</form>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="stat-cards" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2FF">
            <svg width="20" height="20" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $totalHariIni }}</div>
        <div class="stat-label">Total Pengembalian Hari Ini</div>
        <div class="stat-sub">
            <span style="color:#059669;font-weight:600">+12%</span>
            <span>Dari kemarin</span>
        </div>
        <div style="margin-top:10px;height:4px;border-radius:4px;background:#DBEAFE">
            <div style="height:100%;border-radius:4px;background:#3B82F6;width:75%"></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF3C7">
            <svg width="20" height="20" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $menungguVerifikasi }}</div>
        <div class="stat-label">Menunggu Verifikasi</div>
        <div class="stat-sub" style="color:#6B7280">Transaksi aktif</div>
        <div style="margin-top:10px;height:4px;border-radius:4px;background:#FEF3C7">
            <div style="height:100%;border-radius:4px;background:#F59E0B;width:50%"></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF2F2">
            <svg width="20" height="20" fill="none" stroke="#EF4444" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <div class="stat-value" style="color:#DC2626">{{ $alatRusak }}</div>
        <div class="stat-label">Alat Rusak/Hilang</div>
        <div class="stat-sub" style="color:#DC2626;font-weight:600">!Perlu Tindakan</div>
        <div style="margin-top:10px;height:4px;border-radius:4px;background:#FECACA">
            <div style="height:100%;border-radius:4px;background:#EF4444;width:40%"></div>
        </div>
    </div>
</div>

<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <h3 style="font-size:15px;font-weight:600;color:#1A1A2E">Daftar Pengembalian</h3>
        <div style="display:flex;gap:8px">
            <a href="{{ route('admin.peminjaman.aktif', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn btn-sm btn-outline" style="display:flex;align-items:center;gap:6px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Ekspor CSV
            </a>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>ID Peminjaman</th>
                    <th>Nama Peminjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondisi Alat</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                @php
                $st = strtoupper(trim($b->status ?? ''));
                $kondisiItems = $b->borrowingItems->pluck('kondisi_saat_kembali')->filter();
                $kondisiLabel = $kondisiItems->isEmpty() ? '-' : ($kondisiItems->contains('Rusak Berat') || $kondisiItems->contains('Rusak Ringan') ? 'Rusak/Kabel Putus' : 'Baik/Lengkap');
                $kondisiClass = $kondisiLabel === '-' ? 'badge-gray' : ($kondisiLabel === 'Baik/Lengkap' ? 'badge-green' : 'badge-red');
                $badgeClass = match($st) {
                    'DIKEMBALIKAN' => 'badge-green',
                    'TERLAMBAT' => 'badge-red',
                    'DIPINJAM' => 'badge-purple',
                    'DISETUJUI' => 'badge-blue',
                    default => 'badge-gray',
                };
                $statusLabel = match($st) {
                    'DIKEMBALIKAN' => 'Dikembalikan',
                    'TERLAMBAT' => 'Terlambat',
                    'DIPINJAM' => 'Dipinjam',
                    'DISETUJUI' => 'Disetujui',
                    default => $b->status,
                };
                @endphp
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">{{ $b->id_borrowing }}</td>
                    <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td>{{ $b->tgl_pengembalian_aktual?->format('d/m/Y') ?: ($b->tgl_rencana_kembali?->format('d/m/Y') ?: '-') }}</td>
                    <td><span class="badge {{ $kondisiClass }}">{{ $kondisiLabel }}</span></td>
                    <td>{{ $b->prosesOleh?->nama_lengkap ?: '-' }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                    <td style="text-align:center;white-space:nowrap">
                        <div class="action-group" style="justify-content:center;flex-wrap:nowrap">
                            @if($st === 'DIPINJAM')
                            <a href="{{ route('admin.peminjaman.kembali-form', $b->id_borrowing) }}" class="btn btn-sm" style="background:#3B82F6">Catat Pengembalian</a>
                            @endif
                            <a href="{{ route('admin.peminjaman.show', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada data pengembalian.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:16px;border-top:1px solid #E5E7EB">
        <span style="font-size:12px;color:#6B7280">Menampilkan {{ $borrowings->firstItem() ?? 0 }} dari {{ $borrowings->total() }} entri</span>
        @if($borrowings->hasPages())
        <div class="pagination" style="margin-top:0">{{ $borrowings->appends(request()->query())->links() }}</div>
        @endif
    </div>
</div>
@endsection