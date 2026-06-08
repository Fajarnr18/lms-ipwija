@extends('layouts.app')
@section('title', 'Keranjang Saya')

@section('content')
<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 16px">Keranjang Pinjaman</h2>

    @if(count($cart) === 0)
    <div class="empty-state">
        Keranjang kosong. <a href="{{ route('dosen.catalog.index') }}" style="color:#1E4FD8;text-decoration:none;font-weight:500">Jelajahi katalog</a>
    </div>
    @else
    <form method="POST" action="{{ route('dosen.cart.submit') }}">
        @csrf
        <div style="overflow-x:auto">
            <table>
                <thead><tr><th>Alat</th><th>Qty</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td>{{ $item['nama_alat'] }} ({{ $item['kode_alat'] }})</td>
                        <td><input type="number" name="kuantitas[{{ $id }}]" value="{{ $item['jumlah_unit'] }}" min="1" max="{{ $item['stok_tersedia'] }}" style="width:60px;padding:6px 8px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif"></td>
                        <td><div class="action-group"><a href="{{ route('dosen.cart.remove', $id) }}" class="btn btn-sm btn-danger">Hapus</a></div></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="divider">

        <div class="form-grid">
            <div class="form-group full">
                <label>Keperluan *</label>
                <textarea name="keperluan" required placeholder="Jelaskan keperluan peminjaman..."></textarea>
            </div>
            <div class="form-group">
                <label>Rencana Pinjam *</label>
                <input type="date" name="tgl_rencana_pinjam" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label>Rencana Kembali *</label>
                <input type="date" name="tgl_rencana_kembali" required min="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Ajukan Peminjaman</button>
        </div>
    </form>
    @endif
</div>
@endsection
