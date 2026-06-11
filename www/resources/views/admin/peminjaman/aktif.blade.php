@extends('layouts.app')
@section('title', 'Peminjaman Aktif')
@section('subtitle', 'Daftar peminjaman yang sedang berlangsung')

@section('content')
<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pemohon</th>
                    <th>NIM</th>
                    <th>Tanggal Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">{{ $loop->iteration + ($borrowings->currentPage() - 1) * $borrowings->perPage() }}</td>
                    <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td>{{ $b->mahasiswa?->nim }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $badgeClass = match($b->status) {
                            'DIPINJAM' => 'badge-purple',
                            'DISETUJUI' => 'badge-blue',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $b->status }}</span>
                    </td>
                    <td style="text-align:center">
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('admin.peminjaman.show', $b->id_borrowing) }}" class="btn btn-outline btn-sm">Detail</a>
                            <form method="POST" action="{{ route('admin.peminjaman.kembali', $b->id_borrowing) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:#8B5CF6">Catat Kembali</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada peminjaman aktif.</div></td>
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
