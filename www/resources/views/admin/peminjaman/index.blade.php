@extends('layouts.app')
@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:500px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" placeholder="Cari peminjaman/alat/status..." style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
    </div>
    <button type="button" class="btn btn-outline" style="display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;font-size:12px;white-space:nowrap">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        Filter
    </button>
</div>
@endsection
@section('title', 'Manajemen Peminjaman')
@section('subtitle', 'Kelola pengajuan peminjaman alat dari civitas akademika')
@section('content')

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px">
        <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#F59E0B,#F97316);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countMenunggu }}</div>
                <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Menunggu Persetujuan</div>
            </div>
        </div>
        <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#3B82F6,#1D4ED8);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countDipinjam }}</div>
                <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Sedang Dipinjam</div>
            </div>
        </div>
        <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#EF4444,#DC2626);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countTerlambat }}</div>
                <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Terlambat</div>
            </div>
        </div>
    </div>

<form method="GET" action="{{ route('admin.peminjaman.index') }}" id="filterForm">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:8px">
                <label style="font-size:13px;font-weight:500;color:#374151;white-space:nowrap">Status</label>
                <select name="status" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif;background:#fff;outline:none;min-width:140px">
                    <option value="">Semua Status</option>
                    <option value="MENUNGGU" {{ request('status') === 'MENUNGGU' ? 'selected' : '' }}>Menunggu</option>
                    <option value="DISETUJUI" {{ request('status') === 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
                    <option value="DITOLAK" {{ request('status') === 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                    <option value="DIPINJAM" {{ request('status') === 'DIPINJAM' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="DIKEMBALIKAN" {{ request('status') === 'DIKEMBALIKAN' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <label style="font-size:13px;font-weight:500;color:#374151;white-space:nowrap">Peran</label>
                <select name="role" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif;background:#fff;outline:none;min-width:120px">
                    <option value="">Semua Peran</option>
                    <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ request('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                </select>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('advancedFilter').classList.toggle('show')" style="padding:8px 16px;border-radius:6px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter Lanjutan
            </button>
            <a href="{{ route('admin.peminjaman.export-csv', request()->query()) }}" class="btn btn-success" style="padding:8px 16px;border-radius:6px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Ekspor CSV
            </a>
        </div>
    </div>

    <div id="advancedFilter" style="display:none;margin-bottom:16px">
        <div class="card" style="padding:16px">
            <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:end">
                <div class="form-group" style="margin:0;flex:1;min-width:160px">
                    <label>Tanggal Awal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()">
                </div>
                <div class="form-group" style="margin:0;flex:1;min-width:160px">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()">
                </div>
                <button type="submit" class="btn btn-sm" style="padding:8px 16px">Terapkan</button>
            </div>
        </div>
    </div>
</form>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>ID Peminjaman</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Nama Peminjam</th>
                    <th>Keperluan</th>
                    <th>Tanggal Pinjam</th>
                    <th>Estimasi Kembali</th>
                    <th>Alat</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E;font-size:12px">#{{ $b->id_borrowing }}</td>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280">{{ $b->tgl_pengajuan?->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="font-weight:500;color:#1A1A2E">{{ $b->mahasiswa?->nama_lengkap }}</div>
                        <div style="font-size:11px;color:#9CA3AF">{{ $b->mahasiswa?->nim }} &bull; {{ $b->mahasiswa?->role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}</div>
                    </td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px">{{ $b->keperluan }}</td>
                    <td style="white-space:nowrap;font-size:12px">{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td style="white-space:nowrap;font-size:12px">{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td style="max-width:180px">
                        @foreach($b->borrowingItems as $item)
                            <div style="font-size:11px;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $item->tool?->kode_alat }} - {{ $item->tool?->nama_alat }}
                                <span style="color:#9CA3AF">x{{ $item->jumlah_unit }}</span>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        @php
                        $st = strtoupper(trim($b->status ?? ''));
                        if ($b->is_overdue) {
                            $badgeClass = 'badge-red';
                            $statusLabel = 'Terlambat';
                        } else {
                            $badgeClass = match($st) {
                                'MENUNGGU' => 'badge-yellow',
                                'DISETUJUI' => 'badge-blue',
                                'DITOLAK' => 'badge-red',
                                'DIPINJAM' => 'badge-purple',
                                    'TERLAMBAT' => 'badge-danger',
                                'DIKEMBALIKAN' => 'badge-green',
                                default => 'badge-gray',
                            };
                            $statusLabel = match($st) {
                                'MENUNGGU' => 'Menunggu',
                                'DISETUJUI' => 'Disetujui',
                                'DITOLAK' => 'Ditolak',
                                'DIPINJAM' => 'Dipinjam',
                                    'TERLAMBAT' => 'Terlambat',
                                'DIKEMBALIKAN' => 'Dikembalikan',
                                default => $b->status,
                            };
                        }
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="font-size:11px">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        <div class="action-group" style="justify-content:center;align-items:center;gap:6px;flex-wrap:nowrap">
                            <a href="{{ route('admin.peminjaman.show', $b->id_borrowing) }}" class="btn btn-outline btn-sm" style="width:28px;height:28px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" title="Detail">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @if(strtoupper(trim($b->status ?? '')) === 'MENUNGGU')
                            <form method="POST" action="{{ route('admin.peminjaman.approve', $b->id_borrowing) }}" style="display:inline-flex">
                                @csrf
                                <button class="btn btn-success btn-sm" style="width:28px;height:28px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" title="Setujui" data-confirm="Setujui peminjaman ini?">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            <button class="btn btn-danger btn-sm" style="width:28px;height:28px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" onclick="showRejectModal('{{ route('admin.peminjaman.reject', $b->id_borrowing) }}')" title="Tolak">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            @endif
                            @if(strtoupper(trim($b->status ?? '')) === 'DISETUJUI')
                            <form method="POST" action="{{ route('admin.peminjaman.proses', $b->id_borrowing) }}" style="display:inline-flex">
                                @csrf
                                <button class="btn btn-info btn-sm" style="width:28px;height:28px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" title="Proses Peminjaman" data-confirm="Proses peminjaman ini? Alat akan dicatat sebagai sedang dipinjam.">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                            @if(in_array(strtoupper(trim($b->status ?? '')), ['DIPINJAM', 'TERLAMBAT']))
                            <a href="{{ route('admin.peminjaman.aktif') }}" class="btn btn-sm" style="width:28px;height:28px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#8B5CF6" title="Pengembalian">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="#D1D5DB" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Tidak ada data peminjaman
                        </div>
                    </td>
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
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
            <div style="width:40px;height:40px;border-radius:10px;background:#FEF2F2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#EF4444" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:16px;font-weight:600;margin:0;color:#1A1A2E">Tolak Peminjaman</h2>
                <p style="font-size:13px;color:#6B7280;margin:2px 0 0">Alasan penolakan wajib diisi</p>
            </div>
        </div>
        <form method="POST" action="" id="rejectForm">
            @csrf
            <div class="form-group">
                <label for="alasan_penolakan">Alasan Penolakan <span style="color:#EF4444">*</span></label>
                <textarea name="catatan_admin" id="alasan_penolakan" required placeholder="Tuliskan alasan mengapa peminjaman ini ditolak..." rows="4" style="resize:vertical;font-size:13px"></textarea>
                <div style="font-size:11px;color:#9CA3AF;margin-top:4px">Alasan akan dikirimkan ke pemohon melalui email</div>
            </div>
            <div class="form-actions" style="margin-top:20px">
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()" style="padding:8px 20px">Batal</button>
                <button type="submit" class="btn btn-danger" id="rejectSubmitBtn" style="padding:8px 20px" disabled>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<style>
#advancedFilter.show { display: block !important; }
</style>

<script>
function showRejectModal(route) {
    document.getElementById('rejectForm').action = route;
    document.getElementById('rejectModal').classList.add('show');
    document.getElementById('alasan_penolakan').value = '';
    document.getElementById('rejectSubmitBtn').disabled = true;
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var alasanInput = document.getElementById('alasan_penolakan');
    var submitBtn = document.getElementById('rejectSubmitBtn');

    if (alasanInput) {
        alasanInput.addEventListener('input', function() {
            submitBtn.disabled = this.value.trim() === '';
        });
    }

    var overlay = document.getElementById('rejectModal');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    }
});
</script>
@endsection

