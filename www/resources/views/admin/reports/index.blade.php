@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="page-header" style="margin-bottom:24px">
    <div>
        <h1>Laporan</h1>
        <p>Export data laboratorium</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:24px">
    <div class="card" style="display:flex;flex-direction:column;gap:12px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#1E4FD8;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;color:#1A1A2E">Alat Sering Dipinjam</div>
                <div style="font-size:11px;color:#6B7280;margin-top:2px">Top alat berdasarkan frekuensi peminjaman</div>
            </div>
        </div>
        <a href="{{ route('admin.reports.export', ['type' => 'most_borrowed']) }}" class="btn btn-sm" style="align-self:flex-start;margin-top:auto">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download CSV
        </a>
    </div>

    <div class="card" style="display:flex;flex-direction:column;gap:12px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;border-radius:10px;background:#ECFDF5;display:flex;align-items:center;justify-content:center;color:#059669;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;color:#1A1A2E">Alat Sedang Dipinjam</div>
                <div style="font-size:11px;color:#6B7280;margin-top:2px">Alat yang sedang dalam masa peminjaman</div>
            </div>
        </div>
        <a href="{{ route('admin.reports.export', ['type' => 'currently_borrowed']) }}" class="btn btn-sm" style="align-self:flex-start;margin-top:auto">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download CSV
        </a>
    </div>

    <div class="card" style="display:flex;flex-direction:column;gap:12px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;border-radius:10px;background:#FFFBEB;display:flex;align-items:center;justify-content:center;color:#D97706;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;color:#1A1A2E">Log Mutasi Stok</div>
                <div style="font-size:11px;color:#6B7280;margin-top:2px">Riwayat perubahan stok barang</div>
            </div>
        </div>
        <a href="{{ route('admin.reports.export', ['type' => 'mutations']) }}" class="btn btn-sm" style="align-self:flex-start;margin-top:auto">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download CSV
        </a>
    </div>

    <div class="card" style="display:flex;flex-direction:column;gap:12px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:38px;height:38px;border-radius:10px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;color:#7C3AED;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;color:#1A1A2E">Inventaris Barang</div>
                <div style="font-size:11px;color:#6B7280;margin-top:2px">Data seluruh inventaris barang lab</div>
            </div>
        </div>
        <a href="{{ route('admin.reports.export', ['type' => 'items']) }}" class="btn btn-sm" style="align-self:flex-start;margin-top:auto">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download CSV
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
    <div class="card" style="margin:0">
        <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;margin-bottom:12px">Export Peminjaman</h3>
        <form method="GET" action="{{ route('admin.reports.export') }}">
            <input type="hidden" name="type" value="borrowings">
            <div class="form-group">
                <label>Dari Tanggal</label>
                <input type="date" name="from" required>
            </div>
            <div class="form-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="to" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Ditolak">Ditolak</option>
                    <option value="Dipinjam">Dipinjam</option>
                    <option value="Dikembalikan">Dikembalikan</option>
                </select>
            </div>
            <div class="form-actions" style="margin-top:12px">
                <button class="btn btn-sm">Export CSV</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin:0">
        <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;margin-bottom:12px">Export Barang & Mutasi</h3>
        <form method="GET" action="{{ route('admin.reports.export') }}">
            <input type="hidden" name="type" value="items">
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">Semua</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Alat Tulis">Alat Tulis</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="form-actions" style="margin-top:12px">
                <button class="btn btn-sm">Export CSV</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;margin-bottom:16px;display:flex;align-items:center;gap:8px">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Peminjaman Terbaru
    </h3>
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>ID</th><th>Peminjam</th><th>Tgl Pinjam</th><th>Tgl Kembali (Rencana)</th><th>Status</th><th>Jumlah Alat</th></tr></thead>
            <tbody>
                @forelse($recentBorrowings as $b)
                <tr>
                    <td>{{ $b->id_borrowing }}</td>
                    <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $badgeClass = match($b->status) {
                                'Menunggu' => 'badge-yellow',
                                'Disetujui' => 'badge-blue',
                                'Ditolak' => 'badge-red',
                                'Dipinjam' => 'badge-green',
                                'Dikembalikan' => 'badge-gray',
                                default => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $b->status }}</span>
                    </td>
                    <td>{{ $b->borrowingItems->count() }} alat</td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-state">Belum ada peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
