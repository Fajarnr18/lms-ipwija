@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 16px">Edit Profil</h2>

    <form method="POST" action="{{ route('dosen.profile.update') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group full"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" required></div>
            <div class="form-group"><label>NIM / NUPTK</label><input type="text" value="{{ auth()->user()->nim }}" disabled></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required></div>
            <div class="form-group"><label>Program Studi</label><input type="text" name="program_studi" value="{{ old('program_studi', auth()->user()->program_studi) }}" required></div>
            <div class="form-group"><label>Telepon</label><input type="text" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}"></div>
            <div class="form-group full"><label>Alamat</label><textarea name="alamat">{{ old('alamat', auth()->user()->alamat) }}</textarea></div>
        </div>
        <div class="form-actions"><button class="btn">Simpan Profil</button></div>
    </form>
</div>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 16px">Ganti Password</h2>
    <form method="POST" action="{{ route('dosen.profile.update') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group full"><label>Password Saat Ini</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Password Baru</label><input type="password" name="password_baru" required minlength="8"></div>
            <div class="form-group"><label>Konfirmasi Password Baru</label><input type="password" name="konfirmasi_password_baru" required></div>
        </div>
        <div class="form-actions"><button class="btn">Ganti Password</button></div>
    </form>
</div>
@endsection
