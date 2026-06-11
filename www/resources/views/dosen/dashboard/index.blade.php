@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang, {{ auth()->user()->nama_lengkap }}')

@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2FF;color:#3B82F6">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="stat-value">{{ $availableTools }}</div>
        <div class="stat-label">Alat Tersedia</div>
    </div>
    @php
    $stats = [
        'Peminjaman Aktif' => ['count' => $recentBorrowings->whereIn('status', ['DIPINJAM', 'DISETUJUI'])->count(), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#1E4FD8', 'bg' => '#EEF2FF'],
        'Menunggu' => ['count' => $recentBorrowings->whereIn('status', ['MENUNGGU'])->count(), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#92400E', 'bg' => '#FFFBEB'],
        'Selesai' => ['count' => $recentBorrowings->whereIn('status', ['DIKEMBALIKAN'])->count(), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#065F46', 'bg' => '#ECFDF5'],
        'Ditolak' => ['count' => $recentBorrowings->whereIn('status', ['DITOLAK'])->count(), 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#991B1B', 'bg' => '#FEF2F2'],
    ];
    @endphp
    @foreach($stats as $label => $s)
    <div class="stat-card">
        <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div class="stat-value">{{ $s['count'] }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
    @endforeach
</div>

@if($activeBorrowing)
<div class="card" style="border-color:#C7D2FE;margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:12px">
        <div style="width:40px;height:40px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#3B82F6;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div style="flex:1">
            <div style="font-size:13px;font-weight:600;color:#1A1A2E">Peminjaman Aktif #{{ $activeBorrowing->id_borrowing }}</div>
            <div style="font-size:12px;color:#6B7280;margin-top:2px">
                {{ $activeBorrowing->tgl_rencana_pinjam?->format('d/m/Y') }} &mdash; {{ $activeBorrowing->tgl_rencana_kembali?->format('d/m/Y') }}
            </div>
        </div>
        <a href="{{ route('dosen.peminjaman.detail', $activeBorrowing->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
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
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBorrowings as $b)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">#{{ $b->id_borrowing }}</td>
                    <td>{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $badgeClass = match($b->status) {
                            'MENUNGGU' => 'badge-yellow',
                            'DISETUJUI' => 'badge-blue',
                            'DITOLAK' => 'badge-red',
                            'DIPINJAM' => 'badge-purple',
                            'DIKEMBALIKAN' => 'badge-green',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $b->status }}</span>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('dosen.peminjaman.detail', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5"><div class="empty-state">Belum ada peminjaman.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
