@extends('layouts.app')

@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:360px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <form method="GET" action="{{ route('admin.laporan.index') }}">
            <input type="hidden" name="tab" value="{{ request('tab', 'rekap-peminjaman') }}">
            <input type="text" name="search" placeholder="Cari laporan..." value="{{ request('search') }}" style="width:100%;padding:8px 12px 8px 36px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
        </form>
    </div>
</div>
@endsection

@section('title', match(request('tab', 'rekap-peminjaman')) {
    'alat-sering-dipinjam' => 'Alat Sering Dipinjam',
    'alat-dipinjam' => 'Alat Sedang Dipinjam',
    'inventaris-barang' => 'Laporan Inventaris Barang',
    'log-mutasi-stok' => 'Log Mutasi Stok',
    'rekap-per-mahasiswa' => 'Rekapitulasi Per Mahasiswa',
    'alat-rusak' => 'Laporan Alat Rusak',
    default => 'Laporan',
})

@section('subtitle', match(request('tab', 'rekap-peminjaman')) {
    'alat-sering-dipinjam' => 'Analisis frekuensi peminjaman alat laboratorium universitas periode ini',
    'alat-dipinjam' => 'Daftar alat yang sedang dipinjam dan belum dikembalikan',
    'inventaris-barang' => 'Data inventaris alat dan barang laboratorium',
    'log-mutasi-stok' => 'Laporan real-time Log Mutasi Barang Universitas IPWIJA',
    'rekap-per-mahasiswa' => 'Rekapitulasi peminjaman berdasarkan mahasiswa',
    'alat-rusak' => 'Laporan rincian alat yang dikembalikan dalam kondisi rusak',
    default => 'Export data laboratorium',
})

@section('header-actions')
<div style="display:flex;gap:8px;align-items:center">
    <a href="{{ route('admin.laporan.export', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn btn-outline btn-sm" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;font-size:12px">Export Excel</a>
    <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn btn-sm" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;font-size:12px;background:#2563EB;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none">Cetak Laporan PDF</a>
</div>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.laporan.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:20px">
    <div style="display:flex;flex-direction:column;gap:4px">
        <label style="font-size:12px;font-weight:600;color:#374151">PILIH JENIS LAPORAN</label>
        <select name="tab" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:260px">
            <option value="rekap-peminjaman" @selected(request('tab', 'rekap-peminjaman')==='rekap-peminjaman')>Rekap Peminjaman</option>
            <option value="alat-sering-dipinjam" @selected(request('tab')==='alat-sering-dipinjam')>Alat Sering Dipinjam</option>
            <option value="alat-dipinjam" @selected(request('tab')==='alat-dipinjam')>Alat Sedang Dipinjam</option>
            <option value="log-mutasi-stok" @selected(request('tab')==='log-mutasi-stok')>Log Mutasi Stok</option>
            <option value="inventaris-barang" @selected(request('tab')==='inventaris-barang')>Laporan Inventaris Barang</option>
            <option value="rekap-per-mahasiswa" @selected(request('tab')==='rekap-per-mahasiswa')>Rekapitulasi Per Mahasiswa</option>
            <option value="alat-rusak" @selected(request('tab')==='alat-rusak')>Laporan Alat Rusak</option>
        </select>
    </div>
    <div style="display:flex;flex-direction:column;gap:4px">
        <label style="font-size:12px;font-weight:600;color:#374151">START DATE</label>
        <input type="date" name="from" value="{{ request('from') }}" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:160px">
    </div>
    <div style="display:flex;flex-direction:column;gap:4px">
        <label style="font-size:12px;font-weight:600;color:#374151">END DATE</label>
        <input type="date" name="to" value="{{ request('to') }}" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:160px">
    </div>
    @if(request('tab') === 'log-mutasi-stok')
    <div style="display:flex;flex-direction:column;gap:4px">
        <label style="font-size:12px;font-weight:600;color:#374151">JENIS MUTASI</label>
        <select name="tipe_mutasi" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:140px">
            <option value="">Semua</option>
            <option value="Masuk" @selected(request('tipe_mutasi')==='Masuk')>Masuk</option>
            <option value="Keluar" @selected(request('tipe_mutasi')==='Keluar')>Keluar</option>
            <option value="Penyesuaian" @selected(request('tipe_mutasi')==='Penyesuaian')>Penyesuaian</option>
        </select>
    </div>
    @endif
    @if(request('tab') === 'inventaris-barang')
    <div style="display:flex;flex-direction:column;gap:4px">
        <label style="font-size:12px;font-weight:600;color:#374151">KONDISI</label>
        <select name="kondisi" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:120px">
            <option value="">Semua</option>
            <option value="Baik" @selected(request('kondisi')==='Baik')>Baik</option>
            <option value="Rusak Ringan" @selected(request('kondisi')==='Rusak Ringan')>Rusak Ringan</option>
            <option value="Rusak Berat" @selected(request('kondisi')==='Rusak Berat')>Rusak Berat</option>
        </select>
    </div>
    <div style="display:flex;flex-direction:column;gap:4px">
        <label style="font-size:12px;font-weight:600;color:#374151">KATEGORI</label>
        <select name="kategori" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:120px">
            <option value="">Semua</option>
            @foreach(\App\Models\Item::select('kategori')->distinct()->pluck('kategori') as $kat)
            <option value="{{ $kat }}" @selected(request('kategori')===$kat)>{{ $kat }}</option>
            @endforeach
        </select>
    </div>
    @endif
    @if(request('from') || request('to') || request('tipe_mutasi') || request('kondisi') || request('kategori'))
    <a href="{{ route('admin.laporan.index', ['tab' => request('tab', 'rekap-peminjaman')]) }}" style="padding:8px 14px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#374151;text-decoration:none;font-weight:500">Reset</a>
    @endif
</form>

@if($error)
<div style="padding:12px 16px;background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;color:#991B1B;font-size:13px;font-weight:500;margin-bottom:20px">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="margin-right:6px;vertical-align:middle"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ $error }}
</div>
@endif

{{-- REKAP PEMINJAMAN --}}
@if(request('tab', 'rekap-peminjaman') === 'rekap-peminjaman')
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#EEF2FF,#E0E7FF);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#3B82F6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalLaporan }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Laporan</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#ECFDF5,#D1FAE5);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalDisetujui }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Disetujui</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#FEF2F2,#FEE2E2);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#EF4444;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalDitolak }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Ditolak</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#ECFDF5,#D1FAE5);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalDikembalikan }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Dikembalikan</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;margin-bottom:20px">
    <div class="card">
        <h3 style="font-size:14px;font-weight:600;color:#1A1A2E;margin:0 0 16px">Tren Peminjaman Bulanan</h3>
        <div style="display:flex;align-items:flex-end;gap:6px;height:160px;padding:0 4px">
            @php
            $maxChart = $chartData ? max($chartData) : 1;
            $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            @endphp
            @foreach($chartData as $i => $val)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
                <div style="font-size:10px;font-weight:600;color:#6B7280">{{ $val }}</div>
                <div style="width:100%;height:{{ max(4, ($val / $maxChart) * 120) }}px;border-radius:4px 4px 0 0;background:linear-gradient(180deg,#3B82F6,#60A5FA);transition:height .3s;min-height:4px"></div>
                <div style="font-size:9px;color:#9CA3AF;font-weight:500">{{ $months[$i] ?? $i+1 }}</div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="card" style="background:linear-gradient(135deg,#1E3A5F,#1A2D4A);border:none;padding:20px">
        <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0 0 4px">Ekspor Laporan</h3>
        <p style="font-size:12px;color:rgba(255,255,255,.6);margin:0 0 20px;line-height:1.5">Unduh data laporan dalam format resmi untuk keperluan administrasi dan arsip Universitas.</p>
        <a href="{{ route('admin.laporan.export', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn" style="width:100%;padding:10px;justify-content:center;background:rgba(255,255,255,.15);color:#fff;margin-bottom:8px;border:1px solid rgba(255,255,255,.2);{{ $totalLaporan == 0 ? 'pointer-events:none;opacity:.5' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            @if($totalLaporan == 0) Tidak ada data untuk diekspor @else Unduh CSV (Excel) @endif
        </a>
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn" style="width:100%;padding:10px;justify-content:center;background:#3B82F6;color:#fff;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Laporan PDF
        </a>
    </div>
</div>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>ID PINJAM</th>
                    <th>PEMINJAM</th>
                    <th>NAMA ALAT</th>
                    <th>TGL PINJAM</th>
                    <th>TGL KEMBALI</th>
                    <th>STATUS</th>
                    <th style="text-align:center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($data['borrowings'] ?? []) as $b)
                <tr>
                    <td style="font-weight:600;color:#1E3A5F;font-size:12px">#{{ $b->id_borrowing }}</td>
                    <td style="font-weight:500;color:#1A1A2E">{{ $b->mahasiswa?->nama_lengkap }}</td>
                    <td style="font-size:12px;color:#6B7280">
                        @php
                        $items = $b->borrowingItems->take(3);
                        $more = $b->borrowingItems->count() - 3;
                        @endphp
                        @foreach($items as $bi)
                        <span style="display:inline-block;background:#F3F4F6;padding:1px 6px;border-radius:4px;margin:1px 2px;font-size:11px">{{ $bi->tool?->nama_alat ?? '-' }}</span>
                        @endforeach
                        @if($more > 0)
                        <span style="font-size:11px;color:#9CA3AF">+{{ $more }} lainnya</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#6B7280;white-space:nowrap">{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                    <td style="font-size:12px;color:#6B7280;white-space:nowrap">{{ $b->tgl_rencana_kembali?->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $st = strtoupper(trim($b->status ?? ''));
                        $badgeClass = match($st) {
                            'MENUNGGU' => 'badge-yellow',
                            'DISETUJUI' => 'badge-blue',
                            'DITOLAK' => 'badge-red',
                            'DIPINJAM' => 'badge-purple',
                                    'TERLAMBAT' => 'badge-danger',
                            'DIKEMBALIKAN' => 'badge-green',
                            default => 'badge-gray',
                        };
                        $statusLabel = match($st) {
                            'MENUNGGU' => 'Menunggu',
                            'DISETUJUI' => 'Disetujui',
                            'DITOLAK' => 'Ditolak',
                            'DIPINJAM' => 'Dipinjam',
                                    'TERLAMBAT' => 'Terlambat',
                            'DIKEMBALIKAN' => 'Dikembalikan',
                            default => $b->status,
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('admin.peminjaman.show', $b->id_borrowing) }}" class="btn btn-outline btn-sm" style="width:32px;height:32px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" title="Detail Peminjaman">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada data ditemukan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(($data['borrowings'] ?? collect())->hasPages())
    <div style="padding:12px 16px;border-top:1px solid #E5E7EB">
        {{ $data['borrowings']->appends(request()->query())->links() }}
    </div>
    @endif
</div>


{{-- ALAT SERING DIPINJAM --}}
@elseif(request('tab') === 'alat-sering-dipinjam')
<div style="display:flex;flex-direction:column;gap:20px">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <div class="card" style="padding:0">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #E5E7EB">
                <h3 style="font-size:15px;font-weight:700;color:#1A1A2E;margin:0">Peringkat Peminjaman</h3>
                <span style="font-size:11px;color:#10B981;font-weight:600;display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:#10B981;display:inline-block;animation:pulse 2s infinite"></span>
                    Real-Time data
                </span>
            </div>
            <style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}</style>
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th style="text-align:center;width:48px">Peringkat</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th style="text-align:center">Total Pinjam</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data['popularTools'] ?? []) as $item)
                        <tr>
                            <td style="text-align:center;font-weight:700;color:#1E3A5F;font-size:14px">{{ $loop->iteration }}</td>
                            <td style="font-weight:500;color:#1A1A2E">{{ $item['tool']?->nama_alat ?? '-' }}</td>
                            <td style="font-size:12px;color:#6B7280">{{ $item['tool']?->kategori ?? '-' }}</td>
                            <td style="text-align:center;font-weight:600;color:#1A1A2E">{{ $item['total_dipinjam'] }}</td>
                            <td style="text-align:center">
                                <span style="display:inline-block;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:500;{{ in_array($item['status'], ['DIPINJAM', 'TERLAMBAT']) ? 'background:#FEF2F2;color:#EF4444' : 'background:#ECFDF5;color:#10B981' }}">{{ $item['status'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state">Tidak ada data ditemukan.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(count($data['popularTools'] ?? []) > 0 && $totalAlat > count($data['popularTools']))
            <div style="padding:10px 20px;border-top:1px solid #E5E7EB;text-align:center">
                <a href="{{ route('admin.laporan.index', array_merge(request()->query(), ['tab' => 'inventaris-barang'])) }}" style="font-size:13px;font-weight:500;color:#2563EB;text-decoration:none">Lihat semua total alat &rarr;</a>
            </div>
            @endif
        </div>
        <div class="card">
            <h3 style="font-size:15px;font-weight:700;color:#1A1A2E;margin:0 0 20px">Top 10 alat menggunakan grafik</h3>
            @php
            $tools = collect($data['popularTools'] ?? []);
            $maxVal = $tools->max('total_dipinjam') ?: 1;
            @endphp
            <div style="display:flex;flex-direction:column;gap:8px">
                @forelse($tools as $item)
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:11px;font-weight:600;color:#6B7280;width:20px;text-align:right;flex-shrink:0">{{ $loop->iteration }}.</span>
                    <div style="flex:1;display:flex;flex-direction:column;gap:4px">
                        <div style="font-size:12px;font-weight:600;color:#1A1A2E">{{ $item['tool']?->nama_alat ?? '-' }}</div>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="flex:1;height:16px;border-radius:4px;background:#F3F4F6;overflow:hidden">
                                <div style="height:100%;width:{{ ($item['total_dipinjam'] / $maxVal) * 100 }}%;border-radius:4px;background:linear-gradient(90deg,#3B82F6,#60A5FA);transition:width .4s"></div>
                            </div>
                            <span style="font-size:11px;font-weight:600;color:#1A1A2E;white-space:nowrap;width:28px;text-align:right">{{ $item['total_dipinjam'] }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:20px 0">Tidak ada data untuk grafik.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ALAT SEDANG DIPINJAM --}}
@elseif(request('tab') === 'alat-dipinjam')
<div style="display:flex;flex-direction:column;gap:14px">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">
        <div class="card" style="display:flex;align-items:center;gap:14px;padding:16px 20px">
            <div style="width:40px;height:40px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalPinjamanAktif }}</div>
                <div style="font-size:12px;color:#6B7280;font-weight:500">Total Pinjaman Aktif</div>
            </div>
        </div>
        <div class="card" style="display:flex;align-items:center;gap:14px;padding:16px 20px">
            <div style="width:40px;height:40px;border-radius:10px;background:#FEF2F2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#EF4444" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2 1.732 2z"/></svg>
            </div>
            <div>
                <div style="font-size:22px;font-weight:800;color:#DC2626">{{ $totalTerlambat }}</div>
                <div style="font-size:12px;color:#6B7280;font-weight:500">Terlambat Kembali</div>
            </div>
        </div>
        <div class="card" style="display:flex;align-items:center;gap:14px;padding:16px 20px">
            <div style="width:40px;height:40px;border-radius:10px;background:#FFF7ED;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $kembaliHariIni }}</div>
                <div style="font-size:12px;color:#6B7280;font-weight:500">Kembali Hari Ini</div>
            </div>
        </div>
        <div class="card" style="display:flex;align-items:center;gap:14px;padding:16px 20px">
            <div style="width:40px;height:40px;border-radius:10px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#8B5CF6" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $peminjamUnik }}</div>
                <div style="font-size:12px;color:#6B7280;font-weight:500">Peminjam Unik</div>
            </div>
        </div>
    </div>

    <div class="card" style="padding:0">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #E5E7EB">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:15px;font-weight:700;color:#1A1A2E">Data Transaksi</span>
                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:10px;font-weight:600;background:#ECFDF5;color:#059669;letter-spacing:.02em">
                    <span style="width:5px;height:5px;border-radius:50%;background:#059669;display:inline-block;animation:pulse 2s infinite"></span>
                    Live Update
                </span>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <div style="display:flex;align-items:center;gap:4px;padding:5px 10px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:11px;font-weight:500;color:#374151;cursor:pointer">
                    Semua Status
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <svg width="15" height="15" fill="none" stroke="#6B7280" viewBox="0 0 24 24" stroke-width="2" style="cursor:pointer"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>ID PINJAM</th>
                        <th>PEMINJAM</th>
                        <th>NAMA ALAT</th>
                        <th>TGL PINJAM</th>
                        <th>ESTIMASI KEMBALI</th>
                        <th>STATUS</th>
                        <th style="text-align:center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($data['activeBorrowings'] ?? []) as $b)
                    @php $overdue = $b->is_overdue; @endphp
                    <tr>
                        <td style="font-weight:600;color:#1E3A5F;font-size:12px;white-space:nowrap">#{{ $b->id_borrowing }}</td>
                        <td>
                            <div style="font-weight:600;color:#1A1A2E;font-size:13px">{{ $b->mahasiswa?->nama_lengkap }}</div>
                            <div style="font-size:11px;color:#9CA3AF;margin-top:1px">{{ $b->mahasiswa?->nim ?? '-' }} &mdash; {{ ucfirst($b->mahasiswa?->role ?? '') }}</div>
                        </td>
                        <td style="font-size:12px;color:#6B7280">
                            @foreach($b->borrowingItems->take(2) as $bi)
                            <span style="display:inline-block;background:#F3F4F6;padding:2px 6px;border-radius:4px;margin:1px;font-size:11px">{{ $bi->tool?->nama_alat ?? '-' }}</span>
                            @endforeach
                            @if($b->borrowingItems->count() > 2)
                            <span style="font-size:11px;color:#9CA3AF">+{{ $b->borrowingItems->count() - 2 }} lainnya</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#6B7280;white-space:nowrap">{{ $b->tgl_rencana_pinjam?->format('d/m/Y') }}</td>
                        <td style="font-size:12px;white-space:nowrap;font-weight:{{ $overdue ? '600' : '400' }};color:{{ $overdue ? '#DC2626' : '#6B7280' }}">
                            {{ $b->tgl_rencana_kembali?->format('d/m/Y') }}
                            @if($overdue)
                            <span style="margin-left:4px;font-size:10px;color:#DC2626">● Terlambat</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $st = strtoupper(trim($b->status ?? ''));
                            if ($overdue) {
                                $badgeBg = '#FEF2F2'; $badgeColor = '#DC2626'; $label = 'TERLAMBAT';
                            } elseif ($stin_array($st, ['DIPINJAM', 'TERLAMBAT'])) {
                                $badgeBg = '#F5F3FF'; $badgeColor = '#7C3AED'; $label = 'DIPINJAM';
                            } else {
                                $badgeBg = '#EFF6FF'; $badgeColor = '#2563EB'; $label = $b->status ?? 'DISETUJUI';
                            }
                            @endphp
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeColor }};white-space:nowrap">
                                <span style="width:5px;height:5px;border-radius:50%;background:{{ $badgeColor }}"></span>
                                {{ $label }}
                            </span>
                        </td>
                        <td style="text-align:center">
                            <svg width="15" height="15" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24" stroke-width="2" style="cursor:pointer;vertical-align:middle"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state">Tidak ada data ditemukan.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-top:1px solid #E5E7EB;font-size:12px;color:#6B7280">
            <span>Menampilkan semua data.</span>
            <div style="display:flex;align-items:center;gap:4px">
                <span style="padding:4px 10px;border:1px solid #E5E7EB;border-radius:6px;cursor:pointer;font-size:11px;color:#374151">&laquo;</span>
                <span style="padding:4px 10px;border:1px solid #2563EB;border-radius:6px;background:#2563EB;color:#fff;font-weight:600;font-size:11px">1</span>
                <span style="padding:4px 10px;border:1px solid #E5E7EB;border-radius:6px;cursor:pointer;font-size:11px;color:#374151">2</span>
                <span style="padding:4px 10px;border:1px solid #E5E7EB;border-radius:6px;cursor:pointer;font-size:11px;color:#374151">3</span>
                <span style="padding:4px 10px;border:1px solid #E5E7EB;border-radius:6px;cursor:pointer;font-size:11px;color:#374151">&raquo;</span>
            </div>
        </div>
    </div>
</div>

{{-- LOG MUTASI STOK --}}
@elseif(request('tab') === 'log-mutasi-stok')
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
    <div style="flex:1;min-width:180px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#EEF2FF,#E0E7FF);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#3B82F6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 13l5 5m0 0l5-5"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalPergerakan }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Pergerakan</div>
        </div>
    </div>
    <div style="flex:1;min-width:180px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#ECFDF5,#D1FAE5);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalMasuk }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Barang Masuk</div>
        </div>
    </div>
    <div style="flex:1;min-width:180px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#FEF2F2,#FEE2E2);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#EF4444;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 15l6-6 6 6"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalKeluar }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Barang Keluar</div>
        </div>
    </div>
</div>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>TANGGAL &amp; WAKTU</th>
                    <th>NAMA ALAT</th>
                    <th>PETUGAS</th>
                    <th>JENIS</th>
                    <th>JUMLAH</th>
                    <th>STOK AWAL</th>
                    <th>STOK AKHIR</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($data['mutations'] ?? collect()) as $m)
                <tr>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280">{{ $m->time_stamp ? \Carbon\Carbon::parse($m->time_stamp)->format('d/m/Y H:i') : '-' }}</td>
                    <td style="font-weight:500;color:#1A1A2E">{{ $m->item?->nama_barang ?? '-' }}</td>
                    <td style="font-size:12px;color:#6B7280">{{ $m->admin?->nama_lengkap ?? '-' }}</td>
                    <td>
                        @php
                        $mBadge = match($m->tipe_mutasi) {
                            'Masuk' => 'badge-green',
                            'Keluar' => 'badge-red',
                            'Penyesuaian' => 'badge-blue',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $mBadge }}">{{ $m->tipe_mutasi }}</span>
                    </td>
                    <td style="font-weight:600">{{ $m->jumlah }}</td>
                    <td style="text-align:right;font-weight:500">{{ $m->stok_sebelum }}</td>
                    <td style="font-weight:700;color:#1E3A5F">{{ $m->stok_sesudah }}</td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:#6B7280">{{ $m->keterangan }}</td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">Tidak ada data mutasi ditemukan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(($data['mutations'] ?? collect())->hasPages())
    <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid #E5E7EB;font-size:13px;color:#6B7280">
        <div>Menampilkan semua {{ ($data['mutations'] ?? collect())->total() }} data</div>
        <div>{{ ($data['mutations'] ?? collect())->appends(request()->query())->links() }}</div>
    </div>
    @endif
</div>

{{-- INVENTARIS BARANG --}}
@elseif(request('tab') === 'inventaris-barang')
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#EEF2FF,#E0E7FF);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#3B82F6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalBarang }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Barang</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#F5F3FF,#EDE9FE);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#8B5CF6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalStok }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Stok</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#ECFDF5,#D1FAE5);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $kondisiBaik }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Kondisi Baik</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#FEF2F2,#FEE2E2);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#EF4444;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2 1.732 2z"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#DC2626">{{ $kondisiRusak }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Rusak</div>
        </div>
    </div>
</div>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr><th>KODE</th><th>NAMA BARANG</th><th>KATEGORI</th><th>KONDISI</th><th>LOKASI</th><th>STOK</th><th style="text-align:center">AKSI</th></tr>
            </thead>
            <tbody>
                @forelse(($data['items'] ?? []) as $item)
                <tr>
                    <td style="font-size:12px;font-weight:500;color:#6B7280">{{ $item->kode_barang }}</td>
                    <td style="font-weight:600;color:#1A1A2E">{{ $item->nama_barang }}</td>
                    <td><span style="display:inline-block;background:#F3F4F6;padding:2px 8px;border-radius:4px;font-size:11px;color:#374151">{{ $item->kategori }}</span></td>
                    <td>
                        @php
                        $kBadge = match($item->kondisi) {
                            'Baik' => 'badge-green',
                            'Rusak Ringan' => 'badge-yellow',
                            'Rusak Berat' => 'badge-red',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $kBadge }}">{{ $item->kondisi }}</span>
                    </td>
                    <td style="font-size:12px;color:#6B7280">{{ $item->lokasi }}</td>
                    <td style="font-weight:700;color:#1E3A5F;font-size:14px">{{ $item->stok }}</td>
                    <td style="text-align:center">
                        <a href="{{ route('admin.inventaris.detail', $item->id_barang) }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;color:#6B7280;text-decoration:none" title="Detail Barang">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state">Tidak ada data ditemukan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @php $p = $data['items'] ?? null; $lastPage = $p ? $p->lastPage() : 0; @endphp
    <div style="padding:12px 16px;border-top:1px solid #E5E7EB">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
            <div style="font-size:13px;color:#6B7280">
                @if($p)
                Menampilkan <span style="font-weight:600;color:#374151">{{ $p->firstItem() }}</span>-
                <span style="font-weight:600;color:#374151">{{ $p->lastItem() }}</span>
                dari <span style="font-weight:600;color:#374151">{{ $p->total() }}</span> hasil
                @else
                Menampilkan 0 hasil
                @endif
            </div>
            <div style="display:flex;gap:4px;flex-wrap:wrap">
                @if($p && $lastPage > 0)
                @if($p->onFirstPage())
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                @else
                <a href="{{ $p->previousPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&laquo;</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                @if($i == $p->currentPage())
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">{{ $i }}</span>
                @else
                <a href="{{ $p->url($i) }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">{{ $i }}</a>
                @endif
                @endfor
                @if($p->hasMorePages())
                <a href="{{ $p->nextPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&raquo;</a>
                @else
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&raquo;</span>
                @endif
                @else
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#6B7280;cursor:default">&laquo;</span>
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">1</span>
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#6B7280;cursor:default">&raquo;</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- REKAP MAHASISWA --}}
@elseif(request('tab') === 'rekap-per-mahasiswa')
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap">
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#EEF2FF,#E0E7FF);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#3B82F6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $totalMahasiswa }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Total Mahasiswa</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#ECFDF5,#D1FAE5);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 11l-4 4-2-2"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $statusAktif }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Status Aktif</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#EEF2FF,#E0E7FF);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#3B82F6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#1A1A2E">{{ $peminjamAktif }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Peminjam Aktif</div>
        </div>
    </div>
    <div style="flex:1;min-width:160px;padding:16px 20px;border-radius:10px;background:linear-gradient(135deg,#FEF2F2,#FEE2E2);display:flex;align-items:center;gap:14px">
        <div style="width:42px;height:42px;border-radius:10px;background:#EF4444;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2 1.732 2z"/></svg>
        </div>
        <div>
            <div style="font-size:22px;font-weight:800;color:#DC2626">{{ $terlambatKembali }}</div>
            <div style="font-size:12px;color:#6B7280;font-weight:500">Terlambat Kembali</div>
        </div>
    </div>
</div>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr><th>NIM</th><th>NAMA MAHASISWA</th><th>PROGRAM STUDI</th><th>FREKUENSI PINJAM</th><th>STATUS AKTIF</th><th style="text-align:center">AKSI</th></tr>
            </thead>
            <tbody>
                @forelse(($data['users'] ?? []) as $u)
                <tr>
                    <td style="font-size:12px;font-weight:500;color:#6B7280">{{ $u->nim ?? '-' }}</td>
                    <td style="font-weight:600;color:#1A1A2E">{{ $u->nama_lengkap }}</td>
                    <td><span style="display:inline-block;background:#F3F4F6;padding:2px 8px;border-radius:4px;font-size:11px;color:#374151">{{ $u->program_studi ?? '-' }}</span></td>
                    <td style="font-weight:700;color:#1E3A5F;text-align:center">{{ $u->total_peminjaman }}</td>
                    <td>
                        @if($u->is_active)
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:#ECFDF5;color:#10B981">
                            <span style="width:5px;height:5px;border-radius:50%;background:#10B981"></span>
                            Aktif
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:#FEF2F2;color:#EF4444">
                            <span style="width:5px;height:5px;border-radius:50%;background:#EF4444"></span>
                            Nonaktif
                        </span>
                        @endif
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('admin.users.index') }}?search={{ $u->nim }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;color:#6B7280;text-decoration:none" title="Detail Mahasiswa">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">Tidak ada data ditemukan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @php $pu = $data['users'] ?? null; $lastPageU = $pu ? $pu->lastPage() : 0; @endphp
    <div style="padding:12px 16px;border-top:1px solid #E5E7EB">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#6B7280">
                <span>Baris Perhalaman</span>
                <form method="GET" action="{{ route('admin.laporan.index') }}" id="perPageForm">
                    <input type="hidden" name="tab" value="rekap-per-mahasiswa">
                    <select name="per_page" onchange="this.form.submit()" style="padding:4px 8px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;outline:none;font-family:'Inter',sans-serif">
                        <option value="10" @selected(($pu ? $pu->perPage() : 10) == 10)>10</option>
                        <option value="25" @selected(($pu ? $pu->perPage() : 10) == 25)>25</option>
                        <option value="50" @selected(($pu ? $pu->perPage() : 10) == 50)>50</option>
                        <option value="100" @selected(($pu ? $pu->perPage() : 10) == 100)>100</option>
                    </select>
                </form>
            </div>
            <div style="display:flex;gap:4px;flex-wrap:wrap">
                @if($pu && $lastPageU > 0)
                @if($pu->onFirstPage())
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                @else
                <a href="{{ $pu->previousPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&laquo;</a>
                @endif
                @for($i = 1; $i <= $lastPageU; $i++)
                @if($i == $pu->currentPage())
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">{{ $i }}</span>
                @else
                <a href="{{ $pu->url($i) }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">{{ $i }}</a>
                @endif
                @endfor
                @if($pu->hasMorePages())
                <a href="{{ $pu->nextPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&raquo;</a>
                @else
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&raquo;</span>
                @endif
                @else
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">1</span>
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&raquo;</span>
                @endif
            </div>
        </div>
    </div>
{{-- LAPORAN ALAT RUSAK --}}
@elseif(request('tab') === 'alat-rusak')
<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>TANGGAL PENGEMBALIAN</th>
                    <th>NAMA ALAT</th>
                    <th>NAMA PEMINJAM</th>
                    <th>NAMA PETUGAS</th>
                    <th style="text-align:center">STOK RUSAK</th>
                    <th>KONDISI BARANG</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($data['damagedTools'] ?? []) as $item)
                <tr>
                    <td style="font-size:13px;color:#6B7280">{{ $item->borowing?->tgl_pengembalian_aktual ? $item->borowing->tgl_pengembalian_aktual->format('d M Y H:i') : '-' }}</td>
                    <td style="font-weight:600;color:#1A1A2E">{{ $item->tool?->nama_alat ?? '-' }}</td>
                    <td style="font-size:13px">{{ $item->borowing?->mahasiswa?->nama_lengkap ?? '-' }}</td>
                    <td style="font-size:13px">{{ $item->borowing?->prosesOleh?->nama_lengkap ?? '-' }}</td>
                    <td style="font-weight:700;color:#DC2626;text-align:center">{{ $item->jumlah_unit }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;background:#FEF2F2;color:#DC2626">
                            {{ $item->kondisi_saat_kembali }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">Tidak ada data ditemukan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @php $pu = $data['damagedTools'] ?? null; $lastPageU = $pu ? $pu->lastPage() : 0; @endphp
    <div style="padding:12px 16px;border-top:1px solid #E5E7EB">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#6B7280">
                <span>Baris Perhalaman</span>
                <form method="GET" action="{{ route('admin.laporan.index') }}" id="perPageForm">
                    <input type="hidden" name="tab" value="alat-rusak">
                    <select name="per_page" onchange="this.form.submit()" style="padding:4px 8px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;outline:none;font-family:'Inter',sans-serif">
                        <option value="15" @selected(($pu ? $pu->perPage() : 15) == 15)>15</option>
                        <option value="25" @selected(($pu ? $pu->perPage() : 15) == 25)>25</option>
                        <option value="50" @selected(($pu ? $pu->perPage() : 15) == 50)>50</option>
                        <option value="100" @selected(($pu ? $pu->perPage() : 15) == 100)>100</option>
                    </select>
                </form>
            </div>
            <div style="display:flex;gap:4px;flex-wrap:wrap">
                @if($pu && $lastPageU > 0)
                @if($pu->onFirstPage())
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                @else
                <a href="{{ $pu->previousPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&laquo;</a>
                @endif
                @for($i = 1; $i <= $lastPageU; $i++)
                @if($i == $pu->currentPage())
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">{{ $i }}</span>
                @else
                <a href="{{ $pu->url($i) }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">{{ $i }}</a>
                @endif
                @endfor
                @if($pu->hasMorePages())
                <a href="{{ $pu->nextPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&raquo;</a>
                @else
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&raquo;</span>
                @endif
                @else
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">1</span>
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&raquo;</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

