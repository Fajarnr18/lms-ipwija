@extends('layouts.app')
@section('title', 'Laporan')
@section('subtitle', 'Export data laboratorium')

@section('content')
<div class="tabs">
    <a href="{{ route('admin.laporan.index', ['tab' => 'rekap-peminjaman']) }}" class="{{ request('tab', 'rekap-peminjaman') === 'rekap-peminjaman' ? 'active' : '' }}">Rekap Peminjaman</a>
    <a href="{{ route('admin.laporan.index', ['tab' => 'alat-sering-dipinjam']) }}" class="{{ request('tab') === 'alat-sering-dipinjam' ? 'active' : '' }}">Alat Sering Dipinjam</a>
    <a href="{{ route('admin.laporan.index', ['tab' => 'inventaris-barang']) }}" class="{{ request('tab') === 'inventaris-barang' ? 'active' : '' }}">Inventaris Barang</a>
    <a href="{{ route('admin.laporan.index', ['tab' => 'log-mutasi-stok']) }}" class="{{ request('tab') === 'log-mutasi-stok' ? 'active' : '' }}">Log Mutasi Stok</a>
    <a href="{{ route('admin.laporan.index', ['tab' => 'alat-dipinjam']) }}" class="{{ request('tab') === 'alat-dipinjam' ? 'active' : '' }}">Alat Sedang Dipinjam</a>
    <a href="{{ route('admin.laporan.index', ['tab' => 'rekap-per-mahasiswa']) }}" class="{{ request('tab') === 'rekap-per-mahasiswa' ? 'active' : '' }}">Rekap per Mahasiswa</a>
</div>

<div class="card" style="margin-bottom:16px">
    <form method="GET" action="{{ route('admin.laporan.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:end">
        <input type="hidden" name="tab" value="{{ request('tab', 'rekap-peminjaman') }}">
        <div class="toolbar-item">
            <label>Tanggal Mulai</label>
            <input type="date" name="from" value="{{ request('from') }}">
        </div>
        <div class="toolbar-item">
            <label>Tanggal Akhir</label>
            <input type="date" name="to" value="{{ request('to') }}">
        </div>
        @if(request('tab', 'rekap-peminjaman') === 'rekap-peminjaman')
        <div class="toolbar-item">
            <label>Status</label>
            <select name="status">
                <option value="">Semua</option>
                <option value="MENUNGGU" @selected(request('status')==='MENUNGGU')>Menunggu</option>
                <option value="DISETUJUI" @selected(request('status')==='DISETUJUI')>Disetujui</option>
                <option value="DITOLAK" @selected(request('status')==='DITOLAK')>Ditolak</option>
                <option value="DIPINJAM" @selected(request('status')==='DIPINJAM')>Dipinjam</option>
                <option value="DIKEMBALIKAN" @selected(request('status')==='DIKEMBALIKAN')>Dikembalikan</option>
            </select>
        </div>
        @endif
        <button type="submit" class="btn btn-sm">Tampilkan</button>
        <a href="{{ route('admin.laporan.export', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn btn-sm btn-outline">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Ekspor CSV
        </a>
    </form>
</div>

<div class="card">
    <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;display:flex;align-items:center;gap:8px">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Hasil Laporan
    </h3>
    <div style="overflow-x:auto">
        @if(request('tab', 'rekap-peminjaman') === 'rekap-peminjaman')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali (Rencana)</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Jumlah Alat</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($data['borrowings'] ?? []) as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($b->keperluan, 40) }}</td>
                    <td>
                        @php
                        $st = strtoupper(trim($b->status ?? ''));
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
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ $b->borrowingItems->count() }} alat</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada data untuk ditampilkan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @elseif(request('tab') === 'alat-sering-dipinjam')
        <table>
            <thead>
                <tr><th>No</th><th>Kode Alat</th><th>Nama Alat</th><th>Kategori</th><th>Total Dipinjam</th><th>Total Unit</th></tr>
            </thead>
            <tbody>
                @forelse(($data['popularTools'] ?? []) as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['tool']?->kode_alat }}</td>
                    <td>{{ $item['tool']?->nama_alat }}</td>
                    <td>{{ $item['tool']?->kategori }}</td>
                    <td>{{ $item['total_dipinjam'] }} kali</td>
                    <td>{{ $item['total_unit'] }} unit</td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">Tidak ada data untuk ditampilkan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
        @elseif(request('tab') === 'inventaris-barang')
        <table>
            <thead>
                <tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Satuan</th><th>Kondisi</th><th>Lokasi</th></tr>
            </thead>
            <tbody>
                @forelse(($data['items'] ?? []) as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ $item->kondisi }}</td>
                    <td>{{ $item->lokasi }}</td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">Tidak ada data untuk ditampilkan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
        @elseif(request('tab') === 'log-mutasi-stok')
        <table>
            <thead>
                <tr><th>No</th><th>Barang</th><th>Tipe Mutasi</th><th>Jumlah</th><th>Stok Sebelum</th><th>Stok Sesudah</th><th>Keterangan</th><th>Waktu</th></tr>
            </thead>
            <tbody>
                @forelse(($data['mutations'] ?? []) as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->item?->nama_barang }}</td>
                    <td>{{ $m->tipe_mutasi }}</td>
                    <td>{{ $m->jumlah }}</td>
                    <td>{{ $m->stok_sebelum }}</td>
                    <td>{{ $m->stok_sesudah }}</td>
                    <td>{{ $m->keterangan }}</td>
                    <td>{{ $m->time_stamp?->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">Tidak ada data untuk ditampilkan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
        @elseif(request('tab') === 'alat-dipinjam')
        <table>
            <thead>
                <tr><th>No</th><th>Peminjam</th><th>Tgl Pinjam</th><th>Tgl Rencana Kembali</th><th>Status</th><th>Jumlah Item</th></tr>
            </thead>
            <tbody>
                @forelse(($data['activeBorrowings'] ?? []) as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $st = strtoupper(trim($b->status ?? ''));
                        $badgeClass = match($st) {
                            'DIPINJAM' => 'badge-purple',
                            'DISETUJUI' => 'badge-blue',
                            default => 'badge-gray',
                        };
                        $statusLabel = match($st) {
                            'DIPINJAM' => 'Dipinjam',
                            'DISETUJUI' => 'Disetujui',
                            default => $b->status,
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ $b->borrowingItems->count() }} item</td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">Tidak ada data untuk ditampilkan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
        @elseif(request('tab') === 'rekap-per-mahasiswa')
        <table>
            <thead>
                <tr><th>No</th><th>Nama</th><th>NIM</th><th>Role</th><th>Total Peminjaman</th><th>Disetujui</th><th>Selesai</th></tr>
            </thead>
            <tbody>
                @forelse(($data['users'] ?? []) as $u)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $u->nama_lengkap }}</td>
                    <td>{{ $u->nim }}</td>
                    <td>{{ ucfirst($u->role) }}</td>
                    <td>{{ $u->total_peminjaman }}</td>
                    <td>{{ $u->total_disetujui }}</td>
                    <td>{{ $u->total_selesai }}</td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state">Tidak ada data untuk ditampilkan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
