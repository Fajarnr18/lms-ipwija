@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('subtitle', 'Informasi lengkap peminjaman #' . $borowing->id_borrowing)
@section('header-actions')
<a href="{{ route('admin.borrowings.index', ['tab' => request('back_tab', 'semua')]) }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:16px">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <div class="detail-item">
            <div class="label">Peminjam</div>
            <div class="value">{{ $borowing->mahasiswa?->nama_lengkap }}</div>
        </div>
        <div class="detail-item">
            <div class="label">NIM</div>
            <div class="value">{{ $borowing->mahasiswa?->nim }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Status</div>
            <div class="value">
                @php
                $badgeClass = match($borowing->status) {
                    'Menunggu' => 'badge-yellow',
                    'Disetujui' => 'badge-blue',
                    'Ditolak' => 'badge-red',
                    'Dipinjam' => 'badge-purple',
                    'Dikembalikan' => 'badge-green',
                    default => 'badge-gray',
                };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $borowing->status }}</span>
            </div>
        </div>
        <div class="detail-item">
            <div class="label">Tanggal Pengajuan</div>
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
            <div class="label">Tanggal Diproses</div>
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
            <div class="label">Tanggal Pengembalian</div>
            <div class="value">{{ $borowing->tgl_pengembalian_aktual?->format('d/m/Y H:i') }}</div>
        </div>
        @endif
    </div>

    @if($borowing->status === 'Menunggu')
    <hr class="divider">
    <div style="display:flex;gap:8px">
        <form method="POST" action="{{ route('admin.borrowings.approve', $borowing->id_borrowing) }}" style="display:inline" onsubmit="return confirmAction('Setujui peminjaman ini?')">
            @csrf
            <button class="btn btn-success">Setujui</button>
        </form>
        <button class="btn btn-danger" onclick="showRejectModal({{ $borowing->id_borrowing }})">Tolak</button>
    </div>
    @endif
    @if(in_array($borowing->status, ['Disetujui', 'Dipinjam']))
    <hr class="divider">
    <a href="{{ route('admin.borrowings.return', $borowing->id_borrowing) }}" class="btn" style="background:#6366F1">Catat Pengembalian</a>
    @endif
</div>

<div class="card">
    <h3 style="font-size:14px;font-weight:600;margin:0 0 16px">Alat yang Dipinjam</h3>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Kondisi Kembali</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borowing->borrowingItems as $item)
                <tr>
                    <td>{{ $item->tool?->kode_alat }}</td>
                    <td>{{ $item->tool?->nama_alat }}</td>
                    <td>{{ $item->jumlah_unit }}</td>
                    <td>{{ $item->kondisi_saat_kembali ?? '-' }}</td>
                    <td>{{ $item->catatan_pengembalian ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">Tidak ada alat dalam peminjaman ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
