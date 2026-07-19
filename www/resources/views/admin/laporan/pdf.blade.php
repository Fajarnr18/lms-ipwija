<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a2e; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #1a1a2e; padding-bottom: 15px; }
        .header h1 { font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
        .header h2 { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 2px; }
        .header p { font-size: 10px; color: #6b7280; }
        .report-title { text-align: center; margin: 15px 0; }
        .report-title h3 { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .report-title p { font-size: 10px; color: #6b7280; margin-top: 3px; }
        .meta { display: table; width: 100%; margin-bottom: 15px; font-size: 10px; }
        .meta-row { display: table-row; }
        .meta-label { display: table-cell; width: 120px; font-weight: 600; padding: 2px 0; color: #374151; }
        .meta-value { display: table-cell; padding: 2px 0; }
        .stats-box { width: 100%; margin-bottom: 15px; border: 1px solid #d1d5db; border-radius: 4px; padding: 10px; }
        .stats-row { display: flex; gap: 20px; }
        .stat-item { text-align: center; flex: 1; }
        .stat-value { font-size: 18px; font-weight: 700; color: #1a1a2e; }
        .stat-label { font-size: 9px; color: #6b7280; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th { background: #1a1a2e; color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
        table td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tr:nth-child(even) { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .footer { margin-top: 30px; border-top: 1px solid #d1d5db; padding-top: 10px; font-size: 9px; color: #6b7280; }
        .footer-row { display: flex; justify-content: space-between; }
        .signature { margin-top: 40px; display: table; width: 100%; }
        .signature-col { display: table-cell; width: 50%; text-align: center; padding: 0 20px; }
        .signature-line { border-bottom: 1px solid #1a1a2e; margin: 50px auto 5px; width: 180px; }
        .signature-name { font-size: 10px; font-weight: 600; }
        .signature-title { font-size: 9px; color: #6b7280; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-data { text-align: center; padding: 30px; color: #6b7280; font-style: italic; }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <div class="header">
        <h1>Universitas IPWIJA</h1>
        <h2>Sistem Manajemen Laboratorium</h2>
        <p>Jl. Letda Natsir No.Kav.53, RT.003/RW.001, Cikeas Udik, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16967</p>
    </div>

    {{-- REPORT TITLE --}}
    <div class="report-title">
        <h3>{{ $title }}</h3>
        <p>
            @if($from && $to)
                Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} - {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
            @else
                Per tanggal: {{ now()->format('d M Y') }}
            @endif
        </p>
    </div>

    {{-- TAB: Rekap Peminjaman --}}
    @if($tab === 'rekap-peminjaman')
    <table style="width:auto; margin-bottom:15px; border:none;">
        <tr><td style="border:none;padding:2px 0;font-weight:600;width:150px;">Total Laporan</td><td style="border:none;padding:2px 0;">: {{ $stats['totalLaporan'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Disetujui</td><td style="border:none;padding:2px 0;">: {{ $stats['totalDisetujui'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Ditolak</td><td style="border:none;padding:2px 0;">: {{ $stats['totalDitolak'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Dikembalikan</td><td style="border:none;padding:2px 0;">: {{ $stats['totalDikembalikan'] }}</td></tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Peminjam</th>
                <th>NIM</th>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $b)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                <td>{{ $b->mahasiswa?->nim }}</td>
                <td>{{ $b->borrowingItems->pluck('tool.nama_alat')->filter()->join(', ') }}</td>
                <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                <td>
                    @php $s = $b->status; @endphp
                    <span class="badge {{ $s==='DISETUJUI' ? 'badge-blue' : ($s==='DITOLAK' ? 'badge-red' : ($s==='DIKEMBALIKAN' ? 'badge-green' : ($s==='DIPINJAM' ? 'badge-yellow' : 'badge-gray'))) }}">{{ $s }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="no-data">Tidak ada data peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- TAB: Alat Sering Dipinjam --}}
    @if($tab === 'alat-sering-dipinjam')
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Kode Alat</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th class="text-center">Total Dipinjam</th>
                <th class="text-center">Total Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item['tool']?->kode_alat }}</td>
                <td>{{ $item['tool']?->nama_alat }}</td>
                <td>{{ $item['tool']?->kategori }}</td>
                <td class="text-center">{{ $item['total_dipinjam'] }}</td>
                <td class="text-center">{{ $item['total_unit'] }}</td>
                <td><span class="badge {{ $item['status']==='Dipinjam' ? 'badge-yellow' : 'badge-green' }}">{{ $item['status'] }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="no-data">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- TAB: Alat Sedang Dipinjam --}}
    @if($tab === 'alat-dipinjam')
    <table style="width:auto; margin-bottom:15px; border:none;">
        <tr><td style="border:none;padding:2px 0;font-weight:600;width:180px;">Total Pinjaman Aktif</td><td style="border:none;padding:2px 0;">: {{ $stats['totalPinjamanAktif'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Terlambat</td><td style="border:none;padding:2px 0;">: {{ $stats['totalTerlambat'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Kembali Hari Ini</td><td style="border:none;padding:2px 0;">: {{ $stats['kembaliHariIni'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Peminjam Unik</td><td style="border:none;padding:2px 0;">: {{ $stats['peminjamUnik'] }}</td></tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Peminjam</th>
                <th>NIM</th>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Rencana Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $b)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $b->mahasiswa?->nama_lengkap }}</td>
                <td>{{ $b->mahasiswa?->nim }}</td>
                <td>{{ $b->borrowingItems->pluck('tool.nama_alat')->filter()->join(', ') }}</td>
                <td>{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                <td>{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                <td>
                    @php $overdue = $b->is_overdue ?? false; @endphp
                    <span class="badge {{ $overdue ? 'badge-red' : 'badge-yellow' }}">{{ $overdue ? 'TERLAMBAT' : $b->status }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="no-data">Tidak ada pinjaman aktif</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- TAB: Log Mutasi Stok --}}
    @if($tab === 'log-mutasi-stok')
    <table style="width:auto; margin-bottom:15px; border:none;">
        <tr><td style="border:none;padding:2px 0;font-weight:600;width:150px;">Total Pergerakan</td><td style="border:none;padding:2px 0;">: {{ $stats['totalPergerakan'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Masuk</td><td style="border:none;padding:2px 0;">: {{ $stats['totalMasuk'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Keluar</td><td style="border:none;padding:2px 0;">: {{ $stats['totalKeluar'] }}</td></tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Barang</th>
                <th>Tipe</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Stok Sebelum</th>
                <th class="text-center">Stok Sesudah</th>
                <th>Keterangan</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $m)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $m->item?->nama_barang }}</td>
                <td><span class="badge {{ $m->tipe_mutasi==='Masuk' ? 'badge-green' : ($m->tipe_mutasi==='Keluar' ? 'badge-red' : 'badge-yellow') }}">{{ $m->tipe_mutasi }}</span></td>
                <td class="text-center">{{ $m->jumlah }}</td>
                <td class="text-center">{{ $m->stok_sebelum }}</td>
                <td class="text-center">{{ $m->stok_sesudah }}</td>
                <td>{{ $m->keterangan }}</td>
                <td>{{ $m->time_stamp?->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="no-data">Tidak ada data mutasi</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- TAB: Inventaris Barang --}}
    @if($tab === 'inventaris-barang')
    <table style="width:auto; margin-bottom:15px; border:none;">
        <tr><td style="border:none;padding:2px 0;font-weight:600;width:150px;">Total Barang</td><td style="border:none;padding:2px 0;">: {{ $stats['totalBarang'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Total Stok</td><td style="border:none;padding:2px 0;">: {{ $stats['totalStok'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Kondisi Baik</td><td style="border:none;padding:2px 0;">: {{ $stats['kondisiBaik'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Kondisi Rusak</td><td style="border:none;padding:2px 0;">: {{ $stats['kondisiRusak'] }}</td></tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-center">Stok</th>
                <th>Satuan</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->kategori }}</td>
                <td class="text-center">{{ $item->stok }}</td>
                <td>{{ $item->satuan }}</td>
                <td><span class="badge {{ $item->kondisi==='Baik' ? 'badge-green' : 'badge-red' }}">{{ $item->kondisi }}</span></td>
                <td>{{ $item->lokasi }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="no-data">Tidak ada data inventaris</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- TAB: Rekap Per Mahasiswa --}}
    @if($tab === 'rekap-per-mahasiswa')
    <table style="width:auto; margin-bottom:15px; border:none;">
        <tr><td style="border:none;padding:2px 0;font-weight:600;width:180px;">Total Mahasiswa/Dosen</td><td style="border:none;padding:2px 0;">: {{ $stats['totalMahasiswa'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Status Aktif</td><td style="border:none;padding:2px 0;">: {{ $stats['statusAktif'] }}</td></tr>
        <tr><td style="border:none;padding:2px 0;font-weight:600;">Peminjam Aktif</td><td style="border:none;padding:2px 0;">: {{ $stats['peminjamAktif'] }}</td></tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Role</th>
                <th class="text-center">Total Peminjaman</th>
                <th class="text-center">Disetujui</th>
                <th class="text-center">Selesai</th>
                <th class="text-center">Aktif</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $u)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $u->nama_lengkap }}</td>
                <td>{{ $u->nim }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td class="text-center">{{ $u->total_peminjaman }}</td>
                <td class="text-center">{{ $u->total_disetujui }}</td>
                <td class="text-center">{{ $u->total_selesai }}</td>
                <td class="text-center">{{ $u->total_dipinjam }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="no-data">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- TAB: Alat Rusak --}}
    @if($tab === 'alat-rusak')
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Tanggal</th>
                <th>Nama Alat</th>
                <th>Peminjam</th>
                <th>Petugas</th>
                <th class="text-center">Jumlah</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->borowing?->tgl_pengembalian_aktual?->format('d/m/Y') }}</td>
                <td>{{ $item->tool?->nama_alat }}</td>
                <td>{{ $item->borowing?->mahasiswa?->nama_lengkap }}</td>
                <td>{{ $item->borowing?->prosesOleh?->nama_lengkap }}</td>
                <td class="text-center">{{ $item->jumlah_unit }}</td>
                <td><span class="badge badge-red">{{ $item->kondisi_saat_kembali }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="no-data">Tidak ada data alat rusak</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif

    {{-- SIGNATURE --}}
    <div class="signature">
        <div class="signature-col">
            <p style="font-size:10px;">Mengetahui,</p>
            <div class="signature-line"></div>
            <p class="signature-name">Kepala Laboratorium</p>
            <p class="signature-title">Universitas IPWIJA</p>
        </div>
        <div class="signature-col">
            <p style="font-size:10px;">{{ now()->format('d F Y') }}</p>
            <div class="signature-line"></div>
            <p class="signature-name">{{ auth()->user()->nama_lengkap ?? 'Admin' }}</p>
            <p class="signature-title">Petugas Administrasi</p>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }} | Oleh: {{ auth()->user()->nama_lengkap ?? 'Admin' }} | LMS Universitas IPWIJA</p>
    </div>
</body>
</html>
