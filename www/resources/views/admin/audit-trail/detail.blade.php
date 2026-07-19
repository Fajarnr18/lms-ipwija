@extends('layouts.app')
@section('title', 'Detail Audit Trail')
@section('subtitle', 'Menampilkan rincian perubahan data pada sistem.')

@section('content')
@php
$roleBadge = match(strtolower($log->role_pelaku ?? '')) {
    'admin' => 'badge-blue',
    'mahasiswa' => 'badge-green',
    'dosen' => 'badge-purple',
    default => 'badge-gray',
};

$aksiBadge = match(strtolower($log->aksi)) {
    'buat', 'tambah', 'create' => 'badge-green',
    'ubah', 'edit', 'update', 'change_status' => 'badge-yellow',
    'hapus', 'delete', 'reject_and_delete' => 'badge-red',
    'setuju', 'approve' => 'badge-blue',
    'tolak', 'reject' => 'badge-red',
    'kembali' => 'badge-purple',
    default => 'badge-gray',
};

$labelMap = [
    'id_alat' => 'ID Alat',
    'id_barang' => 'ID Barang',
    'nama_alat' => 'Nama Alat',
    'nama_barang' => 'Nama Barang',
    'kode_barang' => 'Kode Barang',
    'status_alat' => 'Status Alat',
    'kondisi' => 'Kondisi',
    'lokasi_rak' => 'Lokasi Rak',
    'stok' => 'Stok',
    'stok_akhir' => 'Stok Akhir',
    'stok_sebelum' => 'Stok Sebelum',
    'id_kategori' => 'ID Kategori',
    'kategori' => 'Kategori',
    'deskripsi' => 'Deskripsi',
    'jumlah' => 'Jumlah',
    'satuan' => 'Satuan',
    'tipe_mutasi' => 'Tipe Mutasi',
    'keterangan' => 'Keterangan',
    'status' => 'Status',
    'nama_lengkap' => 'Nama Lengkap',
    'email' => 'Email',
    'role' => 'Role',
    'is_active' => 'Status Aktif',
    'nim' => 'NIM',
    'nuptk' => 'NUPTK',
    'program_studi' => 'Program Studi',
    'judul_peminjaman' => 'Judul Peminjaman',
    'tgl_peminjaman' => 'Tanggal Peminjaman',
    'tgl_rencana_kembali' => 'Rencana Kembali',
    'tgl_kembali' => 'Tanggal Kembali',
];
@endphp

<div style="display:grid;grid-template-columns:1fr 1.8fr;gap:20px;align-items:start">

    {{-- Ringkasan Info --}}
    <div class="card" style="padding:20px">
        <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0 0 16px;display:flex;align-items:center;gap:8px">
            <svg width="18" height="18" fill="none" stroke="#1E3A5F" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Ringkasan Info
        </h2>

        <div style="display:flex;flex-direction:column;gap:14px">
            <div>
                <span style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:2px">Nama Pengguna</span>
                <span style="font-size:14px;font-weight:600;color:#1A1A2E">{{ $log->dilakukan_oleh }}</span>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Peran / Role</span>
                <span class="badge {{ $roleBadge }}">{{ ucfirst($log->role_pelaku ?? '-') }}</span>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Modul Sistem</span>
                @php
                $modColors = match($log->modul) {
                    'Peminjaman' => ['bg' => '#EEF2FF', 'text' => '#1E4FD8'],
                    'Alat' => ['bg' => '#ECFDF5', 'text' => '#059669'],
                    'Barang' => ['bg' => '#FFFBEB', 'text' => '#D97706'],
                    'User' => ['bg' => '#F5F3FF', 'text' => '#7C3AED'],
                    'Mutasi' => ['bg' => '#FEF2F2', 'text' => '#DC2626'],
                    default => ['bg' => '#F3F4F6', 'text' => '#6B7280'],
                };
                @endphp
                <span style="display:inline-block;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:{{ $modColors['bg'] }};color:{{ $modColors['text'] }}">{{ $log->modul }}</span>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Aksi Dilakukan</span>
                <span class="badge {{ $aksiBadge }}" style="font-size:12px;padding:4px 12px">{{ $log->aksi }}</span>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Alamat IP</span>
                <code style="font-size:13px;background:#F3F4F6;padding:4px 10px;border-radius:6px;color:#6B7280;display:inline-block">{{ $log->ip_address }}</code>
            </div>
            <div>
                <span style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:2px">Waktu Kejadian</span>
                <span style="font-size:14px;font-weight:500;color:#1A1A2E" title="{{ $log->time_stamp?->format('d/m/Y H:i:s') }}">{{ $log->time_stamp?->diffForHumans() }}</span>
            </div>
        </div>

        <a href="{{ route('admin.audit-trail.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;color:#374151;text-decoration:none;font-weight:500;background:#fff;transition:all .2s;margin-top:16px" onmouseover="this.style.borderColor='#1E3A5F';this.style.color='#1E3A5F'" onmouseout="this.style.borderColor='#E5E7EB';this.style.color='#374151'">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    {{-- Komparasi Data --}}
    <div class="card" style="padding:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0;display:flex;align-items:center;gap:8px">
                <svg width="18" height="18" fill="none" stroke="#1E3A5F" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Komparasi Data
            </h2>
            @if($perubahanTerdeteksi)
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:999px;font-size:11px;font-weight:700;background:#FEF2F2;color:#DC2626;border:1px solid #FECACA">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                Perubahan Terdeteksi
            </span>
            @else
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:999px;font-size:11px;font-weight:600;background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Tidak Ada Perubahan
            </span>
            @endif
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            {{-- Data Sebelum --}}
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:14px">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid #E5E7EB">
                    <svg width="14" height="14" fill="none" stroke="#6B7280" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.02em">Data Sebelum</span>
                </div>
                @forelse($dataSebelum as $field => $value)
                <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:5px 0;border-bottom:1px dashed #E5E7EB;font-size:13px">
                    <span style="color:#6B7280;font-weight:500;min-width:90px">{{ $labelMap[$field] ?? ucwords(str_replace('_', ' ', $field)) }}</span>
                    <span style="color:#1A1A2E;font-weight:600;text-align:right;max-width:160px;word-break:break-word">
                        @if(is_bool($value))
                            {{ $value ? 'Ya' : 'Tidak' }}
                        @elseif(is_null($value))
                            <span style="color:#D1D5DB">-</span>
                        @else
                            {{ $value }}
                        @endif
                    </span>
                </div>
                @empty
                <div style="text-align:center;padding:20px 0;color:#9CA3AF;font-size:13px">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display:block;margin:0 auto 8px"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Tidak ada data sebelumnya
                </div>
                @endforelse
            </div>

            {{-- Data Sesudah --}}
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:14px">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid #E5E7EB">
                    <svg width="14" height="14" fill="none" stroke="#6B7280" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.02em">Data Sesudah</span>
                </div>
                @forelse($dataSesudah as $field => $value)
                <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:5px 0;border-bottom:1px dashed #E5E7EB;font-size:13px">
                    <span style="color:#6B7280;font-weight:500;min-width:90px">{{ $labelMap[$field] ?? ucwords(str_replace('_', ' ', $field)) }}</span>
                    <span style="color:#1A1A2E;font-weight:600;text-align:right;max-width:160px;word-break:break-word">
                        @if(is_bool($value))
                            {{ $value ? 'Ya' : 'Tidak' }}
                        @elseif(is_null($value))
                            <span style="color:#D1D5DB">-</span>
                        @else
                            {{ $value }}
                        @endif
                    </span>
                </div>
                @empty
                <div style="text-align:center;padding:20px 0;color:#9CA3AF;font-size:13px">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display:block;margin:0 auto 8px"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Tidak ada data sesudah
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection