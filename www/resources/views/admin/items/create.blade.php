@extends('layouts.app')
@section('title', 'Tambah Barang')

@section('content')
<a href="{{ route('admin.items.index') }}" class="btn btn-outline btn-sm mb-3">&larr; Kembali</a>

<div class="card">
    <form method="POST" action="{{ route('admin.items.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">Pilih</option>
                    <option value="Elektronik" @selected(old('kategori')==='Elektronik')>Elektronik</option>
                    <option value="Kimia" @selected(old('kategori')==='Kimia')>Kimia</option>
                    <option value="Alat Tulis" @selected(old('kategori')==='Alat Tulis')>Alat Tulis</option>
                    <option value="Lainnya" @selected(old('kategori')==='Lainnya')>Lainnya</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required>
            </div>
            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required>{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok') }}" required min="0">
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan') }}" required placeholder="ex: buah, liter">
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    <option value="Baik" @selected(old('kondisi')==='Baik')>Baik</option>
                    <option value="Rusak Ringan" @selected(old('kondisi')==='Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(old('kondisi')==='Rusak Berat')>Rusak Berat</option>
                    <option value="Tidak Layak" @selected(old('kondisi')==='Tidak Layak')>Tidak Layak</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" required>
            </div>
            <div class="form-group">
                <label>Tgl Pendataan</label>
                <input type="date" name="tgl_pendataan" value="{{ old('tgl_pendataan', date('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline">Batal</a>
            <button class="btn">Simpan</button>
        </div>
    </form>
</div>
@endsection
