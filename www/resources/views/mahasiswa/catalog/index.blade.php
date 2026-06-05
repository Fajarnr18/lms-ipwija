@extends('layouts.app')
@section('title', 'Katalog Alat')

@section('content')
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('mhs.catalog.index') }}">
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

<style>
.tool-card{background:#fff;border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;border:1px solid #f1f5f9;transition:all .25s}
.tool-card:hover{border-color:#cbd5e1;box-shadow:0 8px 24px rgba(0,0,0,.06);transform:translateY(-3px)}
@media(prefers-color-scheme:dark){.tool-card{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}.tool-card:hover{box-shadow:0 8px 24px rgba(99,102,241,.2),0 0 0 1px rgba(99,102,241,.3)}}
.tool-card .tool-badge{display:inline-block;padding:2px 8px;border-radius:6px;font-size:.68rem;font-weight:600;background:#eef2ff;color:#4f46e5;margin-bottom:.5rem;align-self:flex-start}
.tool-card h3{font-size:1rem;font-weight:600;margin:0 0 .25rem;color:#0f172a}
@media(prefers-color-scheme:dark){.tool-card h3{color:#ededec}}
.tool-card .meta{font-size:.78rem;color:#64748b;margin:0 0 .25rem}
@media(prefers-color-scheme:dark){.tool-card .meta{color:#a1a09a}}
.tool-card .desc{font-size:.8rem;color:#64748b;margin:0 0 .75rem;flex:1;line-height:1.4}
@media(prefers-color-scheme:dark){.tool-card .desc{color:#a1a09a}}
.tool-card .qty-add{display:flex;gap:.25rem;align-items:center}
.tool-card .qty-add input{width:56px;text-align:center}
.tool-card .stok-habis{color:#dc2626;font-size:.8rem;font-weight:500}
</style>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
    @forelse($tools as $tool)
    <div class="tool-card">
        <span class="tool-badge">{{ $tool->kategori }}</span>
        <h3>{{ $tool->nama_alat }}</h3>
        <div class="meta">{{ $tool->kode_alat }} — {{ $tool->stok_tersedia }} tersedia</div>
        <p class="desc">{{ Str::limit($tool->deskripsi, 100) }}</p>
        <div style="margin-top:auto">
            @if($tool->stok_tersedia > 0)
            <form method="POST" action="{{ route('mhs.cart.add', $tool->id_alat) }}">
                @csrf
                <input type="hidden" name="id_alat" value="{{ $tool->id_alat }}">
                <label style="font-size:.75rem;font-weight:500;color:#64748b;display:block;margin-bottom:.3rem">Jumlah</label>
                <div class="qty-add">
                    <input type="number" name="jumlah" value="1" min="1" max="{{ $tool->stok_tersedia }}">
                    <button class="btn btn-sm">+ Keranjang</button>
                </div>
            </form>
            @else
            <span class="stok-habis">Stok Habis</span>
            @endif
        </div>
    </div>
    @empty
    <p style="color:#64748b;grid-column:1/-1;text-align:center;padding:2rem">Tidak ada alat tersedia.</p>
    @endforelse
</div>
@if($tools->hasPages())<div class="pagination">{{ $tools->appends(request()->query())->links() }}</div>@endif
@endsection
