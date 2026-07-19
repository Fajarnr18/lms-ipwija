@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('subtitle', 'Seluruh riwayat peminjaman alat')

@section('content')
<form method="GET" action="{{ route('dosen.peminjaman.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Filter Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach(['MENUNGGU', 'DISETUJUI', 'DITOLAK', 'DIPINJAM', 'TERLAMBAT', 'DIKEMBALIKAN'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(strtolower($s)) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tgl Pengajuan</th>
                    <th>Rencana Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Tgl Dikembalikan</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">#{{ $b->id_borrowing }}</td>
                    <td>{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_pengembalian_aktual?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @php
                        $st = strtoupper(trim($b->status ?? ''));
                        $badgeClass = match($st) {
                            'MENUNGGU' => 'badge-yellow',
                            'DISETUJUI' => 'badge-blue',
                            'DITOLAK' => 'badge-red',
                            'DIPINJAM' => 'badge-purple',
                                    'TERLAMBAT' => 'badge-danger',
                            'DIKEMBALIKAN' => 'badge-green',
                            default => 'badge-gray',
                        };
                        $statusLabel = match($st) {
                            'MENUNGGU' => 'Menunggu',
                            'DISETUJUI' => 'Disetujui',
                            'DITOLAK' => 'Ditolak',
                            'DIPINJAM' => 'Dipinjam',
                                    'TERLAMBAT' => 'Terlambat',
                            'DIKEMBALIKAN' => 'Dikembalikan',
                            default => $b->status,
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('dosen.peminjaman.detail', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Belum ada riwayat peminjaman.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
    {{ $borrowings->appends(request()->query())->links() }}
    @endif
</div>
@endsection


