@extends('layouts.app')
@section('title', 'Keranjang Peminjaman')

@section('content')
<style>
.cart-layout { display:grid; grid-template-columns:1fr 380px; gap:24px; align-items:start; }
.cart-card { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:20px; box-shadow:0 1px 2px rgba(0,0,0,.04); }
.cart-card-title { font-size:15px; font-weight:700; color:#1A1A2E; margin:0 0 16px; }
.cart-card .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
.cart-card .card-header .title { font-size:15px; font-weight:700; color:#1A1A2E; margin:0; }
.cart-card .card-header .count { font-size:13px; color:#6B7280; }
.cart-table { width:100%; border-collapse:collapse; }
.cart-table th { text-align:left; font-size:11px; font-weight:600; color:#6B7280; text-transform:uppercase; letter-spacing:.04em; padding:8px 12px; border-bottom:1.5px solid #E5E7EB; }
.cart-table td { padding:12px; border-bottom:1px solid #F3F4F6; font-size:13px; color:#374151; vertical-align:middle; }
.cart-table .no-col { width:36px; text-align:center; color:#9CA3AF; font-size:12px; }
.cart-table .foto-col { width:48px; }
.cart-table .foto-col img { width:40px; height:40px; border-radius:8px; object-fit:cover; background:#F3F4F6; }
.cart-table .foto-col .no-foto { width:40px; height:40px; border-radius:8px; background:#F3F4F6; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:16px; }
.cart-table .qty-input { width:60px; text-align:center; padding:6px 8px; border:1.5px solid #E5E7EB; border-radius:6px; font-size:13px; font-family:'Inter',sans-serif; outline:none; }
.cart-table .qty-input:focus { border-color:#3B82F6; }
.cart-table .hapus-link { color:#EF4444; font-size:12px; font-weight:500; text-decoration:none; }
.cart-table .hapus-link:hover { text-decoration:underline; }
.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-group { margin-bottom:16px; }
.form-group label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:6px; }
.form-group input, .form-group textarea { width:100%; padding:10px 12px; border:1.5px solid #E5E7EB; border-radius:8px; font-size:13px; font-family:'Inter',sans-serif; outline:none; }
.form-group input:focus, .form-group textarea:focus { border-color:#3B82F6; }
.form-group textarea { resize:vertical; min-height:80px; }
.ringkasan-card { background:#1E3A5F; border-radius:12px; padding:24px; color:#fff; }
.ringkasan-card h3 { font-size:16px; font-weight:700; margin:0 0 20px; }
.ringkasan-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; font-size:13px; }
.ringkasan-row.total { font-weight:700; font-size:15px; padding-top:12px; }
.ringkasan-divider { border:none; border-top:1px solid rgba(255,255,255,.2); margin:12px 0; }
.ringkasan-warning { font-size:11px; color:rgba(255,255,255,.7); line-height:1.5; margin:16px 0; }
.btn-ajukan { width:100%; padding:12px; background:#3B82F6; color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s; }
.btn-ajukan:hover { background:#2563EB; }
.alert-error { padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13px; background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; }
</style>



@if(count($cart) === 0)
<div class="card">
    <div class="empty-state">
        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;color:#D1D5DB"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        Keranjang kosong. <a href="{{ route('dosen.katalog.index') }}" style="color:#3B82F6;text-decoration:none;font-weight:500">Jelajahi katalog</a>
    </div>
</div>
@else
<form method="POST" action="{{ route('dosen.keranjang.ajukan') }}" id="cartForm">
    @csrf
    <div class="cart-layout" style="grid-template-columns:1fr 420px">
        <div class="cart-card" style="grid-column:1/-1">
            <div class="card-header">
                <h3 class="title">Daftar Alat yang Dipilih</h3>
                <span class="count">Total {{ count($cart) }} alat</span>
            </div>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th class="foto-col">Foto</th>
                        <th>Nama Alat</th>
                        <th style="text-align:center">Jumlah</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    @php $tool = $tools->firstWhere('id_alat', $id); @endphp
                    <tr>
                        <td class="no-col">{{ $loop->iteration }}</td>
                        <td class="foto-col">
                            @if($tool)
                            <img src="{{ $tool->foto_url }}" alt="{{ $item['nama_alat'] }}">
                            @else
                            <div class="no-img" style="width:100%;height:100%;background:#F9FAFB;display:flex;align-items:center;justify-content:center;color:#D1D5DB">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:500;color:#1A1A2E">{{ $item['nama_alat'] }}</div>
                            <div style="font-size:11px;color:#9CA3AF;margin-top:2px">{{ $item['kode_alat'] }}</div>
                        </td>
                        <td style="text-align:center">
                            <input type="number" name="kuantitas[{{ $id }}]" value="{{ $item['jumlah_unit'] }}" min="1" max="{{ $item['stok_tersedia'] }}" class="qty-input" data-id="{{ $id }}">
                        </td>
                        <td style="text-align:center">
                            <a href="{{ route('dosen.keranjang.hapus', $id) }}" class="hapus-link" onclick="event.preventDefault(); hapusItem('{{ route('dosen.keranjang.hapus', $id) }}', '{{ $item['nama_alat'] }}')">Hapus</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cart-card">
            <h3 class="cart-card-title">Detail Peminjaman</h3>
            <div class="form-row-2">
                <div class="form-group">
                    <label>Tanggal Dipinjam <span style="color:#EF4444">*</span></label>
                    <input type="date" name="tgl_rencana_pinjam" required min="{{ date('Y-m-d') }}" id="tglPinjam" onchange="hitungDurasi()">
                </div>
                <div class="form-group">
                    <label>Tanggal Kembali <span style="color:#EF4444">*</span></label>
                    <input type="date" name="tgl_rencana_kembali" required min="{{ date('Y-m-d') }}" id="tglKembali" onchange="hitungDurasi()">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label>Catatan Keperluan <span style="color:#EF4444">*</span></label>
                <textarea name="keperluan" required placeholder="Jelaskan keperluan peminjaman..." rows="3">{{ old('keperluan') }}</textarea>
            </div>
        </div>

        <div>
            <div class="ringkasan-card">
                <h3>Ringkasan Pesanan</h3>
                <div class="ringkasan-row">
                    <span>Total Alat</span>
                    <span>{{ count($cart) }}</span>
                </div>
                <div class="ringkasan-row">
                    <span>Total Kuantitas</span>
                    <span>{{ collect($cart)->sum('jumlah_unit') }}</span>
                </div>
                <hr class="ringkasan-divider">
                <div class="ringkasan-row">
                    <span>Estimasi Durasi Total</span>
                    <span id="durasiText">-</span>
                </div>
                <p class="ringkasan-warning">Pastikan data peminjaman sudah benar sebelum menekan tombol ajukan. Persetujuan Admin memakan 1-2 hari kerja.</p>
                <button type="submit" class="btn-ajukan">Ajukan Peminjaman</button>
            </div>

            @if($errors->any())
            <div class="alert-error" style="margin-top:16px">
                <div style="display:flex;gap:8px;align-items:flex-start">
                    <span>&#9888;</span>
                    <div>@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

<script>
function hapusItem(url, nama) {
    showConfirmModal('Hapus ' + nama + ' dari keranjang?', function() {
        window.location.href = url;
    });
}
function hitungDurasi() {
    var pinjam = document.getElementById('tglPinjam').value;
    var kembali = document.getElementById('tglKembali').value;
    var el = document.getElementById('durasiText');
    if (pinjam && kembali) {
        var p = new Date(pinjam), k = new Date(kembali);
        var diff = Math.ceil((k - p) / (1000*60*60*24));
        if (diff > 0) el.textContent = diff + ' hari';
        else el.textContent = '-';
    } else {
        el.textContent = '-';
    }
}
</script>
@endif
@endsection