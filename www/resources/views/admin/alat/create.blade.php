@extends('layouts.app')
@section('title', 'Tambah Alat')
@section('subtitle', 'Tambahkan alat baru ke laboratorium')

@section('header-actions')
<a href="{{ route('admin.alat.index') }}" class="btn btn-outline btn-sm">Batal</a>
<button type="submit" form="form-alat" class="btn btn-sm">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
    Simpan Data
</button>
@endsection

@section('content')
<form id="form-alat" method="POST" action="{{ route('admin.alat.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">
        <div class="card">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                <div style="width:36px;height:36px;border-radius:8px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="18" height="18" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 style="font-size:15px;font-weight:600;color:#1A1A2E">Informasi Utama</h3>
            </div>
            <hr style="border:none;border-top:1px solid #E5E7EB;margin:0 0 20px">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group" style="margin-bottom:0">
                    <label>Kode Alat</label>
                    <input type="text" name="kode_alat" value="{{ old('kode_alat') }}" required placeholder="Contoh: AL-001">
                    @error('kode_alat')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label>Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}" required placeholder="Elektronik, Kimia, dll">
                    @error('kategori')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required>
                @error('nama_alat')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Deskripsi Alat</label>
                <textarea name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                <div class="form-group" style="margin-bottom:0">
                    <label>Lokasi (Rak/Lantai)</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" required placeholder="Rak A1">
                    @error('lokasi')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label>Stok Total</label>
                    <input type="number" name="stok_total" value="{{ old('stok_total', 1) }}" required min="0">
                    @error('stok_total')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label>Stok Tersedia</label>
                    <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia', 1) }}" required min="0">
                    @error('stok_tersedia')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px">
            <div class="card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                    <div style="width:36px;height:36px;border-radius:8px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="18" height="18" fill="none" stroke="#8B5CF6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:600;color:#1A1A2E">Status & Kondisi</h3>
                </div>
                <hr style="border:none;border-top:1px solid #E5E7EB;margin:0 0 20px">

                <div class="form-group">
                    <label>Status Operasional</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:6px">
                        <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid #E5E7EB;border-radius:6px;cursor:pointer;transition:all .15s;font-size:13px;font-weight:500">
                            <input type="radio" name="status_alat" value="TERSEDIA" {{ old('status_alat', 'TERSEDIA') === 'TERSEDIA' ? 'checked' : '' }} style="width:auto">
                            Tersedia
                        </label>
                        <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid #E5E7EB;border-radius:6px;cursor:pointer;transition:all .15s;font-size:13px;font-weight:500">
                            <input type="radio" name="status_alat" value="MAINTENANCE" {{ old('status_alat') === 'MAINTENANCE' ? 'checked' : '' }} style="width:auto">
                            Maintenance
                        </label>
                        <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid #E5E7EB;border-radius:6px;cursor:pointer;transition:all .15s;font-size:13px;font-weight:500">
                            <input type="radio" name="status_alat" value="RUSAK" {{ old('status_alat') === 'RUSAK' ? 'checked' : '' }} style="width:auto">
                            Rusak
                        </label>
                    </div>
                    @error('status_alat')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label>Kondisi Fisik</label>
                    <select name="kondisi_fisik">
                        <option value="">Pilih kondisi</option>
                        <option value="Baik" @selected(old('kondisi_fisik')==='Baik')>Baik</option>
                        <option value="Rusak Ringan" @selected(old('kondisi_fisik')==='Rusak Ringan')>Rusak Ringan</option>
                        <option value="Rusak Berat" @selected(old('kondisi_fisik')==='Rusak Berat')>Rusak Berat</option>
                    </select>
                    @error('kondisi_fisik')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                    <div style="width:36px;height:36px;border-radius:8px;background:#FFFBEB;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="18" height="18" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:600;color:#1A1A2E">Media Alat</h3>
                </div>
                <hr style="border:none;border-top:1px solid #E5E7EB;margin:0 0 20px">

                <div class="form-group" style="margin-bottom:0">
                    <label>Foto Alat</label>
                    <input type="file" name="foto_alat" accept="image/png,image/jpeg,image/jpg,image/webp" style="padding:6px 12px">
                    @error('foto_alat')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
