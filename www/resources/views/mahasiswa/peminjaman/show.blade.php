@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
<style>
    .loan-header-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .loan-top-bar {
        padding: 20px 24px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        border: 1.5px solid transparent;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .status-menunggu { background: #fff; border-color: #FBBF24; color: #D97706; }
    .status-menunggu .status-dot { background: #D97706; }
    
    .status-disetujui { background: #fff; border-color: #BFDBFE; color: #2563EB; }
    .status-disetujui .status-dot { background: #2563EB; }
    
    .status-dipinjam { background: #fff; border-color: #E9D5FF; color: #9333EA; }
    .status-dipinjam .status-dot { background: #9333EA; }
    
    .status-dikembalikan { background: #fff; border-color: #BBF7D0; color: #16A34A; }
    .status-dikembalikan .status-dot { background: #16A34A; }
    
    .status-ditolak { background: #fff; border-color: #FECACA; color: #DC2626; }
    .status-ditolak .status-dot { background: #DC2626; }
    
    .date-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        padding: 20px 24px;
    }
    .date-card {
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .date-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .date-title { font-size: 11px; color: #6B7280; font-weight: 500; margin-bottom: 2px; }
    .date-value { font-size: 14px; font-weight: 700; color: #111827; }

    .main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    
    .section-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }
    .items-table th {
        text-align: left;
        font-size: 12px;
        color: #6B7280;
        font-weight: 500;
        padding-bottom: 12px;
        border-bottom: 1px solid #E5E7EB;
    }
    .items-table td {
        padding: 16px 0;
        border-bottom: 1px solid #E5E7EB;
        vertical-align: middle;
    }
    .items-table tr:last-child td { border-bottom: none; }
    
    .download-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #1D4ED8;
        color: #fff;
        padding: 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 24px;
        transition: background 0.2s;
    }
    .download-btn:hover { background: #1E40AF; }
    
    .instruction-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .instruction-list li {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #374151;
        line-height: 1.5;
    }
    .instruction-num {
        width: 22px;
        height: 22px;
        background: #1E3A8A;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }
    
    .location-box {
        background: #F3E8FF;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }
</style>

<div class="loan-header-card">
    <div class="loan-top-bar">
        <div>
            <div style="font-size:12px; color:#6B7280; font-weight:600; text-transform:uppercase;">
                LOAN ID <span style="color:#111827; font-size:16px; margin-left:6px;">REQ-{{ str_pad($borowing->id_borrowing, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div style="font-size:13px; color:#6B7280; margin-top:4px;">
                Diajukan pada {{ $borowing->tgl_pengajuan?->translatedFormat('d F Y, H:i') ?? '-' }} WIB
            </div>
        </div>
        <div>
            @php
                $st = strtoupper(trim($borowing->status ?? ''));
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
        </div>
    </div>
    
    <div class="date-grid">
        <div class="date-card">
            <div class="date-icon" style="background:#EFF6FF; color:#3B82F6;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="date-title">Tanggal Pinjam</div>
                <div class="date-value">{{ $borowing->tgl_rencana_pinjam?->translatedFormat('d M Y') ?? '-' }}</div>
            </div>
        </div>
        <div class="date-card">
            <div class="date-icon" style="background:#F3F4F6; color:#4B5563;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="date-title">Tanggal Pengajuan</div>
                <div class="date-value">{{ $borowing->tgl_pengajuan?->translatedFormat('d M Y') ?? '-' }}</div>
            </div>
        </div>
        <div class="date-card">
            <div class="date-icon" style="background:#F3E8FF; color:#9333EA;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="date-title">Estimasi Kembali</div>
                <div class="date-value">{{ $borowing->tgl_rencana_kembali?->translatedFormat('d M Y') ?? '-' }}</div>
            </div>
        </div>
        <div class="date-card">
            <div class="date-icon" style="background:#DCFCE7; color:#16A34A;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="date-title">Tanggal Pengembalian</div>
                <div class="date-value">{{ $borowing->tgl_pengembalian_aktual?->translatedFormat('d M Y') ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="main-grid">
    <div class="left-col">
        @if(strtoupper(trim($borowing->status ?? '')) === 'DITOLAK' && $borowing->catatan_admin)
        <div class="section-card" style="border-color:#FECACA; background:#FEF2F2; padding:16px;">
            <div style="display:flex;align-items:flex-start;gap:12px">
                <div style="width:36px;height:36px;border-radius:10px;background:#FCA5A5;display:flex;align-items:center;justify-content:center;color:#991B1B;flex-shrink:0">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:#991B1B;margin-bottom:4px">Alasan Penolakan</div>
                    <div style="font-size:13px;color:#7F1D1D;line-height:1.5;">{{ $borowing->catatan_admin }}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="section-card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#111827;">Daftar Alat</h3>
                <span style="background:#1E3A8A; color:#fff; font-size:11px; font-weight:600; padding:4px 10px; border-radius:9999px;">{{ $borowing->borrowingItems->count() }} ITEMS</span>
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Kondisi Kembali</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borowing->borrowingItems as $item)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:48px; height:48px; border-radius:8px; background:#F3F4F6; overflow:hidden;">
                                    @if($item->tool?->foto)
                                        <img src="{{ Storage::url($item->tool->foto) }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#9CA3AF;">
                                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600; color:#111827; font-size:13px; margin-bottom:2px;">{{ $item->tool?->nama_alat }}</div>
                                    <div style="font-size:12px; color:#6B7280;">{{ $item->tool?->kode_alat }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px; font-weight:600; color:#111827;">{{ $item->jumlah_unit }} Unit</td>
                        <td>
                            @php
                                $kondisi = strtoupper($item->kondisi_saat_kembali ?? 'BELUM KEMBALI');
                                $kondisiClass = match($kondisi) {
                                    'BAIK' => 'color:#16A34A; background:#DCFCE7;',
                                    'RUSAK' => 'color:#DC2626; background:#FECACA;',
                                    'HILANG' => 'color:#9333EA; background:#F3E8FF;',
                                    default => 'color:#4B5563; background:#F3F4F6;',
                                };
                            @endphp
                            <span style="{{ $kondisiClass }} font-size:10px; font-weight:700; padding:4px 8px; border-radius:4px;">{{ $kondisi }}</span>
                        </td>
                        <td style="font-size:13px; color:#4B5563;">{{ $item->catatan_pengembalian ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:24px 0; color:#6B7280;">Tidak ada data alat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="section-card">
            <h3 style="margin:0 0 12px 0; font-size:15px; font-weight:600; color:#111827;">Catatan Petugas</h3>
            <div style="border:1px solid #E5E7EB; border-radius:8px; padding:16px; min-height:80px; font-size:14px; color:#374151;">
                {{ $borowing->catatan_admin ?: '-' }}
            </div>
        </div>
        
        <div style="background:#F3F4F6; border:1px solid #E5E7EB; border-radius:8px; padding:16px; display:flex; gap:12px;">
            <svg style="color:#6B7280; width:20px; height:20px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div style="font-size:13px; color:#4B5563; line-height:1.5;">
                <strong>Catatan:</strong> Pastikan semua alat dicek kembali kondisinya saat pengembalian untuk menghindari kerusakan atau kehilangan.
            </div>
        </div>
    </div>
    
    <div class="right-col">
        <a href="{{ route('peminjaman.pdf', $borowing->id_borrowing) }}" class="download-btn">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Unduh Bukti Pinjam (PDF)
        </a>
        
        <div class="section-card" style="padding:24px;">
            <h3 style="margin:0 0 20px 0; font-size:16px; font-weight:600; color:#111827;">Instruksi Pengambilan</h3>
            
            <ul class="instruction-list">
                <li>
                    <div class="instruction-num">1</div>
                    <div>Tunjukkan Bukti Pinjam (PDF/Digital) kepada petugas laboratorium di jam kerja.</div>
                </li>
                <li>
                    <div class="instruction-num">2</div>
                    <div>Lakukan verifikasi identitas (KTM) sebelum serah terima alat.</div>
                </li>
                <li>
                    <div class="instruction-num">3</div>
                    <div>Cek fisik alat bersama petugas dan tanda tangani berita acara peminjaman.</div>
                </li>
            </ul>
            
            <div class="location-box">
                <div style="color:#9333EA; margin-top:2px;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <div style="font-weight:700; font-size:12px; color:#111827; margin-bottom:4px;">Lokasi Pengambilan</div>
                    <div style="font-size:12px; color:#4B5563; line-height:1.5;">Gedung B, Lantai 2, Lab Kimia Terpadu Universitas IPWIJA.</div>
                </div>
            </div>
        </div>
        
        <a href="{{ route('peminjaman.index') }}" style="display:block; text-align:center; padding:12px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; font-weight:600; color:#4B5563; text-decoration:none; background:#fff;">
            &larr; Kembali
        </a>
    </div>
</div>
@endsection
