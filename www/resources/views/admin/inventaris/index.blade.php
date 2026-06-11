@extends('layouts.app')
@section('title', 'Manajemen Barang')
@section('subtitle', 'Kelola inventaris barang laboratorium')

@section('header-actions')
<a href="{{ route('admin.inventaris.create') }}" class="btn btn-sm">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Barang
</a>
@endsection

@section('content')
<div class="tabs">
    <a href="{{ route('admin.inventaris.index') }}" class="{{ !request('tab') || request('tab') === 'daftar' ? 'active' : '' }}">Daftar Barang</a>
    <a href="{{ route('admin.inventaris.index', ['tab' => 'mutasi']) }}" class="{{ request('tab') === 'mutasi' ? 'active' : '' }}">Log Mutasi</a>
</div>

<form method="GET" action="{{ route('admin.inventaris.index') }}">
    <input type="hidden" name="tab" value="{{ request('tab', 'daftar') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama atau kode barang..." value="{{ request('search') }}" style="min-width:200px">
        </div>
        <div class="toolbar-item">
            <label>Kondisi</label>
            <select name="kondisi" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Baik" @selected(request('kondisi')==='Baik')>Baik</option>
                <option value="Rusak Ringan" @selected(request('kondisi')==='Rusak Ringan')>Rusak Ringan</option>
                <option value="Rusak Berat" @selected(request('kondisi')==='Rusak Berat')>Rusak Berat</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

@if(request('tab') === 'mutasi')
<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Barang</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutations ?? [] as $m)
                <tr>
                    <td style="white-space:nowrap">{{ $m->time_stamp?->format('d/m/Y H:i') }}</td>
                    <td>{{ $m->item?->nama_barang }}</td>
                    <td>
                        <span class="badge {{ $m->tipe_mutasi === 'Masuk' ? 'badge-green' : ($m->tipe_mutasi === 'Keluar' ? 'badge-red' : 'badge-yellow') }}">
                            {{ $m->tipe_mutasi }}
                        </span>
                    </td>
                    <td>{{ $m->jumlah }}</td>
                    <td>{{ $m->stok_sebelum }}</td>
                    <td>{{ $m->stok_sesudah }}</td>
                    <td>{{ $m->keterangan }}</td>
                    <td>{{ $m->admin?->nama_lengkap ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8"><div class="empty-state">Belum ada mutasi.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($mutations) && $mutations->hasPages())
    <div class="pagination">{{ $mutations->appends(request()->query())->links() }}</div>
    @endif
</div>
@else
<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Kondisi</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>
                        <span class="badge {{ $item->kondisi === 'Baik' ? 'badge-green' : ($item->kondisi === 'Rusak Ringan' ? 'badge-yellow' : 'badge-red') }}">
                            {{ $item->kondisi }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('admin.inventaris.edit', $item->id_barang) }}" class="btn btn-outline btn-sm">Edit</a>
                            <a href="{{ route('admin.inventaris.mutasi', $item->id_barang) }}" class="btn btn-outline btn-sm">Mutasi</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada barang ditemukan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="pagination">{{ $items->appends(request()->query())->links() }}</div>
    @endif
</div>
@endif
@endsection
