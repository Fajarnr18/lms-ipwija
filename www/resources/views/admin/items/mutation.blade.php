@extends('layouts.app')
@section('title', 'Mutasi Stok')

@section('content')
<a href="{{ route('admin.items.index') }}" class="btn btn-outline btn-sm mb-3">&larr; Kembali</a>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 .25rem">{{ $item->nama_barang }}</h2>
    <p style="font-size:.875rem;color:#64748b;margin:0 0 1rem">Kode: {{ $item->kode_barang }} | Stok Saat Ini: <strong>{{ $item->stok }}</strong> {{ $item->satuan }}</p>

    <form method="POST" action="{{ route('admin.items.mutation-store', $item->id_barang) }}" class="form-horizontal" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid #e2e8f0">
        @csrf
        <div class="toolbar-item">
            <label>Tipe Mutasi</label>
            <select name="tipe_mutasi" required>
                <option value="Masuk">Masuk</option>
                <option value="Keluar">Keluar</option>
                <option value="Penyesuaian">Penyesuaian</option>
            </select>
        </div>
        <div class="toolbar-item">
            <label>Jumlah</label>
            <input type="number" name="jumlah" required min="1" style="width:100px">
        </div>
        <div class="toolbar-item" style="flex:1;min-width:200px">
            <label>Keterangan</label>
            <input type="text" name="keterangan" required placeholder="Alasan mutasi...">
        </div>
        <button class="btn btn-sm">Simpan Mutasi</button>
    </form>

    <h3 style="font-size:.875rem;font-weight:600;margin:0 0 .75rem">Riwayat Mutasi</h3>
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>Waktu</th><th>Tipe</th><th>Jumlah</th><th>Stok Sebelum</th><th>Stok Sesudah</th><th>Keterangan</th><th>Oleh</th></tr></thead>
            <tbody>
                @forelse($mutations as $m)
                <tr>
                    <td>{{ $m->time_stamp?->format('d/m/Y H:i') }}</td>
                    <td><span class="badge {{ $m->tipe_mutasi === 'Masuk' ? 'badge-tersedia' : ($m->tipe_mutasi === 'Keluar' ? 'badge-dipinjam' : 'badge-pending') }}">{{ $m->tipe_mutasi }}</span></td>
                    <td>{{ $m->jumlah }}</td>
                    <td>{{ $m->stok_sebelum }}</td>
                    <td>{{ $m->stok_sesudah }}</td>
                    <td>{{ $m->keterangan }}</td>
                    <td>{{ $m->admin?->nama_lengkap }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:1.5rem;color:#64748b">Belum ada mutasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
