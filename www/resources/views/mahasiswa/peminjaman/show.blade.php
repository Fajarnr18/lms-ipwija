@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('subtitle', 'Peminjaman #' . $borowing->id_borrowing)

@section('header-actions')
<a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:16px">
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Status</div>
            <div class="value">
                @php
                $badgeClass = match($borowing->status) {
                    'MENUNGGU' => 'badge-yellow',
                    'DISETUJUI' => 'badge-blue',
                    'DITOLAK' => 'badge-red',
                    'DIPINJAM' => 'badge-purple',
                    'DIKEMBALIKAN' => 'badge-green',
                    default => 'badge-gray',
                };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $borowing->status }}</span>
            </div>
        </div>
        <div class="detail-item">
            <div class="label">Tgl Pengajuan</div>
            <div class="value">{{ $borowing->tgl_pengajuan?->format('d/m/Y H:i') }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Rencana Pinjam</div>
            <div class="value">{{ $borowing->tgl_rencana_pinjam?->format('d/m/Y') }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Rencana Kembali</div>
            <div class="value">{{ $borowing->tgl_rencana_kembali?->format('d/m/Y') }}</div>
        </div>
        @if($borowing->tgl_pengembalian_aktual)
        <div class="detail-item">
            <div class="label">Tgl Dikembalikan</div>
            <div class="value">{{ $borowing->tgl_pengembalian_aktual?->format('d/m/Y H:i') }}</div>
        </div>
        @endif
        <div class="detail-item" style="grid-column:1/-1">
            <div class="label">Keperluan</div>
            <div class="value">{{ $borowing->keperluan }}</div>
        </div>
        @if($borowing->prosesOleh)
        <div class="detail-item">
            <div class="label">Diproses Oleh</div>
            <div class="value">{{ $borowing->prosesOleh?->nama_lengkap }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Tgl Diproses</div>
            <div class="value">{{ $borowing->tgl_diproses?->format('d/m/Y H:i') }}</div>
        </div>
        @endif
        @if($borowing->catatan_admin)
        <div class="detail-item" style="grid-column:1/-1">
            <div class="label">Catatan Admin</div>
            <div class="value" style="color:#991B1B">{{ $borowing->catatan_admin }}</div>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <h3 style="font-size:14px;font-weight:600;margin:0 0 16px">Alat yang Dipinjam</h3>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Kondisi Kembali</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borowing->borrowingItems as $item)
                <tr>
                    <td>{{ $item->tool?->kode_alat }}</td>
                    <td>{{ $item->tool?->nama_alat }}</td>
                    <td>{{ $item->jumlah_unit }}</td>
                    <td>{{ $item->kondisi_saat_kembali ?? '-' }}</td>
                    <td>{{ $item->catatan_pengembalian ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5"><div class="empty-state">Tidak ada alat.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($borowing->status === 'DITOLAK' && $borowing->catatan_admin)
<div class="card" style="border-color:#FECACA">
    <div style="display:flex;align-items:flex-start;gap:12px">
        <div style="width:36px;height:36px;border-radius:10px;background:#FEF2F2;display:flex;align-items:center;justify-content:center;color:#EF4444;flex-shrink:0">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="font-size:13px;font-weight:600;color:#991B1B;margin-bottom:4px">Alasan Penolakan</div>
            <div style="font-size:13px;color:#6B7280">{{ $borowing->catatan_admin }}</div>
        </div>
    </div>
</div>
@endif
@endsection
