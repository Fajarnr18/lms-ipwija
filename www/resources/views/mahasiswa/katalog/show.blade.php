@extends('layouts.app')
@section('title', 'Detail Alat')
@section('subtitle', $tool->nama_alat)

@section('header-actions')
<a href="{{ route('katalog.index') }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
@endsection

@section('content')
<div class="card">
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Kode Alat</div>
            <div class="value">{{ $tool->kode_alat }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Nama Alat</div>
            <div class="value">{{ $tool->nama_alat }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Kategori</div>
            <div class="value">{{ $tool->kategori }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Status</div>
            <div class="value">
                @php
                $badgeClass = match($tool->status_alat) {
                    'TERSEDIA' => 'badge-green',
                    'MAINTENANCE' => 'badge-red',
                    default => 'badge-gray',
                };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $tool->status_alat }}</span>
            </div>
        </div>
        <div class="detail-item">
            <div class="label">Stok Total</div>
            <div class="value">{{ $tool->stok_total }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Stok Tersedia</div>
            <div class="value">{{ $tool->stok_tersedia }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Lokasi</div>
            <div class="value">{{ $tool->lokasi ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Deskripsi</div>
            <div class="value">{{ $tool->deskripsi ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Kondisi Fisik</div>
            <div class="value">{{ $tool->kondisi_fisik ?? '-' }}</div>
        </div>
        @if($tool->foto_alat)
        <div class="detail-item full">
            <div class="label">Foto Alat</div>
            <img src="{{ asset('storage/' . $tool->foto_alat) }}" alt="Foto {{ $tool->nama_alat }}" style="max-width:320px;border-radius:6px;margin-top:4px">
        </div>
        @endif
    </div>
</div>

@if($tool->status_alat === 'TERSEDIA' && $tool->stok_tersedia > 0)
<div style="margin-top:16px;text-align:right">
    <form method="POST" action="{{ route('keranjang.tambah', $tool->id_alat) }}" style="display:inline-flex;align-items:center;gap:8px">
        @csrf
        <label style="font-size:13px;font-weight:500;color:#374151">Jumlah:</label>
        <input type="number" name="jumlah" value="1" min="1" max="{{ $tool->stok_tersedia }}" style="width:70px;text-align:center;padding:8px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif;outline:none">
        <span style="font-size:12px;color:#6B7280">(max {{ $tool->stok_tersedia }})</span>
        <button type="submit" class="btn btn-sm" style="display:inline-flex;align-items:center;gap:6px">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah ke Keranjang
        </button>
    </form>
</div>
@endif
@endsection
