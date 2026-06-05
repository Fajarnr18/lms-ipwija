@extends('layouts.app')
@section('title', 'Manajemen Barang')

@section('content')
<a href="{{ route('admin.items.create') }}" class="btn btn-sm mb-3">+ Tambah Barang</a>

<form method="GET" action="{{ route('admin.items.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama/kode/kategori" value="{{ request('search') }}" style="min-width:200px">
        </div>
        <div class="toolbar-item">
            <label>Kondisi</label>
            <select name="kondisi" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Baik" @selected(request('kondisi')==='Baik')>Baik</option>
                <option value="Rusak Ringan" @selected(request('kondisi')==='Rusak Ringan')>Rusak Ringan</option>
                <option value="Rusak Berat" @selected(request('kondisi')==='Rusak Berat')>Rusak Berat</option>
                <option value="Tidak Layak" @selected(request('kondisi')==='Tidak Layak')>Tidak Layak</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div class="card">
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Satuan</th><th>Kondisi</th><th>Lokasi</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td><span class="badge {{ $item->kondisi === 'Baik' ? 'badge-tersedia' : 'badge-rusak' }}">{{ $item->kondisi }}</span></td>
                    <td>{{ $item->lokasi }}</td>
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
                <tr><td colspan="8" style="text-align:center;padding:2rem;color:#64748b">Tidak ada barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())<div class="pagination">{{ $items->links() }}</div>@endif
</div>
@endsection
