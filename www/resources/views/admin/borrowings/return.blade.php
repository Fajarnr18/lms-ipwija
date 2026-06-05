@extends('layouts.app')
@section('title', 'Catat Pengembalian')

@section('content')
<a href="{{ route('admin.borrowings.index', ['tab' => 'aktif']) }}" class="btn btn-outline btn-sm mb-3">&larr; Kembali</a>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 .25rem">Catat Pengembalian #{{ $borowing->id_borrowing }}</h2>
    <p style="font-size:.875rem;color:#64748b;margin:0 0 1rem">
        {{ $borowing->mahasiswa?->nama_lengkap }} — {{ $borowing->tgl_rencana_pinjam?->format('d/m/Y') }} s/d {{ $borowing->tgl_rencana_kembali?->format('d/m/Y') }}
    </p>

    <form method="POST" action="{{ route('admin.borrowings.return-submit', $borowing->id_borrowing) }}">
        @csrf
        <div class="overflow-x-auto">
            <table>
                <thead><tr><th>Kode</th><th>Nama Alat</th><th>Jumlah</th><th>Kondisi</th><th>Catatan</th></tr></thead>
                <tbody>
                    @foreach($borowing->borrowingItems as $index => $item)
                    <tr>
                        <td>{{ $item->tool?->kode_alat }}</td>
                        <td>{{ $item->tool?->nama_alat }}</td>
                        <td>{{ $item->jumlah_unit }}</td>
                        <td>
                            <input type="hidden" name="items[{{ $index }}][id_borrowings_item]" value="{{ $item->id_borrowings_item }}">
                            <select name="items[{{ $index }}][kondisi_saat_kembali]" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="items[{{ $index }}][catatan_pengembalian]" placeholder="Catatan...">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Simpan Pengembalian</button>
        </div>
    </form>
</div>
@endsection
