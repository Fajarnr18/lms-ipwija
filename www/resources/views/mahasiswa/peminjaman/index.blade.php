@extends('layouts.app')
@section('title', 'Peminjaman Saya')

@section('content')
<style>
    .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .stat-title {
        font-size: 13px;
        color: #6B7280;
        font-weight: 500;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
    }
    .val-total { color: #111827; }
    .val-dipinjam { color: #3B82F6; }
    .val-selesai { color: #8B5CF6; }
    .val-ditolak { color: #EF4444; }

    .tabs-container {
        border-bottom: 1px solid #E5E7EB;
        margin-bottom: 0;
        display: flex;
        gap: 24px;
        padding: 0 16px;
        background: #fff;
        border-radius: 8px 8px 0 0;
        border: 1px solid #E5E7EB;
        border-bottom: none;
    }
    .tab-item {
        padding: 16px 0;
        color: #6B7280;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        position: relative;
    }
    .tab-item.active {
        color: #111827;
        font-weight: 600;
    }
    .tab-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #3B82F6;
    }

    .table-container {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th {
        background: #F9FAFB;
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #4B5563;
        text-transform: uppercase;
        border-bottom: 1px solid #E5E7EB;
        border-top: 1px solid #E5E7EB;
    }
    .custom-table td {
        padding: 16px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #E5E7EB;
    }
    .custom-table th:first-child, .custom-table td:first-child { padding-left: 24px; }
    .custom-table th:last-child, .custom-table td:last-child { padding-right: 24px; }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    
    .status-menunggu { background: #FEF3C7; color: #D97706; }
    .status-menunggu .status-dot { background: #D97706; }
    
    .status-disetujui { background: #DBEAFE; color: #2563EB; }
    .status-disetujui .status-dot { background: #2563EB; }
    
    .status-dipinjam { background: #F3E8FF; color: #9333EA; }
    .status-dipinjam .status-dot { background: #9333EA; }
    
    .status-dikembalikan { background: #DCFCE7; color: #16A34A; }
    .status-dikembalikan .status-dot { background: #16A34A; }
    
    .status-ditolak { background: #FEE2E2; color: #DC2626; }
    .status-ditolak .status-dot { background: #DC2626; }
    
    .btn-detail {
        background: #1D4ED8;
        color: #fff;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.2s;
    }
    .btn-detail:hover { background: #1E40AF; }

    .info-box {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: 8px;
        padding: 16px 20px;
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
    .info-box svg {
        color: #3B82F6;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .info-title {
        font-size: 14px;
        font-weight: 600;
        color: #1E3A8A;
        margin-bottom: 4px;
    }
    .info-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }
    
    .pagination-wrapper {
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-title">Total Pengajuan</div>
        <div class="stat-value val-total">{{ $countTotal }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Sedang Dipinjam</div>
        <div class="stat-value val-dipinjam">{{ $countDipinjam }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Selesai</div>
        <div class="stat-value val-selesai">{{ $countSelesai }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Ditolak</div>
        <div class="stat-value val-ditolak">{{ $countDitolak }}</div>
    </div>
</div>

<div class="tabs-container">
    <a href="{{ route('peminjaman.index') }}" class="tab-item {{ !request('status') || request('status') == 'SEMUA' ? 'active' : '' }}">Semua</a>
    <a href="{{ route('peminjaman.index', ['status' => 'MENUNGGU']) }}" class="tab-item {{ request('status') == 'MENUNGGU' ? 'active' : '' }}">Menunggu</a>
    <a href="{{ route('peminjaman.index', ['status' => 'DISETUJUI']) }}" class="tab-item {{ request('status') == 'DISETUJUI' ? 'active' : '' }}">Disetujui</a>
    <a href="{{ route('peminjaman.index', ['status' => 'DIPINJAM']) }}" class="tab-item {{ request('status') == 'DIPINJAM' ? 'active' : '' }}">Dipinjam</a>
    <a href="{{ route('peminjaman.index', ['status' => 'DIKEMBALIKAN']) }}" class="tab-item {{ request('status') == 'DIKEMBALIKAN' ? 'active' : '' }}">Dikembalikan</a>
    <a href="{{ route('peminjaman.index', ['status' => 'DITOLAK']) }}" class="tab-item {{ request('status') == 'DITOLAK' ? 'active' : '' }}">Ditolak</a>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>NO. PENGAJUAN</th>
                <th>TANGGAL AJUAN</th>
                <th>JUMLAH ALAT</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $b)
            <tr>
                <td style="font-weight: 600;">REQ-{{ str_pad($b->id_borrowing, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $b->tgl_pengajuan?->translatedFormat('d M Y') ?? '-' }}</td>
                <td>{{ $b->borrowingItems->sum('jumlah_unit') }} Alat</td>
                <td>
                    @php
                        $st = strtoupper(trim($b->status ?? ''));
                        $badgeClass = match($st) {
                            'MENUNGGU' => 'status-menunggu',
                            'DISETUJUI' => 'status-disetujui',
                            'DIPINJAM' => 'status-dipinjam',
                            'DIKEMBALIKAN' => 'status-dikembalikan',
                            'DITOLAK' => 'status-ditolak',
                            default => '',
                        };
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">
                        <span class="status-dot"></span>
                        {{ $st }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('peminjaman.detail', $b->id_borrowing) }}" class="btn-detail">
                        Lihat Detail
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #6B7280;">Belum ada riwayat peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($borrowings->hasPages())
    <div class="pagination-wrapper">
        <div style="font-size: 13px; color: #6B7280;">
            Menampilkan {{ $borrowings->firstItem() }}-{{ $borrowings->lastItem() }} dari {{ $borrowings->total() }} pengajuan
        </div>
        <div>
            {{ $borrowings->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

<div class="info-box">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <div>
        <div class="info-title">Informasi Peminjaman</div>
        <div class="info-desc">
            Harap membawa Kartu Tanda Mahasiswa (KTM) asli saat melakukan pengambilan alat di laboratorium. Pastikan alat dikembalikan sesuai jadwal untuk menghindari denda keterlambatan.
        </div>
    </div>
</div>
@endsection

