@extends('layouts.app')
@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" placeholder="Cari kode atau nama barang..." style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
    </div>
</div>
@endsection
@section('title', '')
@section('subtitle', '')

@section('content')
<div style="margin-bottom:4px">
    <h2 style="font-size:20px;font-weight:700;color:#1A1A2E;margin:0">Detail Data barang</h2>
    <p style="font-size:13px;color:#6B7280;margin:4px 0 20px">Informasi lengkap mengenai aset laboratorium</p>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">
    <div class="card" style="padding:0">
        <div style="padding:20px">
            @if($item->foto_url)
            <div style="margin-bottom:16px;border-radius:8px;overflow:hidden;border:1px solid #E5E7EB;height:240px;background:#F9FAFB;display:flex;align-items:center;justify-content:center">
                <img src="{{ $item->foto_url }}" alt="{{ $item->nama_barang }}" style="width:100%;height:100%;object-fit:cover">
            </div>
            @endif
            <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1.5px solid #E5E7EB;margin-bottom:12px">
                <div>
                    <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Kode Barang</div>
                    <div style="font-size:15px;font-weight:700;color:#1A1A2E;margin-top:2px">{{ $item->kode_barang }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Tanggal Pendataan</div>
                    <div style="font-size:15px;font-weight:600;color:#1A1A2E;margin-top:2px">{{ $item->tgl_pendataan ? \Carbon\Carbon::parse($item->tgl_pendataan)->format('d/m/Y') : '-' }}</div>
                </div>
            </div>

            <div style="padding-bottom:12px;border-bottom:1.5px solid #E5E7EB;margin-bottom:12px">
                <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Nama Barang</div>
                <div style="font-size:15px;font-weight:600;color:#1A1A2E;margin-top:2px">{{ $item->nama_barang }}</div>
            </div>

            <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1.5px solid #E5E7EB;margin-bottom:12px">
                <div>
                    <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Kategori</div>
                    <div style="font-size:14px;font-weight:500;color:#1A1A2E;margin-top:2px">{{ $item->kategori ?? '-' }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Kondisi Barang</div>
                    <div style="margin-top:2px">
                        @php
                        $kBadge = match($item->kondisi) {
                            'Baik' => 'badge-green',
                            'Rusak Ringan' => 'badge-yellow',
                            'Rusak Berat', 'Tidak Layak' => 'badge-red',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $kBadge }}">{{ $item->kondisi }}</span>
                    </div>
                </div>
            </div>

            <div>
                <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px">Deskripsi Produk</div>
                <div style="font-size:14px;color:#4B5563;line-height:1.6;background:#F9FAFB;padding:12px 16px;border-radius:6px;border:1px solid #E5E7EB">
                    {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card" style="margin-bottom:12px;padding:20px;text-align:center">
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px">Stok Tersedia</div>
            <div style="font-size:36px;font-weight:800;color:#1E3A5F;letter-spacing:-.02em">{{ $item->stok }} <span style="font-size:16px;font-weight:500;color:#6B7280">{{ $item->satuan }}</span></div>
        </div>

        <div class="card" style="margin-bottom:20px;padding:20px">
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:8px">Lokasi Penyimpanan</div>
            <div style="display:flex;align-items:center;gap:10px">
                <svg width="18" height="18" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span style="font-size:15px;font-weight:600;color:#1A1A2E">{{ $item->lokasi ?? '-' }}</span>
            </div>
        </div>

        <a href="{{ route('admin.inventaris.edit', $item->id_barang) }}" class="btn" style="width:100%;padding:12px;background:#1E3A5F;color:#fff;justify-content:center;margin-bottom:8px">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Data Barang
        </a>

        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline" style="width:100%;padding:12px;justify-content:center">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>
</div>
@endsection