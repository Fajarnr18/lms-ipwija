@extends('layouts.app')
@section('title', 'Peminjaman Saya')
@section('subtitle', 'Riwayat peminjaman alat laboratorium')

@section('content')
<div style="display:flex;flex-wrap:wrap;gap:12px;align-items:end;margin-bottom:16px">
    <form method="GET" action="{{ route('dosen.peminjaman.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:end">
        <div class="toolbar-item">
            <label>Filter Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach(['MENUNGGU', 'DISETUJUI', 'DITOLAK', 'DIPINJAM', 'DIKEMBALIKAN'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(strtolower($s)) }}</option>
                @endforeach
            </select>
        </div>
    </form>
    <a href="{{ route('dosen.katalog.index') }}" class="btn btn-sm" style="margin-left:auto">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Pinjam Alat Baru
    </a>
</div>

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tgl Pengajuan</th>
                    <th>Rencana Pinjam</th>
                    <th>Rencana Kembali</th>
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
                    <td>
                        @php
                        $badgeClass = match($b->status) {
                            'MENUNGGU' => 'badge-yellow',
                            'DISETUJUI' => 'badge-blue',
                            'DITOLAK' => 'badge-red',
                            'DIPINJAM' => 'badge-purple',
                            'DIKEMBALIKAN' => 'badge-green',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $b->status }}</span>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('dosen.peminjaman.detail', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"><div class="empty-state">Belum ada peminjaman.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
    <div class="pagination">{{ $borrowings->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
