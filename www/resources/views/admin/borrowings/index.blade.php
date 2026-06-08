@extends('layouts.app')
@section('title', 'Peminjaman')
@section('subtitle', 'Kelola peminjaman alat laboratorium')

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
        <div class="toolbar-item">
            <label>Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}">
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">#{{ $b->id_borrowing }}</td>
                    <td>
                        <div>{{ $b->mahasiswa?->nama_lengkap }}</div>
                        <div style="font-size:11px;color:#6B7280">{{ $b->mahasiswa?->nim }}</div>
                    </td>
                    <td>{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $badgeClass = match($b->status) {
                            'Menunggu' => 'badge-yellow',
                            'Disetujui' => 'badge-blue',
                            'Ditolak' => 'badge-red',
                            'Dipinjam' => 'badge-purple',
                            'Dikembalikan' => 'badge-green',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $b->status }}</span>
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
                            <a href="{{ route('admin.borrowings.return', $b->id_borrowing) }}" class="btn btn-sm" style="background:#6366F1">Catat Kembali</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">Tidak ada peminjaman.</div>
                    </td>
                </tr>
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
        <h2 style="font-size:16px;font-weight:600;margin:0 0 16px">Tolak Peminjaman</h2>
        <form method="POST" action="" id="rejectForm">
            @csrf
            <div class="form-group">
                <label>Catatan Penolakan</label>
                <textarea name="catatan_admin" required placeholder="Alasan penolakan..." rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('rejectModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:50; display:none; align-items:center; justify-content:center; padding:20px; }
.modal-overlay.show { display:flex; }
.modal { background:#fff; border-radius:12px; padding:24px; width:100%; max-width:480px; }
</style>

<script>
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/borrowings/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}
</script>
@endsection
