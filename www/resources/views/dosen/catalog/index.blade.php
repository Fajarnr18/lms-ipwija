@extends('layouts.app')
@section('title', 'Katalog Alat')

@section('content')
<form method="GET" action="{{ route('dosen.catalog.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama/kode alat..." value="{{ request('search') }}" style="min-width:200px">
        </div>
        <div class="toolbar-item">
            <label>Kategori</label>
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($kategoris as $k)
                <option value="{{ $k }}" @selected(request('kategori')===$k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
    @forelse($tools as $tool)
    <div class="card" style="display:flex;flex-direction:column">
        <span class="badge badge-blue" style="align-self:flex-start;margin-bottom:8px">{{ $tool->kategori }}</span>
        <h3 style="font-size:1rem;font-weight:600;margin:0 0 4px;color:#1A1A2E">{{ $tool->nama_alat }}</h3>
        <div style="font-size:12px;color:#6B7280;margin-bottom:4px">{{ $tool->kode_alat }} &mdash; {{ $tool->stok_tersedia }} tersedia</div>
        <p style="font-size:13px;color:#6B7280;margin:0 0 12px;flex:1;line-height:1.4">{{ Str::limit($tool->deskripsi, 100) }}</p>
        <div style="margin-top:auto">
            @if($tool->stok_tersedia > 0)
            <form method="POST" action="{{ route('dosen.cart.add', $tool->id_alat) }}">
                @csrf
                <input type="hidden" name="id_alat" value="{{ $tool->id_alat }}">
                <label style="font-size:11px;font-weight:500;color:#6B7280;display:block;margin-bottom:4px">Jumlah</label>
                <div style="display:flex;gap:4px;align-items:center">
                    <input type="number" name="jumlah" value="1" min="1" max="{{ $tool->stok_tersedia }}" style="width:56px;text-align:center;padding:6px 8px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif">
                    <button class="btn btn-sm">+ Keranjang</button>
                </div>
            </form>
            @else
            <span style="color:#EF4444;font-size:13px;font-weight:500">Stok Habis</span>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1">Tidak ada alat tersedia.</div>
    @endforelse
</div>
@if($tools->hasPages())<div class="pagination">{{ $tools->appends(request()->query())->links() }}</div>@endif
@endsection
