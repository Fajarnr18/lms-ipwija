@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('header-actions')
<a href="{{ route('dosen.borrowings.index') }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
@endsection

@section('content')
<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 16px">Detail Peminjaman #{{ $borowing->id_borrowing }}</h2>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Status</div>
            <div class="value"><span class="badge @php echo match($borowing->status) { 'Menunggu'=>'badge-yellow','Disetujui'=>'badge-blue','Ditolak'=>'badge-red','Dipinjam'=>'badge-purple','Dikembalikan'=>'badge-green',default=>'badge-gray' } @endphp">{{ $borowing->status }}</span></div>
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
            <div class="value">{{ $borowing->catatan_admin }}</div>
        </div>
        @endif
        @if($borowing->tgl_pengembalian_aktual)
        <div class="detail-item">
            <div class="label">Tgl Dikembalikan</div>
            <div class="value">{{ $borowing->tgl_pengembalian_aktual?->format('d/m/Y H:i') }}</div>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 16px">Alat yang Dipinjam</h2>
    <div style="overflow-x:auto">
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
