@extends('layouts.app')
@section('title', 'Katalog Alat')
@section('subtitle', '')
@section('content')
<style>
.catalog-search-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
}
.catalog-search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 420px;
    width: 100%;
    background: #F9FAFB;
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 0 14px;
    transition: all .2s;
}
.catalog-search-box:focus-within {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    background: #fff;
}
.catalog-search-box input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 0;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    outline: none;
    color: #1A1A2E;
}
.catalog-search-box input::placeholder { color: #9CA3AF; }
.catalog-top-left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.catalog-filter-select {
    padding: 10px 14px;
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: #1A1A2E;
    background: #F9FAFB;
    outline: none;
    cursor: pointer;
    transition: all .2s;
    min-width: 160px;
}
.catalog-filter-select:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    background: #fff;
}
.catalog-hero {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}
.catalog-hero-left h1 {
    font-size: 28px;
    font-weight: 800;
    color: #1A1A2E;
    letter-spacing: -.03em;
    margin: 0 0 4px;
}
.catalog-hero-left p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
    line-height: 1.5;
}
.catalog-hero-right {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}
.catalog-filter-box {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1.5px solid #E5E7EB;
    background: #fff;
    color: #6B7280;
    font-family: 'Inter', sans-serif;
}
.catalog-filter-box:hover {
    border-color: #3B82F6;
    color: #3B82F6;
    background: #EEF2FF;
}
.catalog-filter-box.active {
    border-color: #3B82F6;
    background: #3B82F6;
    color: #fff;
}
.catalog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}
.catalog-card {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    overflow: hidden;
    transition: all .2s;
}
.catalog-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    border-color: #D1D5DB;
}
.catalog-card-img {
    width: 100%;
    height: 180px;
    background: #F3F4F6;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.catalog-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.catalog-card-img .no-img {
    color: #9CA3AF;
    font-size: 12px;
    text-align: center;
    padding: 20px;
}
.catalog-card-body {
    padding: 16px;
}
.catalog-card-body .card-top-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 4px;
}
.catalog-card-body .card-top-row h3 {
    font-size: 15px;
    font-weight: 600;
    color: #1A1A2E;
    margin: 0;
    line-height: 1.3;
    flex: 1;
    min-width: 0;
}
.catalog-card-body .code {
    font-size: 12px;
    color: #9CA3AF;
    margin-bottom: 8px;
}
.catalog-card-body .stock {
    font-size: 13px;
    font-weight: 500;
    color: #1E3A5F;
    margin-bottom: 14px;
}
.catalog-card-body .stock.out {
    color: #EF4444;
}
.catalog-card-actions {
    display: flex;
    gap: 8px;
}
.catalog-card-actions .btn-detail {
    flex: 1;
    padding: 8px 12px;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    background: #fff;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-family: 'Inter', sans-serif;
    transition: all .2s;
}
.catalog-card-actions .btn-detail:hover {
    border-color: #3B82F6;
    color: #3B82F6;
    background: #EEF2FF;
}
.catalog-card-actions .btn-cart-add {
    width: 36px;
    height: 36px;
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    transition: all .2s;
    flex-shrink: 0;
}
.catalog-card-actions .btn-cart-add:hover {
    border-color: #3B82F6;
    color: #fff;
    background: #3B82F6;
}

.catalog-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #E5E7EB;
}
.catalog-footer .total {
    font-size: 13px;
    color: #6B7280;
    font-weight: 500;
}
.catalog-footer .total strong {
    color: #1A1A2E;
}
</style>

<form method="GET" action="{{ route('dosen.katalog.index') }}" id="filterForm">
    <div class="catalog-search-wrap">
        <div class="catalog-top-left">
            <div class="catalog-search-box">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#9CA3AF;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Cari Alat Laboratorium" value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                <a href="{{ route('dosen.katalog.index') }}" style="color:#9CA3AF;text-decoration:none;padding:4px;display:flex">&times;</a>
                @endif
            </div>
            <select name="kategori" class="catalog-filter-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div class="catalog-hero-right">
            <a href="{{ route('dosen.katalog.index') }}" class="catalog-filter-box {{ !request('status_alat') ? 'active' : '' }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
                Semua
            </a>
            <a href="{{ route('dosen.katalog.index', array_merge(request()->except('status_alat', 'page'), ['status_alat' => 'TERSEDIA'])) }}" class="catalog-filter-box {{ request('status_alat') === 'TERSEDIA' ? 'active' : '' }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tersedia
            </a>
        </div>
    </div>

    <div class="catalog-hero">
        <div class="catalog-hero-left">
            <h1>Katalog</h1>
            <p>Telusuri dan pinjam alat laboratorium untuk mendukung riset anda</p>
        </div>
    </div>
</form>

<div class="catalog-grid">
    @forelse($tools as $tool)
    <div class="catalog-card">
        <div class="catalog-card-img">
            <img src="{{ $tool->foto_url }}" alt="{{ $tool->nama_alat }}">
        </div>
        <div class="catalog-card-body">
            <div class="card-top-row">
                <h3>{{ $tool->nama_alat }}</h3>
                @php
                $badgeClass = match($tool->status_alat) {
                    'TERSEDIA' => 'badge-green',
                    'MAINTENANCE', 'RUSAK' => 'badge-red',
                    default => 'badge-gray',
                };
                @endphp
                <span class="badge {{ $badgeClass }}" style="flex-shrink:0">{{ $tool->status_alat }}</span>
            </div>
            <div class="code">{{ $tool->kode_alat }} &middot; {{ $tool->kategori }}</div>
            <div class="stock {{ $tool->stok_tersedia < 1 ? 'out' : '' }}">
                {{ $tool->stok_tersedia > 0 ? $tool->stok_tersedia.' tersedia' : 'Stok Habis' }}
                @if($tool->status_alat === 'MAINTENANCE')
                <span style="color:#EF4444;font-size:11px;font-weight:400;margin-left:4px">(Maintenance)</span>
                @endif
            </div>
            <div class="catalog-card-actions">
                <a href="{{ route('dosen.katalog.show', $tool->id_alat) }}" class="btn-detail">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail
                </a>
                @if($tool->stok_tersedia > 0 && $tool->status_alat === 'TERSEDIA')
                @csrf
                <button type="button" class="btn-cart-add btn-add-cart" data-id="{{ $tool->id_alat }}" data-name="{{ $tool->nama_alat }}" title="Tambah ke keranjang">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </button>
                @else
                <button class="btn-cart-add" disabled style="opacity:.3;cursor:not-allowed;background:#F3F4F6;border-color:#E5E7EB" title="Tidak tersedia">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1;border:1px dashed #E5E7EB;border-radius:12px;background:#fff">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom:12px;color:#D1D5DB">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div style="font-size:14px;font-weight:500;color:#6B7280;margin-bottom:4px">Tidak ada alat ditemukan</div>
        <div style="font-size:12px;color:#9CA3AF">Coba ubah kata kunci atau filter pencarian</div>
    </div>
    @endforelse
</div>

<div class="catalog-footer">
    <div class="total">
        Total <strong>{{ $tools->total() }}</strong> Alat
    </div>
    @if($tools->hasPages())
    {{ $tools->appends(request()->query())->links() }}
    @endif
</div>

<style>
.cart-toast { position:fixed; bottom:24px; right:24px; background:#065F46; color:#fff; padding:12px 20px; border-radius:10px; font-size:13px; font-weight:500; font-family:'Inter',sans-serif; box-shadow:0 4px 16px rgba(0,0,0,.15); z-index:999; opacity:0; transform:translateY(20px); transition:all .3s; pointer-events:none; }
.cart-toast.show { opacity:1; transform:translateY(0); }
.cart-toast.error { background:#991B1B; }
</style>
<div id="cartToast" class="cart-toast"></div>

<script>
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-add-cart');
    if (!btn) return;
    var id = btn.dataset.id;
    var name = btn.dataset.name;
    fetch('{{ url("dosen/keranjang/tambah") }}/' + id, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'jumlah=1'
    })
    .then(function(r) { return r.json().then(function(d) { return {ok: r.ok, data: d}; }); })
    .then(function(res) {
        var toast = document.getElementById('cartToast');
        if (res.ok) {
            toast.textContent = name + ' ditambahkan ke keranjang';
            toast.className = 'cart-toast show';
            var badge = document.querySelector('.topbar-cart-count');
            if (badge) { badge.textContent = res.data.cart_count > 99 ? '99+' : res.data.cart_count; }
            else {
                var cartBtn = document.querySelector('.topbar-cart-btn');
                if (cartBtn && !cartBtn.querySelector('.topbar-cart-count')) {
                    var c = document.createElement('span');
                    c.className = 'topbar-cart-count';
                    c.textContent = res.data.cart_count;
                    cartBtn.appendChild(c);
                }
            }
        } else {
            toast.textContent = res.data.error || 'Gagal menambahkan';
            toast.className = 'cart-toast show error';
        }
        setTimeout(function() { toast.classList.remove('show'); }, 2500);
    })
    .catch(function() {
        var toast = document.getElementById('cartToast');
        toast.textContent = 'Terjadi kesalahan';
        toast.className = 'cart-toast show error';
        setTimeout(function() { toast.classList.remove('show'); }, 2500);
    });
});
</script>

@if(request('search') || request('kategori') || request('status_alat'))
<script>
document.querySelectorAll('.catalog-filter-box').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (this.getAttribute('href') === '{{ route('dosen.katalog.index') }}' && ({{ request('status_alat') ? 'true' : 'false' }} || {{ request('kategori') ? 'true' : 'false' }})) {
            document.getElementById('filterForm').action = this.getAttribute('href');
            document.getElementById('filterForm').submit();
            e.preventDefault();
        }
    });
});
</script>
@endif
@endsection
