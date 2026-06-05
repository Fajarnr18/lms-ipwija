@extends('layouts.app')
@section('title', 'Daftar Alat')

@section('content')
<div class="header-bar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div></div>
    <a href="{{ route('admin.tools.create') }}" class="btn btn-sm">+ Tambah Alat</a>
</div>

<form method="GET" action="{{ route('admin.tools.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama, kode, atau kategori" value="{{ request('search') }}" style="min-width:220px">
        </div>
        <div class="toolbar-item">
            <label>Status</label>
            <select name="status_alat" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Tersedia" @selected(request('status_alat') === 'Tersedia')>Tersedia</option>
                <option value="Dipinjam" @selected(request('status_alat') === 'Dipinjam')>Dipinjam</option>
                <option value="Rusak" @selected(request('status_alat') === 'Rusak')>Rusak</option>
                <option value="Dalam Perbaikan" @selected(request('status_alat') === 'Dalam Perbaikan')>Dalam Perbaikan</option>
            </select>
        </div>
        <div style="display:flex;gap:.375rem;align-items:end">
            <button type="submit" class="btn btn-sm">Cari</button>
            <a href="{{ route('admin.tools.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </div>
    </div>
</form>

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Stok Total</th>
                    <th>Stok Tersedia</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tools as $tool)
                    <tr>
                        <td style="font-weight:500">{{ $tool->kode_alat }}</td>
                        <td>{{ $tool->nama_alat }}</td>
                        <td>{{ $tool->kategori }}</td>
                        <td>{{ $tool->stok_total }}</td>
                        <td>{{ $tool->stok_tersedia }}</td>
                        <td>
                            <span class="badge badge-@php
                                echo match($tool->status_alat) {
                                    'Tersedia' => 'tersedia',
                                    'Dipinjam' => 'dipinjam',
                                    'Rusak' => 'rusak',
                                    'Dalam Perbaikan' => 'perbaikan',
                                    default => 'tersedia',
                                };
                            @endphp">{{ $tool->status_alat }}</span>
                        </td>
                        <td>{{ $tool->lokasi }}</td>
                        <td style="text-align:center">
                            <div class="action-group" style="justify-content:center">
                                <a href="{{ route('admin.tools.edit', $tool->id_alat) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form method="POST" action="{{ route('admin.tools.destroy', $tool->id_alat) }}" onsubmit="return confirmAction('Hapus alat ini?')" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2rem;color:#64748b">
                            Tidak ada alat ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($tools->hasPages())
        <div class="pagination">
            {{ $tools->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
