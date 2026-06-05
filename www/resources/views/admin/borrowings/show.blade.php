@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
<a href="{{ route('admin.borrowings.index', ['tab' => request('back_tab', 'semua')]) }}" class="btn btn-outline btn-sm mb-3">&larr; Kembali</a>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 1rem">Detail Peminjaman #{{ $borowing->id_borrowing }}</h2>
    <div class="grid-2">
        <div class="form-group"><label>Peminjam</label><span>{{ $borowing->mahasiswa?->nama_lengkap }}</span></div>
        <div class="form-group"><label>NIM</label><span>{{ $borowing->mahasiswa?->nim }}</span></div>
        <div class="form-group"><label>Status</label><span class="badge @php echo match($borowing->status) { 'Menunggu' => 'badge-pending', 'Disetujui' => 'badge-approved', 'Ditolak' => 'badge-rejected', 'Dipinjam' => 'badge-borrowed', 'Dikembalikan' => 'badge-returned', default => 'badge-pending' } @endphp">{{ $borowing->status }}</span></div>
        <div class="form-group"><label>Tgl Pengajuan</label><span>{{ $borowing->tgl_pengajuan?->format('d/m/Y H:i') }}</span></div>
        <div class="form-group"><label>Rencana Pinjam</label><span>{{ $borowing->tgl_rencana_pinjam?->format('d/m/Y') }}</span></div>
        <div class="form-group"><label>Rencana Kembali</label><span>{{ $borowing->tgl_rencana_kembali?->format('d/m/Y') }}</span></div>
        <div class="form-group full"><label>Keperluan</label><span>{{ $borowing->keperluan }}</span></div>
        @if($borowing->prosesOleh)
        <div class="form-group"><label>Diproses Oleh</label><span>{{ $borowing->prosesOleh?->nama_lengkap }}</span></div>
        <div class="form-group"><label>Tgl Diproses</label><span>{{ $borowing->tgl_diproses?->format('d/m/Y H:i') }}</span></div>
        @endif
        @if($borowing->catatan_admin)
        <div class="form-group full"><label>Catatan Admin</label><span>{{ $borowing->catatan_admin }}</span></div>
        @endif
        @if($borowing->tgl_pengembalian_aktual)
        <div class="form-group"><label>Tgl Pengembalian</label><span>{{ $borowing->tgl_pengembalian_aktual?->format('d/m/Y H:i') }}</span></div>
        @endif
    </div>
</div>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 1rem">Alat yang Dipinjam</h2>
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>Kode</th><th>Nama Alat</th><th>Jumlah</th><th>Kondisi Kembali</th><th>Catatan</th></tr></thead>
            <tbody>
                @foreach($borowing->borrowingItems as $item)
                <tr>
                    <td>{{ $item->tool?->kode_alat }}</td>
                    <td>{{ $item->tool?->nama_alat }}</td>
                    <td>{{ $item->jumlah_unit }}</td>
                    <td>{{ $item->kondisi_saat_kembali ?? '-' }}</td>
                    <td>{{ $item->catatan_pengembalian ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
