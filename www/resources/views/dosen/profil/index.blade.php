@extends('layouts.app')
@section('title', 'Profil Saya')
@section('subtitle', 'Kelola informasi akun Anda')

@section('content')
<div class="card" style="margin-bottom:16px">
    <h2 style="font-size:14px;font-weight:600;margin:0 0 4px;color:#1A1A2E">Informasi Profil</h2>
    <p style="font-size:12px;color:#6B7280;margin-bottom:16px">Data diri Anda sebagai pengguna sistem</p>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Nama Lengkap</div>
            <div class="value">{{ auth()->user()->nama_lengkap }}</div>
        </div>
        <div class="detail-item">
            <div class="label">NUPTK</div>
            <div class="value">{{ auth()->user()->nim }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Email</div>
            <div class="value">{{ auth()->user()->email }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Program Studi</div>
            <div class="value">{{ auth()->user()->program_studi }}</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <h2 style="font-size:14px;font-weight:600;margin:0 0 16px">Edit Profil</h2>
    <form method="POST" action="{{ route('dosen.profil.update') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group full">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" required>
                @error('nama_lengkap')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>NUPTK</label>
                <input type="text" value="{{ auth()->user()->nim }}" disabled style="background:#F9FAFB;color:#6B7280">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group full">
                <label>Program Studi</label>
                <input type="text" name="program_studi" value="{{ old('program_studi', auth()->user()->program_studi) }}" required>
                @error('program_studi')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions"><button class="btn">Simpan Profil</button></div>
    </form>
</div>

<div class="card">
    <h2 style="font-size:14px;font-weight:600;margin:0 0 16px">Ganti Password</h2>
    <form method="POST" action="{{ route('dosen.profil.update') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group full">
                <label>Password Saat Ini</label>
                <input type="password" name="password" required>
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" required minlength="8">
                @error('password_baru')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi_password_baru" required>
                @error('konfirmasi_password_baru')<div class="error-text">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions"><button class="btn">Ganti Password</button></div>
    </form>
</div>
@endsection
