@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
<div class="tabs">
    <a href="{{ route('admin.borrowings.index', ['tab' => 'semua']) }}" class="{{ $tab === 'semua' ? 'active' : '' }}">Semua</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'menunggu']) }}" class="{{ $tab === 'menunggu' ? 'active' : '' }}">Menunggu</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'aktif']) }}" class="{{ $tab === 'aktif' ? 'active' : '' }}">Aktif</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'selesai']) }}" class="{{ $tab === 'selesai' ? 'active' : '' }}">Selesai</a>
</div>

<form method="GET" action="{{ route('admin.borrowings.index') }}">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama peminjam..." value="{{ request('search') }}" style="min-width:200px">
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div class="card">
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr><th>ID</th><th>Peminjam</th><th>NIM</th><th>Tgl Pengajuan</th><th>Rencana Pinjam</th><th>Rencana Kembali</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td>{{ $b->id_borrowing }}</td>
                    <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td>{{ $b->mahasiswa?->nim }}</td>
                    <td>{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge @php
                            echo match($b->status) {
                                'Menunggu' => 'badge-pending',
                                'Disetujui' => 'badge-approved',
                                'Ditolak' => 'badge-rejected',
                                'Dipinjam' => 'badge-borrowed',
                                'Dikembalikan' => 'badge-returned',
                                default => 'badge-pending',
                            };
                        @endphp">{{ $b->status }}</span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.borrowings.show', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
                            @if($b->status === 'Menunggu')
                            <form method="POST" action="{{ route('admin.borrowings.approve', $b->id_borrowing) }}" style="display:inline" onsubmit="return confirmAction('Setujui peminjaman ini?')">
                                @csrf
                                <button class="btn btn-sm btn-success">Setuju</button>
                            </form>
                            <button class="btn btn-sm btn-danger" onclick="showRejectModal({{ $b->id_borrowing }})">Tolak</button>
                            @endif
                            @if(in_array($b->status, ['Disetujui', 'Dipinjam']))
                            <a href="{{ route('admin.borrowings.return', $b->id_borrowing) }}" class="btn btn-sm btn-info">Catat Kembali</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:2rem;color:#64748b">Tidak ada peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
    <div class="pagination">{{ $borrowings->appends(request()->query())->links() }}</div>
    @endif
</div>

<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <h2>Tolak Peminjaman</h2>
        <form method="POST" action="" id="rejectForm">
            @csrf
            <div class="form-group">
                <label>Catatan Penolakan</label>
                <textarea name="catatan_admin" required placeholder="Alasan penolakan..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('rejectModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/borrowings/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}
</script>
@endsection
