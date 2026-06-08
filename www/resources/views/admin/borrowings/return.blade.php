@extends('layouts.app')
@section('title', 'Catat Pengembalian')
@section('subtitle', 'Pencatatan pengembalian alat peminjaman #' . $borowing->id_borrowing)

@section('content')
<div class="card" style="margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="flex:1;min-width:200px">
            <div style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $borowing->mahasiswa?->nama_lengkap }}</div>
            <div style="font-size:12px;color:#6B7280;margin-top:2px">
                {{ $borowing->tgl_rencana_pinjam?->format('d/m/Y') }} &mdash; {{ $borowing->tgl_rencana_kembali?->format('d/m/Y') }}
            </div>
        </div>
        <div style="text-align:right">
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Status</div>
            <span class="badge badge-purple">{{ $borowing->status }}</span>
        </div>
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.borrowings.return-submit', $borowing->id_borrowing) }}">
        @csrf

        <div class="form-group">
            <label>Tanggal Pengembalian Aktual</label>
            <input type="date" name="tgl_pengembalian_aktual" value="{{ old('tgl_pengembalian_aktual', date('Y-m-d')) }}" required style="max-width:280px">
        </div>

        <h3 style="font-size:14px;font-weight:600;margin:20px 0 16px">Kondisi Alat Kembali</h3>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Alat</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borowing->borrowingItems as $index => $item)
                    <tr>
                        <td>{{ $item->tool?->kode_alat }}</td>
                        <td>{{ $item->tool?->nama_alat }}</td>
                        <td>{{ $item->jumlah_unit }}</td>
                        <td>
                            <input type="hidden" name="items[{{ $index }}][id_borrowings_item]" value="{{ $item->id_borrowings_item }}">
                            <select name="items[{{ $index }}][kondisi_saat_kembali]" required style="min-width:130px">
                                <option value="">Pilih</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="items[{{ $index }}][catatan_pengembalian]" placeholder="Catatan..." style="min-width:150px">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.borrowings.index', ['tab' => 'aktif']) }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-success">Simpan Pengembalian</button>
        </div>
    </form>
</div>
@endsection
