@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('subtitle', 'Informasi lengkap peminjaman #' . $borowing->id_borrowing)

@section('header-actions')
<div style="display:flex;align-items:center;gap:12px">
    <span id="realtimeClock" style="font-size:13px;font-weight:600;color:#1E3A5F;font-variant-numeric:tabular-nums"></span>
</div>
@endsection

@section('content')
@php
$st = strtoupper(trim($borowing->status ?? ''));
$totalUnit = $borowing->borrowingItems->sum('jumlah_unit');
@endphp

<style>
.kembali-btn-group { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
@media(min-width:640px){ .kembali-btn-group { justify-content:end; } }
</style>

<div style="display:flex;flex-direction:column;gap:20px">

    {{-- ROW 1: Informasi Peminjam + Detail Waktu & Keperluan --}}
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">
        {{-- LEFT: Informasi Peminjam --}}
        <div class="card" style="padding:24px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:10px;flex:1">
                    <div style="width:38px;height:38px;border-radius:10px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="18" height="18" fill="none" stroke="#8B5CF6" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0">Informasi Peminjam</h2>
                </div>
                @php
                if ($borowing->is_overdue) {
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
                        default => $borowing->status,
                    };
                }
                @endphp
                <span class="badge {{ $badgeClass }}" style="font-size:13px;padding:6px 14px">{{ $statusLabel }}</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                    <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Nama Mahasiswa</div>
                    <div class="value" style="font-size:16px;font-weight:700;color:#1A1A2E">{{ $borowing->mahasiswa?->nama_lengkap }}</div>
                </div>
                <div>
                    <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">NIM Mahasiswa</div>
                    <div class="value" style="font-size:15px;font-weight:600;color:#1A1A2E">{{ $borowing->mahasiswa?->nim }}</div>
                </div>
                <div>
                    <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Jurusan</div>
                    <div class="value" style="font-size:15px;font-weight:600;color:#1A1A2E">{{ $borowing->mahasiswa?->program_studi ?: '-' }}</div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Detail Waktu & Keperluan --}}
        <div class="card" style="padding:24px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
                <div style="width:38px;height:38px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0">Detail Waktu & Keperluan</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px">
                <div>
                    <div class="label" style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">ID Peminjaman</div>
                    <div class="value" style="font-size:15px;font-weight:700;color:#1E3A5F">#{{ $borowing->id_borrowing }}</div>
                </div>
                <div>
                    <div class="label" style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Tanggal Pengajuan</div>
                    <div class="value" style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $borowing->tgl_pengajuan?->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="label" style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Tanggal Pinjam</div>
                    <div class="value" style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $borowing->tgl_rencana_pinjam?->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="label" style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Estimasi Kembali</div>
                    <div class="value" style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $borowing->tgl_rencana_kembali?->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="label" style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Keperluan</div>
                    <div class="value" style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $borowing->keperluan }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 2: Daftar Alat Pinjaman + Catatan Penggunaan Lab --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
        {{-- LEFT: Daftar Alat Pinjaman --}}
        <div class="card" style="padding:24px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:38px;height:38px;border-radius:10px;background:#ECFDF5;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="18" height="18" fill="none" stroke="#10B981" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0">Daftar Alat Pinjaman</h2>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#6B7280">Total Unit</span>
                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;border-radius:8px;background:#EEF2FF;color:#1E4FD8;font-size:15px;font-weight:700;padding:0 10px">{{ $totalUnit }}</span>
                </div>
            </div>
            <div style="overflow-x:auto">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:40px">No</th>
                                <th>Kode</th>
                                <th>Nama Alat</th>
                                <th style="text-align:center">Jumlah</th>
                                <th>Kondisi Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borowing->borrowingItems as $item)
                            <tr>
                                <td style="text-align:center;font-weight:600">{{ $loop->iteration }}</td>
                                <td>{{ $item->tool?->kode_alat }}</td>
                                <td>{{ $item->tool?->nama_alat }}</td>
                                <td style="text-align:center;font-weight:600">{{ $item->jumlah_unit }}</td>
                                <td>{{ $item->kondisi_saat_kembali ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5"><div class="empty-state">Tidak ada alat dalam peminjaman ini.</div></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
            </div>
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid #E5E7EB">
                <span style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#6B7280">Total Keseluruhan</span>
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;border-radius:8px;background:#1E3A5F;color:#fff;font-size:16px;font-weight:800;padding:0 12px">{{ $totalUnit }}</span>
            </div>
        </div>

        {{-- RIGHT: Catatan Penggunaan Lab --}}
        <div class="card" style="padding:24px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
                <div style="width:38px;height:38px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0">Catatan Penggunaan Lab</h2>
            </div>
            <textarea id="catatanLab" rows="5" placeholder="Tulis catatan penggunaan lab..." style="width:100%;padding:12px 14px;border:1.5px solid #E5E7EB;border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;background:#fff;outline:none;resize:vertical;transition:all .2s" onfocus="this.style.borderColor='#F59E0B';this.style.boxShadow='0 0 0 3px rgba(245,158,11,.1)'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">{{ $borowing->catatan_admin }}</textarea>
            <div id="catatanStatus" style="font-size:11px;color:#9CA3AF;margin-top:6px;text-align:right"></div>
            <div style="display:flex;align-items:center;gap:6px;margin-top:16px">
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;white-space:nowrap">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                @if($st === 'MENUNGGU')
                <form method="POST" action="{{ route('admin.peminjaman.approve', $borowing->id_borrowing) }}" style="display:inline-flex">
                    @csrf
                    <button class="btn btn-success" style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;white-space:nowrap" data-confirm="Setujui peminjaman ini?">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Approve
                    </button>
                </form>
                <button class="btn btn-danger" onclick="showRejectModal('{{ route('admin.peminjaman.reject', $borowing->id_borrowing) }}')" style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;white-space:nowrap">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reject
                </button>
                @endif
                @if($st === 'DISETUJUI')
                <form method="POST" action="{{ route('admin.peminjaman.proses', $borowing->id_borrowing) }}" style="display:inline-flex">
                    @csrf
                    <button class="btn btn-info" style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;white-space:nowrap" data-confirm="Proses peminjaman ini? Status akan berubah menjadi Dipinjam.">Proses Peminjaman</button>
                </form>
                @endif
                @if(in_array($st, ['DIPINJAM', 'TERLAMBAT']))
                <a href="{{ route('admin.peminjaman.kembali-form', $borowing->id_borrowing) }}" class="btn" style="background:#8B5CF6;display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;white-space:nowrap">Catat Pengembalian</a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- REJECT MODAL --}}
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
                    Tolak Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Real-time clock
function updateClock() {
    var now = new Date();
    var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    var day = days[now.getDay()];
    var date = now.toLocaleDateString('id-ID', { year:'numeric', month:'long', day:'numeric' });
    var time = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    document.getElementById('realtimeClock').textContent = day + ', ' + date + ' | ' + time;
}
updateClock();
setInterval(updateClock, 1000);

// Reject modal
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

    // Auto-save catatan
    var catatan = document.getElementById('catatanLab');
    var statusEl = document.getElementById('catatanStatus');
    var saveTimer;
    if (catatan) {
        catatan.addEventListener('input', function() {
            statusEl.textContent = 'Belum disimpan...';
            statusEl.style.color = '#F59E0B';
            clearTimeout(saveTimer);
            saveTimer = setTimeout(function() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("admin.peminjaman.catatan", $borowing->id_borrowing) }}');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        statusEl.textContent = 'Tersimpan otomatis.';
                        statusEl.style.color = '#10B981';
                    } else {
                        statusEl.textContent = 'Gagal menyimpan.';
                        statusEl.style.color = '#EF4444';
                    }
                };
                xhr.send('catatan=' + encodeURIComponent(catatan.value));
            }, 800);
        });
    }
});
</script>
@endsection


