@extends('layouts.app')
@section('title', request('tab') === 'mutasi' ? '' : 'Inventaris Barang')
@section('subtitle', request('tab') === 'mutasi' ? '' : 'Kelola aset dan stok peralatan laboratorium secara real-time')

@section('header-search')
@if(request('tab') === 'mutasi')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <form method="GET" action="{{ route('admin.inventaris.index') }}">
            <input type="hidden" name="tab" value="mutasi">
            <input type="text" name="search" placeholder="Cari alat..." value="{{ request('search') }}" style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
        </form>
    </div>
</div>
@else
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <form method="GET" action="{{ route('admin.inventaris.index') }}">
            <input type="text" name="search" placeholder="Cari kode atau nama barang..." value="{{ request('search') }}" style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
        </form>
    </div>
</div>
@endif
@endsection

@section('header-actions')
@if(request('tab') === 'mutasi')
@else
<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
    <a href="{{ route('admin.inventaris.index', ['tab' => 'mutasi']) }}" class="btn btn-sm btn-outline {{ request('tab') === 'mutasi' ? 'active-tab' : '' }}" style="padding:8px 16px;{{ request('tab') === 'mutasi' ? 'background:#EEF2FF;border-color:#1E3A5F;color:#1E3A5F' : '' }}">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Log Mutasi
    </a>
    <a href="{{ route('admin.inventaris.create') }}" class="btn btn-sm" style="padding:8px 16px">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Barang
    </a>
</div>
@endif
@endsection

@section('content')
@if(request('tab') === 'mutasi')
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px">
    <div>
        <h2 style="font-size:20px;font-weight:700;color:#1A1A2E;margin:0">Log Mutasi Stok</h2>
        <p style="font-size:13px;color:#6B7280;margin:4px 0 0">Laporan real-time Mutasi Barang Universitas IPWija</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
        <a href="{{ route('admin.laporan.export', ['tab' => 'log-mutasi-stok', 'from' => request('from'), 'to' => request('to'), 'tipe_mutasi' => request('tipe_mutasi')]) }}" class="btn btn-sm btn-outline" style="padding:8px 16px">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </a>
        <button onclick="window.print()" class="btn btn-sm" style="padding:8px 16px;background:#3B82F6;color:#fff">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Laporan
        </button>
    </div>
</div>

<form method="GET" action="{{ route('admin.inventaris.index') }}">
    <input type="hidden" name="tab" value="mutasi">
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:20px">
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:12px;font-weight:600;color:#374151">Pilih Jenis Laporan</label>
            <select name="jenis_laporan" onchange="this.form.submit()" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:150px">
                <option value="">Log Mutasi Stok</option>
            </select>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:12px;font-weight:600;color:#374151">START DATE</label>
            <input type="date" name="from" value="{{ request('from') }}" onchange="this.form.submit()" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif">
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:12px;font-weight:600;color:#374151">END DATE</label>
            <input type="date" name="to" value="{{ request('to') }}" onchange="this.form.submit()" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif">
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:12px;font-weight:600;color:#374151">Jenis Mutasi</label>
            <select name="tipe_mutasi" onchange="this.form.submit()" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:130px">
                <option value="">Semua</option>
                <option value="Masuk" @selected(request('tipe_mutasi')==='Masuk')>Masuk</option>
                <option value="Keluar" @selected(request('tipe_mutasi')==='Keluar')>Keluar</option>
                <option value="Penyesuaian" @selected(request('tipe_mutasi')==='Penyesuaian')>Penyesuaian</option>
            </select>
        </div>
        @if(request('from') || request('to') || request('tipe_mutasi'))
        <a href="{{ route('admin.inventaris.index', ['tab' => 'mutasi']) }}" class="btn btn-sm btn-outline" style="padding:6px 14px">Reset</a>
        @endif
    </div>
</form>

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
                    <th>TANGGAL & WAKTU</th>
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
                @forelse($mutations as $m)
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
                <tr>
                    <td colspan="8"><div class="empty-state">Tidak ada data mutasi ditemukan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid #E5E7EB;font-size:13px;color:#6B7280">
        <div>Menampilkan semua {{ $mutations->total() }} data</div>
        <div>{{ $mutations->appends(request()->query())->links() }}</div>
    </div>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2FF">
            <svg width="20" height="20" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="stat-value">{{ $totalBarang }}</div>
        <div class="stat-label">Total Barang</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ECFDF5">
            <svg width="20" height="20" fill="none" stroke="#10B981" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $baikCount }}</div>
        <div class="stat-label">Kondisi Baik</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF3C7">
            <svg width="20" height="20" fill="none" stroke="#F59E0B" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-value">{{ $rusakCount }}</div>
        <div class="stat-label">Rusak Ringan/Berat</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF2F2">
            <svg width="20" height="20" fill="none" stroke="#EF4444" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
        </div>
        <div class="stat-value">{{ $totalStok }}</div>
        <div class="stat-label">Total Stok</div>
    </div>
</div>

<form method="GET" action="{{ route('admin.inventaris.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Kategori</label>
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($kategoris as $k)
                <option value="{{ $k }}" @selected(request('kategori')===$k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-item">
            <label>Kondisi</label>
            <select name="kondisi" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Baik" @selected(request('kondisi')==='Baik')>Baik</option>
                <option value="Rusak Ringan" @selected(request('kondisi')==='Rusak Ringan')>Rusak Ringan</option>
                <option value="Rusak Berat" @selected(request('kondisi')==='Rusak Berat')>Rusak Berat</option>
            </select>
        </div>
        @if(request('kategori') || request('kondisi'))
        <a href="{{ route('admin.inventaris.index', request()->only('search')) }}" class="btn btn-sm btn-outline" style="padding:6px 12px">Reset Filter</a>
        @endif
    </div>
</form>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="font-weight:600;color:#1E3A5F;font-size:12px">{{ $item->kode_barang }}</td>
                    <td>
                        <div style="font-weight:500;color:#1A1A2E">{{ $item->nama_barang }}</div>
                    </td>
                    <td><span class="badge badge-gray">{{ $item->kategori ?? '-' }}</span></td>
                    <td style="font-weight:700;font-size:15px;color:#1A1A2E">{{ $item->stok }}</td>
                    <td style="color:#6B7280;font-size:12px">{{ $item->satuan }}</td>
                    <td>
                        @php
                        $kondisiBadge = match($item->kondisi) {
                            'Baik' => 'badge-green',
                            'Rusak Ringan' => 'badge-yellow',
                            'Rusak Berat', 'Tidak Layak' => 'badge-red',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $kondisiBadge }}">{{ $item->kondisi }}</span>
                    </td>
                    <td style="font-size:12px;color:#6B7280">{{ $item->lokasi }}</td>
                    <td>
                        <div class="action-group" style="justify-content:center;flex-wrap:nowrap">
                            <a href="{{ route('admin.inventaris.edit', $item->id_barang) }}" class="btn btn-outline btn-sm" style="width:32px;height:32px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" title="Edit Barang">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="{{ route('admin.inventaris.mutasi', $item->id_barang) }}" class="btn btn-sm" style="width:32px;height:32px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;background:#8B5CF6" title="Catat Mutasi">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.inventaris.destroy', $item->id_barang) }}" style="display:inline;margin:0;padding:0">
                                @csrf @method('DELETE')
                                <button type="submit" data-confirm="Hapus barang {{ $item->nama_barang }}?" class="btn btn-outline btn-sm" style="width:32px;height:32px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:#DC2626;border-color:#FCA5A5" title="Hapus Barang">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            <a href="{{ route('admin.inventaris.detail', $item->id_barang) }}" class="btn btn-outline btn-sm" style="width:32px;height:32px;padding:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center" title="Detail Barang">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Tidak ada barang ditemukan.</p>
                            <a href="{{ route('admin.inventaris.create') }}" class="btn" style="margin-top:12px;display:inline-flex;align-items:center;gap:6px">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Tambah Barang Sekarang
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    {{ $items->appends(request()->query())->links() }}
    @endif
</div>
@endif

@if(request('tab') !== 'mutasi')
<div class="card" style="margin-top:24px">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
        <svg width="18" height="18" fill="none" stroke="#1E3A5F" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
        <h3 style="margin:0;font-size:14px;font-weight:600;color:#1E3A5F">Keterangan</h3>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:13px;color:#6B7280">
        <div style="display:flex;align-items:center;gap:6px">
            <span class="badge badge-green">Baik</span>
            <span>Barang dalam kondisi layak pakai</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <span class="badge badge-yellow">Rusak Ringan</span>
            <span>Barang mengalami kerusakan minor</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <span class="badge badge-red">Rusak Berat</span>
            <span>Barang tidak layak pakai</span>
        </div>
    </div>
</div>
@endif
@endsection