@extends('layouts.app')
@section('title', 'Detail Alat')
@section('subtitle', $tool->nama_alat)

@section('header-actions')
<a href="{{ route('admin.alat.index') }}" class="btn btn-sm btn-outline">&larr; Kembali</a>
<a href="{{ route('admin.alat.edit', $tool->id_alat) }}" class="btn btn-sm">Edit Alat</a>
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
@endsection
