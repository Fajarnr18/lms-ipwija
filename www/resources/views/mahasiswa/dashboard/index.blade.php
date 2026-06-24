@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang, {{ auth()->user()->nama_lengkap }}')

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#F59E0B,#F97316);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countMenunggu }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Menunggu</div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#3B82F6,#1D4ED8);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countBerjalan }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Berjalan</div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#8B5CF6,#6D28D9);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $cartCount }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Keranjang</div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#10B981,#059669);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countSelesai }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Selesai</div>
        </div>
    </div>
</div>

@if($activeBorrowing)
@php $stAktif = strtoupper(trim($activeBorrowing->status ?? '')); @endphp
<div class="card" style="border-color:#C7D2FE;margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:12px">
        <div style="width:40px;height:40px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#3B82F6;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div style="flex:1">
            <div style="font-size:13px;font-weight:600;color:#1A1A2E">Peminjaman Aktif #{{ $activeBorrowing->id_borrowing }}</div>
            <div style="font-size:12px;color:#6B7280;margin-top:2px">
                {{ $activeBorrowing->tgl_rencana_pinjam?->format('d/m/Y') }} &mdash; {{ $activeBorrowing->tgl_rencana_kembali?->format('d/m/Y') }}
                &middot;
                <span style="color:{{ $stAktif === 'DIPINJAM' ? '#7C3AED' : '#2563EB' }}">{{ $stAktif === 'DIPINJAM' ? 'Sedang Dipinjam' : 'Disetujui' }}</span>
            </div>
        </div>
        <a href="{{ route('peminjaman.detail', $activeBorrowing->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
    </div>
</div>
@endif

<div class="card">
    <h2 style="font-size:14px;font-weight:600;margin:0 0 16px">Peminjaman Terkini</h2>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tgl Pengajuan</th>
                    <th>Rencana Pinjam</th>
                    <th>Estimasi Kembali</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBorrowings as $b)
                @php $st = strtoupper(trim($b->status ?? '')); @endphp
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">#{{ $b->id_borrowing }}</td>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280">{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td style="white-space:nowrap;font-size:12px">{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td style="white-space:nowrap;font-size:12px">{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $badgeClass = match($st) {
                            'MENUNGGU' => 'badge-yellow',
                            'DISETUJUI' => 'badge-blue',
                            'DITOLAK' => 'badge-red',
                            'DIPINJAM' => 'badge-purple',
                            'DIKEMBALIKAN' => 'badge-green',
                            default => 'badge-gray',
                        };
                        $statusLabel = match($st) {
                            'MENUNGGU' => 'Menunggu',
                            'DISETUJUI' => 'Disetujui',
                            'DITOLAK' => 'Ditolak',
                            'DIPINJAM' => 'Dipinjam',
                            'DIKEMBALIKAN' => 'Dikembalikan',
                            default => $b->status,
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="font-size:11px">{{ $statusLabel }}</span>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('peminjaman.detail', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"><div class="empty-state">Belum ada peminjaman.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
