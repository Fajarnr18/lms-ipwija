@extends('layouts.app')
@section('title', 'Manajemen Barang')
@section('subtitle', 'Kelola inventaris barang laboratorium')
@section('header-actions')
<a href="{{ route('admin.items.create') }}" class="btn btn-sm">+ Tambah Barang</a>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.items.index') }}">
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

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>
                        <span class="badge {{ $item->kondisi === 'Baik' ? 'badge-green' : ($item->kondisi === 'Rusak Ringan' ? 'badge-yellow' : 'badge-red') }}">
                            {{ $item->kondisi }}
                        </span>
                    </td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.items.edit', $item->id_barang) }}" class="btn btn-sm btn-outline">Edit</a>
                            <a href="{{ route('admin.items.mutation', $item->id_barang) }}" class="btn btn-sm btn-outline">Mutasi</a>
                            <form method="POST" action="{{ route('admin.items.destroy', $item->id_barang) }}" style="display:inline" onsubmit="return confirmAction('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">Tidak ada barang ditemukan.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="pagination">{{ $items->links() }}</div>
    @endif
</div>
@endsection
