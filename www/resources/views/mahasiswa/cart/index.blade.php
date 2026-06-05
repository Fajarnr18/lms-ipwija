@extends('layouts.app')
@section('title', 'Keranjang Saya')

@section('content')
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 1rem">Keranjang Pinjaman</h2>

    @if(count($cart) === 0)
    <p style="text-align:center;padding:2rem;color:#64748b">Keranjang kosong. <a href="{{ route('mhs.catalog.index') }}" style="color:#6366f1;text-decoration:none;font-weight:500">Jelajahi katalog</a></p>
    @else
    <form method="POST" action="{{ route('mhs.cart.submit') }}">
        @csrf
        <div class="overflow-x-auto">
            <table>
                <thead><tr><th>Alat</th><th>Qty</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td>{{ $item['nama_alat'] }} ({{ $item['kode_alat'] }})</td>
                        <td><input type="number" name="kuantitas[{{ $id }}]" value="{{ $item['jumlah_unit'] }}" min="1" max="{{ $item['stok_tersedia'] }}" style="width:60px"></td>
                        <td><a href="{{ route('mhs.cart.remove', $id) }}" class="btn btn-sm btn-danger">Hapus</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-grid" style="margin-top:1.5rem">
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
