@extends('layouts.app')
@section('title', 'Profil Dosen')
@section('header-actions')
<div style="text-align:right">
    <div style="font-size:12px; font-weight:700; color:#111827;">{{ auth()->user()->nama_lengkap }}</div>
    <div style="font-size:11px; color:#6B7280;">{{ auth()->user()->email }}</div>
</div>
<div style="width:36px; height:36px; border-radius:50%; background:#DBEAFE; color:#1E3A8A; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; margin-left:12px;">
    {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}
</div>
@endsection

@section('content')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }
    .profile-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }
    .profile-header-bg {
        height: 100px;
        background: #111827;
    }
    .profile-avatar-container {
        display: flex;
        justify-content: center;
        margin-top: -40px;
        margin-bottom: 16px;
        position: relative;
    }
    .upload-btn {
        position: absolute;
        bottom: 0;
        right: 50%;
        transform: translateX(35px);
        width: 24px;
        height: 24px;
        background: #1D4ED8;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        transition: background 0.2s;
    }
    .upload-btn:hover {
        background: #1E40AF;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #E5E7EB;
        border: 4px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: #6B7280;
        overflow: hidden;
    }
    .profile-info {
        text-align: center;
        padding: 0 24px 24px;
        border-bottom: 1px solid #E5E7EB;
    }
    .profile-name {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }
    .profile-role {
        font-size: 13px;
        color: #6B7280;
    }
    .profile-details {
        padding: 24px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 13px;
    }
    .detail-label {
        color: #6B7280;
    }
    .detail-value {
        font-weight: 600;
        color: #111827;
    }
    
    .status-card {
        background: #F8FAFC;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 24px 24px;
    }
    
    .section-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }
    .section-title {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title svg {
        color: #6B7280;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
    }
    .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
        color: #111827;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus {
        outline: none;
        border-color: #1D4ED8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
    }
    .form-input.readonly {
        background: #F3F4F6;
        color: #6B7280;
        cursor: not-allowed;
    }
    
    .radio-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .radio-option:hover {
        border-color: #9CA3AF;
        background: #F9FAFB;
    }
    .radio-option:has(input:checked) {
        border-color: #1D4ED8;
        background: #EFF6FF;
    }
    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #1D4ED8;
        cursor: pointer;
    }
    .radio-label {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
        flex: 1;
    }
    .radio-option:has(input:checked) .radio-label {
        color: #1E3A8A;
        font-weight: 600;
    }
    
    .alert-box {
        background: #EFF6FF;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }
    .alert-icon {
        color: #2563EB;
        flex-shrink: 0;
    }
    .alert-text {
        font-size: 13px;
        color: #1E3A8A;
        line-height: 1.5;
    }
    
    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 32px;
    }
    .btn-cancel {
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #4B5563;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    .btn-cancel:hover {
        color: #111827;
    }
    .btn-save {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        background: #1D4ED8;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-save:hover {
        background: #1E40AF;
    }

    .error-text {
        color: #DC2626;
        font-size: 12px;
        margin-top: 4px;
    }
</style>

@if(session('success'))
<div style="background:#DCFCE7; color:#16A34A; padding:12px 16px; border-radius:8px; margin-bottom:24px; font-size:14px; font-weight:500;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:#FEE2E2; color:#B91C1C; padding:12px 16px; border-radius:8px; margin-bottom:24px; font-size:14px; font-weight:500;">
    Terdapat kesalahan pada data yang diinput:
    <ul style="margin-top: 8px; margin-left: 16px;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('dosen.profil.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="profile-grid">
        <div class="left-col">
            <div class="profile-card">
                <div class="profile-header-bg"></div>
                <div class="profile-avatar-container">
                    <div class="profile-avatar" id="avatar-preview-container">
                        @if(auth()->user()->foto_profil)
                            <img id="avatar-preview" src="{{ Storage::url(auth()->user()->foto_profil) }}" style="width:100%; height:100%; object-fit:cover;" alt="Foto Profil">
                        @else
                            <span id="avatar-initials">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}</span>
                            <img id="avatar-preview" src="" style="width:100%; height:100%; object-fit:cover; display:none;" alt="Foto Profil">
                        @endif
                    </div>
                    <label class="upload-btn" for="foto_profil" title="Upload Foto Profil">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    </label>
                    <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/jpg" style="display:none;" onchange="previewImage(event)">
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->nama_lengkap }}</div>
                    <div class="profile-role">Dosen Aktif</div>
                </div>
                
                <div class="profile-details">
                    <div class="detail-row">
                        <span class="detail-label">NUPTK</span>
                        <span class="detail-value">{{ auth()->user()->nim }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Program Studi</span>
                        <span class="detail-value">{{ auth()->user()->program_studi ?? '-' }}</span>
                    </div>
                </div>
                
                <div class="status-card">
                    <div style="width:24px; height:24px; background:#2563EB; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <div style="font-size:12px; font-weight:700; color:#1E3A8A;">Status Terverifikasi</div>
                        <div style="font-size:11px; color:#6B7280; margin-top:2px;">Update: {{ auth()->user()->updated_at ? auth()->user()->updated_at->translatedFormat('d M Y') : '-' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="section-card">
                <h2 class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Jenis Notifikasi
                </h2>
                <label class="radio-option">
                    <input type="radio" name="jenis_notifikasi" value="Email" {{ (auth()->user()->jenis_notifikasi ?? 'Email') === 'Email' ? 'checked' : '' }}>
                    <span class="radio-label">Email</span>
                </label>
                <label class="radio-option">
                    <input type="radio" name="jenis_notifikasi" value="Whatsapp" {{ (auth()->user()->jenis_notifikasi ?? 'Email') === 'Whatsapp' ? 'checked' : '' }}>
                    <span class="radio-label">Whatsapp</span>
                </label>
                <div style="font-size:11px; color:#6B7280; line-height:1.5; margin-top:16px;">
                    Atur notifikasi yang dikirimkan, pilih whatsapp atau email untuk tujuan pesan !
                </div>
            </div>
        </div>
        
        <div class="right-col">
            <div class="section-card">
                <h2 class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Edit Informasi Kontak
                </h2>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-input readonly" name="nama_lengkap" value="{{ auth()->user()->nama_lengkap }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>NUPTK</label>
                        <input type="text" class="form-input readonly" value="{{ auth()->user()->nim }}" readonly disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Dosen</label>
                        <input type="email" class="form-input" name="email" value="{{ old('email', auth()->user()->email) }}" required autocomplete="off">
                        @error('email')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor Handphone</label>
                        <input type="text" class="form-input" name="no_whatsapp" value="{{ old('no_whatsapp', auth()->user()->no_whatsapp) }}">
                        @error('no_whatsapp')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            
            <div class="section-card">
                <h2 class="section-title" style="border-bottom:1px solid #E5E7EB; padding-bottom:16px; margin-bottom:24px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Ubah Password
                </h2>
                
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" class="form-input" name="password" placeholder="Masukan password lama...">
                    @error('password')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-grid" style="margin-top:16px;">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" class="form-input" name="password_baru" placeholder="Masukan password baru...">
                        @error('password_baru')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" class="form-input" name="konfirmasi_password_baru" placeholder="Konfirmasi password baru...">
                        @error('konfirmasi_password_baru')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="alert-box">
                    <div class="alert-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="alert-text">
                        Password harus terdiri dari minimal 8 karakter, mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol untuk keamanan maksimal.
                    </div>
                </div>
            </div>
            
            <div class="actions">
                <button type="button" class="btn-cancel" onclick="window.location.reload()">Batal</button>
                <button type="submit" class="btn-save">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('avatar-preview');
        const initials = document.getElementById('avatar-initials');
        
        output.src = reader.result;
        output.style.display = 'block';
        
        if (initials) {
            initials.style.display = 'none';
        }
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
        // Submit form otomatis setelah pilih foto
        event.target.closest('form').submit();
    }
}
</script>
@endsection
