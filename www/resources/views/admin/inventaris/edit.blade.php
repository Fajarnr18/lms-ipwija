@extends('layouts.app')
@section('title', 'Edit Barang')
@section('subtitle', 'Ubah data barang inventaris')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.inventaris.update', $item->id_barang) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" required>
                @error('kode_barang')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <select name="satuan" required>
                    <option value="">Pilih satuan</option>
                    <option value="unit" @selected(old('satuan', $item->satuan)==='unit')>Unit</option>
                    <option value="kg" @selected(old('satuan', $item->satuan)==='kg')>Kg</option>
                    <option value="liter" @selected(old('satuan', $item->satuan)==='liter')>Liter</option>
                    <option value="pcs" @selected(old('satuan', $item->satuan)==='pcs')>Pcs</option>
                    <option value="box" @selected(old('satuan', $item->satuan)==='box')>Box</option>
                </select>
                @error('satuan')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group full">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required>
                @error('nama_barang')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori', $item->kategori) }}">
                @error('kategori')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    <option value="">Pilih kondisi</option>
                    <option value="Baik" @selected(old('kondisi', $item->kondisi)==='Baik')>Baik</option>
                    <option value="Rusak Ringan" @selected(old('kondisi', $item->kondisi)==='Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(old('kondisi', $item->kondisi)==='Rusak Berat')>Rusak Berat</option>
                </select>
                @error('kondisi')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $item->stok) }}" required min="0">
                @error('stok')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}" required>
                @error('lokasi')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Tanggal Pendataan</label>
                <input type="date" name="tgl_pendataan" value="{{ old('tgl_pendataan', $item->tgl_pendataan?->format('Y-m-d')) }}" required>
                @error('tgl_pendataan')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="3">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                @error('deskripsi')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline">Batal</a>
            <button class="btn">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
