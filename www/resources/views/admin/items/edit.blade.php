@extends('layouts.app')
@section('title', 'Edit Barang')
@section('subtitle', 'Ubah data barang inventaris')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.items.update', $item->id_barang) }}">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" required>
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
            </div>
            <div class="form-group full">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $item->stok) }}" required min="0">
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    <option value="">Pilih kondisi</option>
                    <option value="Baik" @selected(old('kondisi', $item->kondisi)==='Baik')>Baik</option>
                    <option value="Rusak Ringan" @selected(old('kondisi', $item->kondisi)==='Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(old('kondisi', $item->kondisi)==='Rusak Berat')>Rusak Berat</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="3">{{ old('keterangan', $item->keterangan) }}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline">Batal</a>
            <button class="btn">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
