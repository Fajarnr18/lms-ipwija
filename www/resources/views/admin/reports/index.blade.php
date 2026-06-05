@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 1rem">Export Laporan</h2>
    <div class="grid-2">
        <div class="card" style="margin:0">
            <form method="GET" action="{{ route('admin.reports.export') }}">
                <h3 style="font-size:.875rem;font-weight:600;margin:0 0 .75rem">Peminjaman</h3>
                <input type="hidden" name="type" value="borrowings">
                <div class="toolbar-item"><label>Dari</label><input type="date" name="from" required></div>
                <div class="toolbar-item"><label>Sampai</label><input type="date" name="to" required></div>
                <div class="toolbar-item"><label>Status</label>
                    <select name="status">
                        <option value="">Semua</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dikembalikan">Dikembalikan</option>
                    </select>
                </div>
                <button class="btn btn-sm" style="margin-top:.5rem">Export CSV</button>
            </form>
        </div>
        <div class="card" style="margin:0">
            <form method="GET" action="{{ route('admin.reports.export') }}">
                <h3 style="font-size:.875rem;font-weight:600;margin:0 0 .75rem">Barang & Mutasi</h3>
                <input type="hidden" name="type" value="items">
                <div class="toolbar-item"><label>Kategori</label>
                    <select name="kategori">
                        <option value="">Semua</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Kimia">Kimia</option>
                        <option value="Alat Tulis">Alat Tulis</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <button class="btn btn-sm" style="margin-top:.5rem">Export CSV</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 1rem">Peminjaman Akhir</h2>
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
                    <td><span class="badge @php echo match($b->status) { 'Menunggu'=>'badge-pending','Disetujui'=>'badge-approved','Ditolak'=>'badge-rejected','Dipinjam'=>'badge-borrowed','Dikembalikan'=>'badge-returned',default=>'badge-pending' } @endphp">{{ $b->status }}</span></td>
                    <td>{{ $b->borrowingItems->count() }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:1.5rem;color:#64748b">Belum ada peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
