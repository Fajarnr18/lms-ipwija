@extends('layouts.app')
@section('title', 'Keranjang Saya')
@section('subtitle', 'Atur peminjaman alat sebelum mengajukan')

@section('content')
<div class="card">
    <h2 style="font-size:14px;font-weight:600;margin:0 0 16px">Keranjang Pinjaman</h2>
    @if(count($cart) === 0)
    <div class="empty-state">
        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;color:#D1D5DB"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        Keranjang kosong. <a href="{{ route('katalog.index') }}" style="color:#3B82F6;text-decoration:none;font-weight:500">Jelajahi katalog</a>
    </div>
    @else
    <form method="POST" action="{{ route('keranjang.ajukan') }}">
        @csrf
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Kode</th>
                        <th style="text-align:center">Qty</th>
                        <th style="text-align:center">Stok Tersedia</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td style="font-weight:500;color:#1A1A2E">{{ $item['nama_alat'] }}</td>
                        <td>{{ $item['kode_alat'] }}</td>
                        <td style="text-align:center">
                            <input type="number" name="kuantitas[{{ $id }}]" value="{{ $item['jumlah_unit'] }}" min="1" max="{{ $item['stok_tersedia'] }}" style="width:60px;text-align:center;padding:6px 8px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif">
                        </td>
                        <td style="text-align:center">{{ $item['stok_tersedia'] }}</td>
                        <td style="text-align:center">
                            <a href="{{ route('keranjang.hapus', $id) }}" class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr class="divider">
        <div class="form-grid">
            <div class="form-group full">
                <label>Keperluan <span style="color:#EF4444">*</span></label>
                <textarea name="keperluan" required placeholder="Jelaskan keperluan peminjaman..." rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Rencana Pinjam <span style="color:#EF4444">*</span></label>
                <input type="date" name="tgl_rencana_pinjam" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label>Rencana Kembali <span style="color:#EF4444">*</span></label>
                <input type="date" name="tgl_rencana_kembali" required min="{{ date('Y-m-d') }}">
            </div>
        </div>
        @if($errors->any())
        <div class="alert alert-error" style="margin-top:16px">
            <span>&#9888;</span>
            <div>@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
        </div>
        @endif
        <div class="form-actions">
            <button type="submit" class="btn">Ajukan Peminjaman</button>
        </div>
    </form>
    @endif
</div>
@endsection
