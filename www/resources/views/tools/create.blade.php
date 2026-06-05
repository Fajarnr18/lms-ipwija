@extends('layouts.app')
@section('title', 'Tambah Alat')

@section('content')
<a href="{{ route('admin.tools.index') }}" class="btn btn-outline btn-sm mb-3">&larr; Kembali</a>

@if ($errors->any())
<div class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <p style="margin:.125rem 0">{{ $error }}</p>
    @endforeach
</div>
@endif

<div class="card">
    <form method="POST" action="{{ route('admin.tools.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Kode Alat</label>
                <input type="text" name="kode_alat" value="{{ old('kode_alat') }}" required placeholder="ex: AL-001">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">Pilih kategori</option>
                    <option value="Elektronik" @selected(old('kategori') === 'Elektronik')>Elektronik</option>
                    <option value="Mekanik" @selected(old('kategori') === 'Mekanik')>Mekanik</option>
                    <option value="Kimia" @selected(old('kategori') === 'Kimia')>Kimia</option>
                    <option value="Biologi" @selected(old('kategori') === 'Biologi')>Biologi</option>
                    <option value="Fisika" @selected(old('kategori') === 'Fisika')>Fisika</option>
                    <option value="Komputer" @selected(old('kategori') === 'Komputer')>Komputer</option>
                    <option value="Lainnya" @selected(old('kategori') === 'Lainnya')>Lainnya</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required placeholder="Nama lengkap alat">
            </div>
            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required placeholder="Deskripsi alat">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-group">
                <label>Stok Total</label>
                <input type="number" name="stok_total" value="{{ old('stok_total') }}" required min="0">
            </div>
            <div class="form-group">
                <label>Stok Tersedia</label>
                <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia') }}" required min="0">
            </div>
            <div class="form-group">
                <label>Status Alat</label>
                <select name="status_alat" required>
                    <option value="Tersedia" @selected(old('status_alat') === 'Tersedia')>Tersedia</option>
                    <option value="Dipinjam" @selected(old('status_alat') === 'Dipinjam')>Dipinjam</option>
                    <option value="Rusak" @selected(old('status_alat') === 'Rusak')>Rusak</option>
                    <option value="Dalam Perbaikan" @selected(old('status_alat') === 'Dalam Perbaikan')>Dalam Perbaikan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" required placeholder="ex: Lab A-01">
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.tools.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn">Simpan</button>
        </div>
    </form>
</div>
@endsection
