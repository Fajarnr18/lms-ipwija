@extends('layouts.app')
@section('title', 'Detail Alat')
@section('subtitle', $tool->nama_alat)

@section('header-actions')
<a href="{{ route('admin.alat.index') }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
<a href="{{ route('admin.alat.edit', $tool->id_alat) }}" class="btn btn-sm btn-info" style="margin-left:8px">Edit Alat</a>
@endsection

@section('content')
<div class="detail-layout">
    <div class="detail-left">
        <form method="GET" action="{{ route('admin.alat.show', $tool->id_alat) }}">
            <div class="card" style="padding:12px 16px">
                <div class="search-box" style="display:flex;gap:8px;align-items:center">
                    <svg width="16" height="16" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjaman..." style="border:none;outline:none;flex:1;font-size:13px;background:transparent">
                    @if(request('search'))
                    <a href="{{ route('admin.alat.show', $tool->id_alat) }}" style="color:#9CA3AF;font-size:12px;text-decoration:none">&times;</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="card">
            @if($tool->foto_alat)
            <div class="detail-foto">
                <img src="{{ asset('storage/' . $tool->foto_alat) }}" alt="Foto {{ $tool->nama_alat }}">
            </div>
            @else
            <div class="detail-foto" style="background:#F9FAFB;display:flex;align-items:center;justify-content:center;color:#D1D5DB;font-size:48px">
                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @endif
        </div>

        <div class="card">
            <div class="detail-nama">{{ $tool->nama_alat }}</div>
            <div class="detail-kode">ID: #{{ $tool->id_alat }} / {{ $tool->kode_alat }}</div>

            <hr class="detail-divider">

            <div class="detail-grid-2col">
                <div>
                    <div class="label">Kategori</div>
                    <div class="value">{{ $tool->kategori }}</div>
                </div>
                <div>
                    <div class="label">Kondisi</div>
                    <div class="value">{{ $tool->kondisi_fisik ?? '-' }}</div>
                </div>
            </div>

            <div class="detail-grid-2col">
                <div>
                    <div class="label">Stok</div>
                    <div class="value">{{ $tool->stok_tersedia }}/{{ $tool->stok_total }}</div>
                </div>
                <div>
                    <div class="label">Lokasi</div>
                    <div class="value">{{ $tool->lokasi ?? '-' }}</div>
                </div>
            </div>

            <div style="margin-top:12px">
                <div class="label">Deskripsi</div>
                <div class="value" style="margin-top:2px">{{ $tool->deskripsi ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="detail-right">
        <div class="card">
            <div class="riwayat-header">
                <div class="riwayat-title">Riwayat Peminjaman</div>
                <a href="{{ route('admin.peminjaman.index', ['search' => $tool->kode_alat]) }}" class="riwayat-link">Lihat Semua</a>
            </div>
            <div style="overflow-x:auto">
                <table class="riwayat-table">
                    <thead>
                        <tr>
                            <th>Tgl Pinjam</th>
                            <th>Nama Peminjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $b)
                        <tr>
                            <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                            <td>{{ $b->mahasiswa?->nama_lengkap ?? '-' }}</td>
                            <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                            <td>
                                @php
                                $st = strtoupper(trim($b->status ?? ''));
                                $badgeClass = match($st) {
                                    'MENUNGGU' => 'badge-yellow',
                                    'DISETUJUI' => 'badge-blue',
                                    'DITOLAK' => 'badge-red',
                                    'DIPINJAM' => 'badge-purple',
                                    'DIKEMBALIKAN' => 'badge-green',
                                    default => 'badge-gray',
                                };
                                $statusLabel = match($st) {
                                    'MENUNGGU' => 'Menunggu',
                                    'DISETUJUI' => 'Disetujui',
                                    'DITOLAK' => 'Ditolak',
                                    'DIPINJAM' => 'Dipinjam',
                                    'DIKEMBALIKAN' => 'Dikembalikan',
                                    default => $b->status,
                                };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"><div class="empty-state" style="padding:24px">Belum ada riwayat peminjaman.</div></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($borrowings->hasPages())
            <div class="pagination" style="margin-top:12px">{{ $borrowings->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>

<div class="detail-actions">
    <a href="{{ route('admin.alat.index') }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
    <a href="{{ route('admin.alat.edit', $tool->id_alat) }}" class="btn btn-sm btn-info">Edit Alat</a>
</div>

<style>
.detail-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: start;
}
@media (max-width: 900px) {
    .detail-layout { grid-template-columns: 1fr; }
}

.detail-foto {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 10px;
    overflow: hidden;
    background: #F9FAFB;
}
.detail-foto img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.detail-nama {
    font-size: 20px;
    font-weight: 700;
    color: #1A1A2E;
    margin-bottom: 2px;
}
.detail-kode {
    font-size: 12px;
    color: #6B7280;
    margin-bottom: 12px;
}

.detail-divider {
    border: none;
    border-top: 1px solid #E5E7EB;
    margin: 12px 0;
}

.detail-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 8px;
}
.detail-grid-2col .label {
    font-size: 11px;
    color: #6B7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 1px;
}
.detail-grid-2col .value {
    font-size: 14px;
    color: #1A1A2E;
    font-weight: 500;
}

.riwayat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.riwayat-title {
    font-size: 14px;
    font-weight: 600;
    color: #1A1A2E;
}
.riwayat-link {
    font-size: 12px;
    color: #3B82F6;
    text-decoration: none;
    font-weight: 500;
}
.riwayat-link:hover {
    text-decoration: underline;
}

.riwayat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.riwayat-table th {
    text-align: left;
    padding: 8px 6px;
    color: #6B7280;
    font-weight: 500;
    border-bottom: 1px solid #E5E7EB;
    white-space: nowrap;
}
.riwayat-table td {
    padding: 8px 6px;
    border-bottom: 1px solid #F3F4F6;
    color: #374151;
}
.riwayat-table tr:last-child td {
    border-bottom: none;
}

.detail-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: 20px;
    gap: 12px;
}
.btn-info {
    background: #3B82F6;
    color: #fff;
    border: none;
}
.btn-info:hover {
    background: #2563EB;
}
</style>
@endsection
