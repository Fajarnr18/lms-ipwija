@extends('layouts.app')
@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<form method="GET" action="{{ route('mhs.borrowings.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach(['Menunggu','Disetujui','Ditolak','Dipinjam','Dikembalikan'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<div class="card">
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>ID</th><th>Tgl Pengajuan</th><th>Tgl Pinjam</th><th>Tgl Kembali (Rencana)</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td>{{ $b->id_borrowing }}</td>
                    <td>{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td><span class="badge @php echo match($b->status) { 'Menunggu'=>'badge-pending','Disetujui'=>'badge-approved','Ditolak'=>'badge-rejected','Dipinjam'=>'badge-borrowed','Dikembalikan'=>'badge-returned',default=>'badge-pending' } @endphp">{{ $b->status }}</span></td>
                    <td><a href="{{ route('mhs.borrowings.show', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:#64748b">Belum ada peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())<div class="pagination">{{ $borrowings->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
