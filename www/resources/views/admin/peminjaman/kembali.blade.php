@extends('layouts.app')
@section('title', 'Catat Pengembalian')
@section('subtitle', 'Verifikasi dan catat kondisi alat yang dikembalikan')
@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:500px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" placeholder="Cari ID Peminjaman..." style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
    </div>
    <button type="button" class="btn btn-outline" style="display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;font-size:12px;white-space:nowrap">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        Filter
    </button>
</div>
@endsection
@section('content')
<style>
.kembali-wrap { display:flex; flex-direction:column; gap:20px; }

.info-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
@media(max-width:1024px){ .info-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:640px){ .info-grid { grid-template-columns:1fr; } }

.kondisi-radio { display:none; }
.kondisi-label { display:flex; align-items:center; gap:8px; padding:10px 14px; border:1.5px solid #E5E7EB; border-radius:8px; cursor:pointer; transition:all .15s; font-size:13px; font-weight:500; color:#6B7280; background:#fff; white-space:nowrap; }
.kondisi-label:hover { border-color:#D1D5DB; background:#F9FAFB; }
.kondisi-radio:checked + .kondisi-label { border-color:#3B82F6; background:#EEF2FF; color:#1E4FD8; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
.kondisi-radio:checked + .kondisi-label.kondisi-baik { border-color:#10B981; background:#ECFDF5; color:#065F46; box-shadow:0 0 0 3px rgba(16,185,129,.1); }
.kondisi-radio:checked + .kondisi-label.kondisi-ringan { border-color:#F59E0B; background:#FFFBEB; color:#92400E; box-shadow:0 0 0 3px rgba(245,158,11,.1); }
.kondisi-radio:checked + .kondisi-label.kondisi-berat { border-color:#EF4444; background:#FEF2F2; color:#991B1B; box-shadow:0 0 0 3px rgba(239,68,68,.1); }
.kondisi-radio:checked + .kondisi-label.kondisi-hilang { border-color:#6B7280; background:#F3F4F6; color:#374151; box-shadow:0 0 0 3px rgba(107,114,128,.1); }
</style>

<div class="kembali-wrap">

    {{-- INFO CARD --}}
    <div class="card" style="padding:24px">
        <div class="info-grid">
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">ID Peminjaman</div>
                <div class="value" style="font-size:16px;font-weight:700;color:#1A1A2E;text-transform:uppercase">#{{ $borowing->id_borrowing }}</div>
            </div>
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Nama Peminjam</div>
                <div class="value" style="font-size:16px;font-weight:700;color:#1A1A2E;text-transform:uppercase">{{ $borowing->mahasiswa?->nama_lengkap }}</div>
            </div>
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Approval Lab</div>
                <div class="value" style="font-size:16px;font-weight:700;color:#1A1A2E;text-transform:uppercase">{{ $borowing->prosesOleh?->nama_lengkap ?: '-' }}</div>
            </div>
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Status Peminjaman</div>
                <div><span class="badge badge-purple" style="font-size:13px;padding:4px 14px">DIPINJAM</span></div>
            </div>
        </div>
        <hr class="divider" style="margin:16px 0">
        <div class="info-grid">
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Tanggal Pengajuan</div>
                <div class="value" style="font-size:15px;font-weight:600;color:#1A1A2E;text-transform:uppercase">{{ $borowing->tgl_pengajuan?->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Tanggal Pinjam</div>
                <div class="value" style="font-size:15px;font-weight:600;color:#1A1A2E;text-transform:uppercase">{{ $borowing->tgl_rencana_pinjam?->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Estimasi Pengembalian</div>
                <div class="value" style="font-size:15px;font-weight:600;color:#1A1A2E;text-transform:uppercase">{{ $borowing->tgl_rencana_kembali?->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="label" style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">Keperluan</div>
                <div class="value" style="font-size:15px;font-weight:600;color:#1A1A2E;text-transform:uppercase">{{ $borowing->keperluan }}</div>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('admin.peminjaman.kembali', $borowing->id_borrowing) }}" id="formKembali">
        @csrf

        {{-- DAFTAR ALAT & VERIFIKASI KONDISI --}}
        <div class="card" style="padding:24px">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                <div style="width:40px;height:40px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="20" height="20" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size:18px;font-weight:700;color:#1A1A2E;margin:0">Daftar Alat & Verifikasi Kondisi</h2>
                    <p style="font-size:13px;color:#6B7280;margin:2px 0 0">Periksa dan pilih kondisi setiap alat yang dikembalikan</p>
                </div>
            </div>

            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;text-align:center">No</th>
                            <th>Nama Alat</th>
                            <th style="min-width:480px">Kondisi Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $globalIdx = 0; @endphp
                        @foreach($borowing->borrowingItems as $item)
                            @for($i = 1; $i <= $item->jumlah_unit; $i++)
                                <tr>
                                    <td style="text-align:center;font-weight:600;font-size:14px">{{ ++$globalIdx }}</td>
                                    <td>
                                        <div style="font-weight:600;color:#1A1A2E">{{ $item->tool?->nama_alat }}</div>
                                        <div style="font-size:12px;color:#6B7280;margin-top:2px">Kode: {{ $item->tool?->kode_alat }} &middot; Unit {{ $i }} dari {{ $item->jumlah_unit }}</div>
                                    </td>
                                    <td>
                                        <input type="hidden" name="items[{{ $globalIdx }}][id_borrowings_item]" value="{{ $item->id_borrowings_item }}">
                                        <div style="display:flex;gap:8px;flex-wrap:wrap">
                                            <label>
                                                <input type="radio" name="items[{{ $globalIdx }}][kondisi_saat_kembali]" value="Baik" class="kondisi-radio kondisi-check" required>
                                                <span class="kondisi-label kondisi-baik">
                                                    <svg width="18" height="18" fill="none" stroke="#10B981" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Baik
                                                </span>
                                            </label>
                                            <label>
                                                <input type="radio" name="items[{{ $globalIdx }}][kondisi_saat_kembali]" value="Rusak Ringan" class="kondisi-radio kondisi-check" required>
                                                <span class="kondisi-label kondisi-ringan">
                                                    <svg width="18" height="18" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Rusak Ringan
                                                </span>
                                            </label>
                                            <label>
                                                <input type="radio" name="items[{{ $globalIdx }}][kondisi_saat_kembali]" value="Rusak Berat" class="kondisi-radio kondisi-check" required>
                                                <span class="kondisi-label kondisi-berat">
                                                    <svg width="18" height="18" fill="none" stroke="#EF4444" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Rusak Berat
                                                </span>
                                            </label>
                                            <label>
                                                <input type="radio" name="items[{{ $globalIdx }}][kondisi_saat_kembali]" value="Tidak Layak" class="kondisi-radio kondisi-check" required>
                                                <span class="kondisi-label kondisi-hilang">
                                                    <svg width="18" height="18" fill="none" stroke="#6B7280" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Tidak Layak
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TANGGAL PENGEMBALIAN AKTUAL --}}
        <div class="card" style="padding:20px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                <div style="width:36px;height:36px;border-radius:10px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" fill="none" stroke="#8B5CF6" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 style="font-size:15px;font-weight:600;margin:0;color:#1A1A2E">Tanggal Pengembalian Aktual</h3>
            </div>
            <input type="date" name="tgl_pengembalian_aktual" value="{{ date('Y-m-d') }}" required style="width:100%;max-width:320px;padding:10px 14px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:14px;font-family:'Inter',sans-serif;background:#fff;outline:none;transition:all .2s" onfocus="this.style.borderColor='#8B5CF6';this.style.boxShadow='0 0 0 3px rgba(139,92,246,.1)'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
        </div>

        {{-- CATATAN PETUGAS --}}
        <div class="card" style="padding:20px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                <div style="width:36px;height:36px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 style="font-size:15px;font-weight:600;margin:0;color:#1A1A2E">Catatan Petugas</h3>
            </div>
            <textarea name="catatan_petugas" rows="3" placeholder="Tuliskan catatan atau keterangan tambahan..." style="width:100%;padding:10px 14px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:14px;font-family:'Inter',sans-serif;background:#fff;outline:none;resize:vertical;transition:all .2s" onfocus="this.style.borderColor='#F59E0B';this.style.boxShadow='0 0 0 3px rgba(245,158,11,.1)'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">{{ old('catatan_petugas') }}</textarea>
        </div>

        {{-- BUTTONS --}}
        <div style="display:flex;align-items:center;justify-content:end;gap:12px">
            <a href="{{ route('admin.peminjaman.aktif') }}" class="btn btn-outline" style="display:flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;font-size:14px;font-weight:600">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Batal
            </a>
            <button type="submit" class="btn btn-info" id="simpanBtn" style="display:flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;font-size:14px;font-weight:600">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Verifikasi
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('formKembali').addEventListener('submit', function(e) {
    var groups = {};
    document.querySelectorAll('.kondisi-check').forEach(function(r) {
        groups[r.getAttribute('name')] = groups[r.getAttribute('name')] || r.checked;
    });
    var allFilled = Object.keys(groups).length > 0 && Object.values(groups).every(function(v) { return v; });
    if (!allFilled) {
        e.preventDefault();
        showNotifModal('error', 'Semua alat harus dipilih kondisinya.');
    }
});
</script>
@endsection
