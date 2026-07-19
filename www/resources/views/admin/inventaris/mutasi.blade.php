@extends('layouts.app')
@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" placeholder="Cari data mutasi..." style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
    </div>
</div>
@endsection


@section('content')
<div style="margin-bottom:16px;font-size:13px;color:#6B7280">
    <a href="{{ route('admin.inventaris.index') }}" style="color:#6B7280;text-decoration:none">Manajemen Alat</a>
    <span style="margin:0 6px">›</span>
    <span style="color:#1A1A2E;font-weight:500">Form Mutasi Barang</span>
</div>

<div class="card" style="margin-bottom:20px">
    <h3 style="font-size:17px;font-weight:700;color:#3B82F6;margin:0 0 4px">Input Mutasi Barang</h3>
    <p style="font-size:12px;color:#6B7280;margin:0 0 16px">Catat pergerakan stok barang lab dengan teliti</p>
    <hr style="border:none;border-top:1.5px solid #E5E7EB;margin:0 -20px 20px">

    <form method="POST" action="{{ route('admin.inventaris.mutasi-store', $item->id_barang) }}" id="formMutasi">
        @csrf

        <div class="form-group">
            <label>Pilih Barang <span style="color:#EF4444">*</span></label>
            <select name="id_barang" required style="width:100%;max-width:400px">
                <option value="{{ $item->id_barang }}" selected>{{ $item->kode_barang }} — {{ $item->nama_barang }} (Stok: {{ $item->stok }} {{ $item->satuan }})</option>
            </select>
            <div style="font-size:11px;color:#9CA3AF;margin-top:4px">Stok saat ini: <strong>{{ $item->stok }} {{ $item->satuan }}</strong></div>
        </div>

        <div style="display:flex;gap:20px">
            <div class="form-group" style="flex:1;margin:0">
                <label>Jenis Mutasi <span style="color:#EF4444">*</span></label>
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:4px">
                    <label class="mutasi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihMutasi(this, 'Masuk')">
                        <input type="radio" name="tipe_mutasi" value="Masuk" class="mutasi-radio" style="display:none">
                        <svg width="14" height="14" fill="none" stroke="#10B981" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                        Masuk
                    </label>
                    <label class="mutasi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihMutasi(this, 'Keluar')">
                        <input type="radio" name="tipe_mutasi" value="Keluar" class="mutasi-radio" style="display:none">
                        <svg width="14" height="14" fill="none" stroke="#EF4444" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6-6 6 6"/></svg>
                        Keluar
                    </label>
                    <label class="mutasi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihMutasi(this, 'Penyesuaian')">
                        <input type="radio" name="tipe_mutasi" value="Penyesuaian" class="mutasi-radio" style="display:none">
                        <svg width="14" height="14" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Penyesuaian
                    </label>
                </div>
                @error('tipe_mutasi')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;align-items:flex-end;gap:16px;flex-shrink:0">
                <div class="form-group" style="margin:0;width:100px">
                    <label>Jumlah <span style="color:#EF4444">*</span></label>
                    <input type="number" name="jumlah" id="inputJumlah" value="1" min="0" style="width:100%;height:36px;text-align:center;font-size:14px;font-weight:600;font-family:'Inter',sans-serif" oninput="hitungStok()">
                    @error('jumlah')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin:0;width:140px">
                    <label>Jumlah per unit <span style="color:#EF4444">*</span></label>
                    <select name="satuan" required style="width:100%">
                        <option value="{{ $item->satuan }}" selected>{{ $item->satuan }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="stokPreviewWrap" style="display:none;margin-bottom:16px">
            <div style="padding:12px 16px;border-radius:8px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:10px" id="stokPreviewBox">
                <span id="stokPreviewText"></span>
            </div>
        </div>

        <div class="form-group">
            <label>Tanggal <span style="color:#EF4444">*</span></label>
            <input type="date" name="tgl_mutasi" value="{{ date('Y-m-d') }}" required style="max-width:280px">
            @error('tgl_mutasi')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom:0">
            <label>Keterangan <span style="color:#EF4444">*</span></label>
            <textarea name="keterangan" rows="3" required placeholder="Tuliskan alasan atau keterangan mutasi..." style="resize:vertical">{{ old('keterangan') }}</textarea>
            @error('keterangan')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px">
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline" style="padding:10px 24px">Batal</a>
            <button type="submit" class="btn" id="btnSubmit" style="padding:10px 24px;background:#3B82F6;color:#fff">Simpan Mutasi</button>
        </div>
    </form>
</div>

<div class="card">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
        <div style="width:36px;height:36px;border-radius:8px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="18" height="18" fill="none" stroke="#F59E0B" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <h3 style="font-size:15px;font-weight:600;color:#1A1A2E;margin:0">Riwayat Mutasi</h3>
        <span style="font-size:12px;color:#6B7280;margin-left:auto">{{ $mutations->count() }} catatan</span>
    </div>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th style="text-align:center">→</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutations as $m)
                <tr>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280">{{ $m->time_stamp ? \Carbon\Carbon::parse($m->time_stamp)->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        @php
                        $mBadge = match($m->tipe_mutasi) {
                            'Masuk' => 'badge-green',
                            'Keluar' => 'badge-red',
                            'Penyesuaian' => 'badge-blue',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $mBadge }}">{{ $m->tipe_mutasi }}</span>
                    </td>
                    <td style="font-weight:600">{{ $m->jumlah }}</td>
                    <td style="text-align:right;font-weight:500">{{ $m->stok_sebelum }}</td>
                    <td style="text-align:center;color:#9CA3AF;font-size:16px">→</td>
                    <td style="font-weight:700;color:#1E3A5F">{{ $m->stok_sesudah }}</td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:#6B7280">{{ $m->keterangan }}</td>
                    <td style="font-size:12px">{{ $m->admin?->nama_lengkap ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8"><div class="empty-state">Belum ada riwayat mutasi untuk barang ini.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function pilihMutasi(el, value) {
    document.querySelectorAll('.mutasi-option').forEach(function(o) {
        o.style.borderColor = '#D1D5DB';
        o.style.background = '#fff';
    });
    el.style.borderColor = '#3B82F6';
    el.style.background = '#EEF2FF';
    el.querySelector('input[type="radio"]').checked = true;
    hitungStok();
}

function ubahJumlah(delta) {
    var input = document.getElementById('inputJumlah');
    var val = parseInt(input.value) || 0;
    val = Math.max(0, val + delta);
    input.value = val;
    hitungStok();
}

function hitungStok() {
    var tipe = document.querySelector('input[name="tipe_mutasi"]:checked');
    var jumlah = parseInt(document.getElementById('inputJumlah').value) || 0;
    var stokAwal = {{ $item->stok }};
    var satuan = '{{ $item->satuan }}';
    var wrap = document.getElementById('stokPreviewWrap');
    var box = document.getElementById('stokPreviewBox');
    var text = document.getElementById('stokPreviewText');

    if (!tipe || jumlah <= 0) {
        wrap.style.display = 'none';
        return;
    }

    wrap.style.display = 'block';
    var tipeVal = tipe.value;
    var hasil, warna, label;

    if (tipeVal === 'Masuk') {
        hasil = stokAwal + jumlah;
        warna = '#10B981';
        label = 'Stok setelah mutasi: ' + stokAwal + ' + ' + jumlah + ' = ';
        box.style.background = '#ECFDF5';
        box.style.border = '1.5px solid #A7F3D0';
    } else if (tipeVal === 'Keluar') {
        hasil = stokAwal - jumlah;
        warna = '#EF4444';
        label = 'Stok setelah mutasi: ' + stokAwal + ' - ' + jumlah + ' = ';
        if (hasil < 0) {
            box.style.background = '#FEF2F2';
            box.style.border = '1.5px solid #FECACA';
            text.innerHTML = '<span style="color:#EF4444">Stok tidak mencukupi (sisa: ' + stokAwal + ' ' + satuan + ')</span>';
            document.getElementById('btnSubmit').disabled = true;
            return;
        }
        box.style.background = '#FEF2F2';
        box.style.border = '1.5px solid #FECACA';
    } else {
        hasil = jumlah;
        warna = '#F59E0B';
        label = 'Stok disesuaikan menjadi: ';
        box.style.background = '#FFFBEB';
        box.style.border = '1.5px solid #FDE68A';
    }

    document.getElementById('btnSubmit').disabled = false;
    text.innerHTML = label + '<strong style="color:' + warna + ';font-size:18px">' + hasil + '</strong> ' + satuan;
}

@if(old('tipe_mutasi'))
document.addEventListener('DOMContentLoaded', function() {
    var checked = document.querySelector('input[name="tipe_mutasi"]:checked');
    if (checked) {
        var label = checked.closest('.mutasi-option');
        if (label) {
            label.style.borderColor = '#3B82F6';
            label.style.background = '#EEF2FF';
            hitungStok();
        }
    }
});
@endif
</script>
@endsection