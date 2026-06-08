@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('subtitle', 'Tambahkan barang baru ke inventaris')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.items.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" required>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <select name="satuan" required>
                    <option value="">Pilih satuan</option>
                    <option value="unit" @selected(old('satuan')==='unit')>Unit</option>
                    <option value="kg" @selected(old('satuan')==='kg')>Kg</option>
                    <option value="liter" @selected(old('satuan')==='liter')>Liter</option>
                    <option value="pcs" @selected(old('satuan')==='pcs')>Pcs</option>
                    <option value="box" @selected(old('satuan')==='box')>Box</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok') }}" required min="0">
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" required>
                    <option value="">Pilih kondisi</option>
                    <option value="Baik" @selected(old('kondisi')==='Baik')>Baik</option>
                    <option value="Rusak Ringan" @selected(old('kondisi')==='Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(old('kondisi')==='Rusak Berat')>Rusak Berat</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline">Batal</a>
            <button class="btn">Simpan</button>
        </div>
    </form>
</div>
@endsection
