@extends('layouts.app')
@section('title', 'Edit Alat')

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
    <form method="POST" action="{{ route('admin.tools.update', $tool->id_alat) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label>Kode Alat</label>
                <input type="text" name="kode_alat" value="{{ old('kode_alat', $tool->kode_alat) }}" required placeholder="ex: AL-001">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach(['Elektronik','Mekanik','Kimia','Biologi','Fisika','Komputer','Lainnya'] as $kat)
                    <option value="{{ $kat }}" @selected(old('kategori', $tool->kategori) === $kat)>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group full">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat', $tool->nama_alat) }}" required placeholder="Nama lengkap alat">
            </div>
            <div class="form-group full">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required placeholder="Deskripsi alat">{{ old('deskripsi', $tool->deskripsi) }}</textarea>
            </div>
            <div class="form-group">
                <label>Stok Total</label>
                <input type="number" name="stok_total" value="{{ old('stok_total', $tool->stok_total) }}" required min="0">
            </div>
            <div class="form-group">
                <label>Stok Tersedia</label>
                <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia', $tool->stok_tersedia) }}" required min="0">
            </div>
            <div class="form-group">
                <label>Status Alat</label>
                <select name="status_alat" required>
                    @foreach(['Tersedia','Dipinjam','Rusak','Dalam Perbaikan'] as $st)
                    <option value="{{ $st }}" @selected(old('status_alat', $tool->status_alat) === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $tool->lokasi) }}" required placeholder="ex: Lab A-01">
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.tools.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
