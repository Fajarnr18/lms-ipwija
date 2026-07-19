@extends('layouts.app')
@section('header-title', 'Edit Barang')
@section('title', '')
@section('subtitle', '')

@section('content')
<div style="margin-bottom:16px;font-size:13px;color:#6B7280">
    <a href="{{ route('admin.inventaris.index') }}" style="color:#6B7280;text-decoration:none">Inventaris</a>
    <span style="margin:0 6px">›</span>
    <a href="{{ route('admin.inventaris.detail', $item->id_barang) }}" style="color:#6B7280;text-decoration:none">{{ $item->nama_barang }}</a>
    <span style="margin:0 6px">›</span>
    <span style="color:#1A1A2E;font-weight:500">Edit barang</span>
</div>

<form method="POST" action="{{ route('admin.inventaris.update', $item->id_barang) }}" style="max-width:960px" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        <div class="card">
            <h3 style="font-size:15px;font-weight:600;color:#1A1A2E;margin-bottom:16px">Informasi Dasar</h3>
            <hr style="border:none;border-top:1.5px solid #E5E7EB;margin:0 -20px 20px">

            <div class="form-group">
                <label>Foto Barang</label>
                @if($item->foto_url)
                <div style="margin-bottom:12px;height:120px;border-radius:6px;overflow:hidden;border:1px solid #E5E7EB">
                    <img src="{{ $item->foto_url }}" alt="Foto" style="width:100%;height:100%;object-fit:cover">
                </div>
                @endif
                <input type="file" name="foto_barang" accept="image/png,image/jpeg,image/jpg,image/webp" style="padding:6px 12px;width:100%;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif">
                @error('foto_barang')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Nama Barang <span style="color:#EF4444">*</span></label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required placeholder="Masukkan Nama Barang">
                @error('nama_barang')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Kategori <span style="color:#EF4444">*</span></label>
                <select name="kategori" required>
                    <option value="">Pilih kategori</option>
                    <option value="Elektronik" @selected(old('kategori', $item->kategori)==='Elektronik')>Elektronik</option>
                    <option value="Kimia" @selected(old('kategori', $item->kategori)==='Kimia')>Kimia</option>
                    <option value="Fisika" @selected(old('kategori', $item->kategori)==='Fisika')>Fisika</option>
                    <option value="Biologi" @selected(old('kategori', $item->kategori)==='Biologi')>Biologi</option>
                    <option value="Mekanik" @selected(old('kategori', $item->kategori)==='Mekanik')>Mekanik</option>
                    <option value="Komputer" @selected(old('kategori', $item->kategori)==='Komputer')>Komputer</option>
                    <option value="Laboratorium" @selected(old('kategori', $item->kategori)==='Laboratorium')>Laboratorium</option>
                    <option value="Umum" @selected(old('kategori', $item->kategori)==='Umum')>Umum</option>
                </select>
                @error('kategori')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label>Kode Inventaris (SKU) <span style="color:#EF4444">*</span></label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" required placeholder="Contoh: BRG-001">
                @error('kode_barang')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>

        <div>
            <div style="background:#fff;border-radius:8px;border:1px solid #E5E7EB;padding:20px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
                <h4 style="font-size:13px;font-weight:600;color:#1A1A2E;margin-bottom:12px">Stok & Lokasi</h4>
                <hr style="border:none;border-top:1.5px solid #E5E7EB;margin:0 -20px 16px">

                <div class="form-group">
                    <label>Jumlah Stok <span style="color:#EF4444">*</span></label>
                    <div style="display:flex;align-items:center;gap:0;border:1.5px solid #E5E7EB;border-radius:6px;overflow:hidden;width:fit-content">
                        <button type="button" onclick="ubahStok(-1)" style="width:36px;height:36px;border:none;background:#F9FAFB;cursor:pointer;font-size:16px;font-weight:600;color:#6B7280;display:flex;align-items:center;justify-content:center;border-right:1.5px solid #E5E7EB">−</button>
                        <input type="number" name="stok" id="inputStok" value="{{ old('stok', $item->stok) }}" min="0" style="width:60px;height:36px;border:none;text-align:center;font-size:14px;font-weight:600;outline:none;font-family:'Inter',sans-serif">
                        <button type="button" onclick="ubahStok(1)" style="width:36px;height:36px;border:none;background:#F9FAFB;cursor:pointer;font-size:16px;font-weight:600;color:#6B7280;display:flex;align-items:center;justify-content:center;border-left:1.5px solid #E5E7EB">+</button>
                    </div>
                    @error('stok')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Satuan <span style="color:#EF4444">*</span></label>
                    <select name="satuan" required>
                        <option value="">Pilih satuan</option>
                        <option value="Unit" @selected(old('satuan', $item->satuan)==='Unit')>Unit</option>
                        <option value="Pcs" @selected(old('satuan', $item->satuan)==='Pcs')>Pcs</option>
                        <option value="Kg" @selected(old('satuan', $item->satuan)==='Kg')>Kg</option>
                        <option value="Liter" @selected(old('satuan', $item->satuan)==='Liter')>Liter</option>
                        <option value="Box" @selected(old('satuan', $item->satuan)==='Box')>Box</option>
                        <option value="Paket" @selected(old('satuan', $item->satuan)==='Paket')>Paket</option>
                        <option value="Set" @selected(old('satuan', $item->satuan)==='Set')>Set</option>
                        <option value="Meter" @selected(old('satuan', $item->satuan)==='Meter')>Meter</option>
                    </select>
                    @error('satuan')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label>Lokasi Lab/Rak <span style="color:#EF4444">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}" required placeholder="Contoh: Lab Komputer A / Rak 3">
                    @error('lokasi')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px">
                <button type="submit" class="btn" style="padding:10px 24px;background:#3B82F6;color:#fff">Simpan Perubahan</button>
                <a href="{{ route('admin.inventaris.detail', $item->id_barang) }}" class="btn btn-outline" style="padding:10px 24px">Batal & Kembali</a>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px">
        <h3 style="font-size:15px;font-weight:600;color:#1A1A2E;margin-bottom:16px">Spesifikasi & Kondisi</h3>
        <hr style="border:none;border-top:1.5px solid #E5E7EB;margin:0 -20px 20px">

        <div class="form-group">
            <label>Kondisi Saat Ini <span style="color:#EF4444">*</span></label>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:4px">
                <label class="kondisi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihKondisi(this, 'Baik')">
                    <input type="radio" name="kondisi" value="Baik" @checked(old('kondisi', $item->kondisi)==='Baik') style="display:none">
                    <span style="width:8px;height:8px;border-radius:50%;background:#10B981;display:inline-block"></span>
                    Baik
                </label>
                <label class="kondisi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihKondisi(this, 'Rusak Ringan')">
                    <input type="radio" name="kondisi" value="Rusak Ringan" @checked(old('kondisi', $item->kondisi)==='Rusak Ringan') style="display:none">
                    <span style="width:8px;height:8px;border-radius:50%;background:#F59E0B;display:inline-block"></span>
                    Rusak Ringan
                </label>
                <label class="kondisi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihKondisi(this, 'Rusak Berat')">
                    <input type="radio" name="kondisi" value="Rusak Berat" @checked(old('kondisi', $item->kondisi)==='Rusak Berat') style="display:none">
                    <span style="width:8px;height:8px;border-radius:50%;background:#EF4444;display:inline-block"></span>
                    Rusak Berat
                </label>
                <label class="kondisi-option" style="display:flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px solid #D1D5DB;border-radius:8px;cursor:pointer;transition:all .2s;font-size:13px;background:#fff" onclick="pilihKondisi(this, 'Tidak Layak')">
                    <input type="radio" name="kondisi" value="Tidak Layak" @checked(old('kondisi', $item->kondisi)==='Tidak Layak') style="display:none">
                    <span style="width:8px;height:8px;border-radius:50%;background:#6B7280;display:inline-block"></span>
                    Tidak Layak
                </label>
            </div>
            @error('kondisi')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Tanggal Pendataan <span style="color:#EF4444">*</span></label>
            <input type="date" name="tgl_pendataan" value="{{ old('tgl_pendataan', $item->tgl_pendataan?->format('Y-m-d')) }}" required style="max-width:280px">
            @error('tgl_pendataan')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom:0">
            <label>Deskripsi Lengkap</label>
            <textarea name="deskripsi" rows="4" placeholder="Masukkan deskripsi lengkap barang..." style="resize:vertical">{{ old('deskripsi', $item->deskripsi) }}</textarea>
            @error('deskripsi')<div class="error-text">{{ $message }}</div>@enderror
        </div>
    </div>
</form>

<script>
function pilihKondisi(el, value) {
    document.querySelectorAll('.kondisi-option').forEach(function(o) {
        o.style.borderColor = '#D1D5DB';
        o.style.background = '#fff';
    });
    el.style.borderColor = '#3B82F6';
    el.style.background = '#EEF2FF';
    el.querySelector('input[type="radio"]').checked = true;
}

function ubahStok(delta) {
    var input = document.getElementById('inputStok');
    var val = parseInt(input.value) || 0;
    val = Math.max(0, val + delta);
    input.value = val;
}

document.addEventListener('DOMContentLoaded', function() {
    var checked = document.querySelector('input[name="kondisi"]:checked');
    if (checked) {
        var label = checked.closest('.kondisi-option');
        if (label) {
            label.style.borderColor = '#3B82F6';
            label.style.background = '#EEF2FF';
        }
    }
});
</script>
@endsection