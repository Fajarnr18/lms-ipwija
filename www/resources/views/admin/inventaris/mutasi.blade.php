@extends('layouts.app')
@section('title', 'Mutasi Stok')
@section('subtitle', 'Catat pergerakan stok barang')

@section('content')
<div class="card" style="margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="flex:1;min-width:200px">
            <div style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $item->nama_barang }}</div>
            <div style="font-size:12px;color:#6B7280;margin-top:2px">Kode: {{ $item->kode_barang }} &middot; Kategori: {{ $item->kategori ?? '-' }}</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Stok Saat Ini</div>
            <div style="font-size:22px;font-weight:700;color:#1E3A5F">{{ $item->stok }} <span style="font-size:13px;font-weight:500;color:#6B7280">{{ $item->satuan }}</span></div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="POST" action="{{ route('admin.inventaris.mutasi-store', $item->id_barang) }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
                <label>Tipe Mutasi</label>
                <select name="tipe_mutasi" required>
                    <option value="">Pilih tipe</option>
                    <option value="Masuk">Masuk</option>
                    <option value="Keluar">Keluar</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" required min="1">
            </div>
            <div class="form-group full">
                <label>Keterangan</label>
                <input type="text" name="keterangan" required placeholder="Alasan mutasi...">
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline">Batal</a>
            <button class="btn">Simpan Mutasi</button>
        </div>
    </form>
</div>

<div class="card">
    <h3 style="font-size:14px;font-weight:600;margin:0 0 16px">Riwayat Mutasi</h3>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutations as $m)
                <tr>
                    <td style="white-space:nowrap">{{ $m->time_stamp?->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $m->tipe_mutasi === 'Masuk' ? 'badge-green' : 'badge-red' }}">
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
                    <td colspan="7"><div class="empty-state">Belum ada riwayat mutasi.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
