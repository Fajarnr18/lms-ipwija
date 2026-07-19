@extends('layouts.app')
@section('title', 'Data Peminjaman')
@section('subtitle', 'Kelola peminjaman alat laboratorium')

@section('content')
<div class="tabs">
    <a href="{{ route('admin.borrowings.index', ['tab' => 'semua']) }}" class="{{ ($tab ?? 'semua') === 'semua' ? 'active' : '' }}">Semua</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'menunggu']) }}" class="{{ ($tab ?? '') === 'menunggu' ? 'active' : '' }}">Menunggu</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'aktif']) }}" class="{{ ($tab ?? '') === 'aktif' ? 'active' : '' }}">Aktif</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'selesai']) }}" class="{{ ($tab ?? '') === 'selesai' ? 'active' : '' }}">Selesai</a>
    <a href="{{ route('admin.borrowings.index', ['tab' => 'ditolak']) }}" class="{{ ($tab ?? '') === 'ditolak' ? 'active' : '' }}">Ditolak</a>
</div>

<form method="GET" action="{{ route('admin.borrowings.index') }}">
    <input type="hidden" name="tab" value="{{ $tab ?? 'semua' }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama/NIM peminjam..." value="{{ request('search') }}" style="min-width:200px">
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
                    <th>No</th>
                    <th>Nama Pemohon</th>
                    <th>NIM</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">{{ $loop->iteration + ($borrowings->currentPage() - 1) * $borrowings->perPage() }}</td>
                    <td>
                        <div>{{ $b->mahasiswa?->nama_lengkap }}</div>
                        <div style="font-size:11px;color:#6B7280">{{ $b->mahasiswa?->role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}</div>
                    </td>
                    <td>{{ $b->mahasiswa?->nim }}</td>
                    <td style="white-space:nowrap">{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        if ($b->is_overdue) {
                            $badgeClass = 'badge-red';
                            $statusLabel = 'Terlambat';
                        } else {
                            $badgeClass = match($b->status) {
                                'MENUNGGU' => 'badge-yellow',
                                'DISETUJUI' => 'badge-blue',
                                'DITOLAK' => 'badge-red',
                                'DIPINJAM' => 'badge-purple',
                                    'TERLAMBAT' => 'badge-danger',
                                'DIKEMBALIKAN' => 'badge-green',
                                default => 'badge-gray',
                            };
                            $statusLabel = $b->status;
                        }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('admin.borrowings.show', $b->id_borrowing) }}" class="btn btn-outline btn-sm">Detail</a>
                            @if($b->status === 'MENUNGGU')
                            <form method="POST" action="{{ route('admin.borrowings.approve', $b->id_borrowing) }}" style="display:inline">
                                @csrf
                                <button class="btn btn-success btn-sm" data-confirm="Setujui peminjaman ini?">Setuju</button>
                            </form>
                            <button class="btn btn-danger btn-sm" onclick="showRejectModal({{ $b->id_borrowing }})">Tolak</button>
                            @endif
                            @if($b->status === 'DISETUJUI')
                            <form method="POST" action="{{ route('admin.borrowings.approve', $b->id_borrowing) }}" style="display:inline">
                                @csrf
                                <button class="btn btn-info btn-sm" data-confirm="Proses peminjaman ini?">Proses</button>
                            </form>
                            @endif
                            @if(in_array($b->status, ['DISETUJUI', 'DIPINJAM']))
                            <a href="{{ route('admin.borrowings.return', $b->id_borrowing) }}" class="btn btn-sm" style="background:#8B5CF6">Kembali</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"><div class="empty-state">Tidak ada peminjaman.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
    {{ $borrowings->appends(request()->query())->links() }}
    @endif
</div>

<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <h2>Tolak Peminjaman</h2>
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

<script>
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '{{ url("admin/borrowings") }}/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}
</script>
@endsection

