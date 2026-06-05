@extends('layouts.app')
@section('title', 'Edit Barang')

@section('content')
<a href="{{ route('admin.items.index') }}" class="btn btn-outline btn-sm mb-3">&larr; Kembali</a>

<div class="card">
    <form method="POST" action="{{ route('admin.items.update', $item->id_barang) }}">
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="Elektronik" @selected(old('kategori', $item->kategori)==='Elektronik')>Elektronik</option>
                    <option value="Kimia" @selected(old('kategori', $item->kategori)==='Kimia')>Kimia</option>
                    <option value="Alat Tulis" @selected(old('kategori', $item->kategori)==='Alat Tulis')>Alat Tulis</option>
                    <option value="Lainnya" @selected(old('kategori', $item->kategori)==='Lainnya')>Lainnya</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required>
            </div>
            <div class="form-group full"><label>Deskripsi</label><textarea name="deskripsi" required>{{ old('deskripsi', $item->deskripsi) }}</textarea></div>
            <div class="form-group"><label>Stok</label><input type="number" name="stok" value="{{ old('stok', $item->stok) }}" required min="0"></div>
            <div class="form-group"><label>Satuan</label><input type="text" name="satuan" value="{{ old('satuan', $item->satuan) }}" required></div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    @foreach(['Baik','Rusak Ringan','Rusak Berat','Tidak Layak'] as $k)
                    <option value="{{ $k }}" @selected(old('kondisi', $item->kondisi)===$k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>Lokasi</label><input type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}" required></div>
            <div class="form-group"><label>Tgl Pendataan</label><input type="date" name="tgl_pendataan" value="{{ old('tgl_pendataan', $item->tgl_pendataan?->format('Y-m-d')) }}" required></div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline">Batal</a>
            <button class="btn">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
