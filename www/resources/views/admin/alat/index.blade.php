@extends('layouts.app')
@section('title', 'Manajemen Alat')
@section('subtitle', 'Daftar dan kelola alat laboratorium')

@section('header-actions')
<a href="{{ route('admin.alat.create') }}" class="btn btn-sm">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Alat
</a>
@endsection

@section('content')
<div class="card" style="padding:16px;margin-bottom:20px">
    <form method="GET" action="{{ route('admin.alat.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:end">
        <div style="flex:1;min-width:180px">
            <label style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Cari</label>
            <input type="text" name="search" placeholder="Nama atau kode alat..." value="{{ request('search') }}" style="width:100%;padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif;outline:none">
        </div>
        <div style="min-width:150px">
            <label style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Kategori</label>
            <select name="kategori" onchange="this.form.submit()" style="width:100%;padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif;outline:none;background:#fff">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:150px">
            <label style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px">Status</label>
            <select name="status_alat" onchange="this.form.submit()" style="width:100%;padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;font-family:'Inter',sans-serif;outline:none;background:#fff">
                <option value="">Semua Status</option>
                <option value="TERSEDIA" @selected(request('status_alat') === 'TERSEDIA')>Tersedia</option>
                <option value="MAINTENANCE" @selected(request('status_alat') === 'MAINTENANCE')>Maintenance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm" style="margin-bottom:0">Cari</button>
        <a href="{{ route('admin.alat.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:0">Reset</a>
    </form>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card" style="border-left:4px solid #1E3A5F">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $totalInventaris }}</div>
                <div class="stat-label" style="color:#6B7280">Total Inventaris</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#1E3A5F;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #10B981">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $kondisiBaik }}</div>
                <div class="stat-label" style="color:#6B7280">Kondisi Baik</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #8B5CF6">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $sedangDipinjam }}</div>
                <div class="stat-label" style="color:#6B7280">Sedang Dipinjam</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#8B5CF6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #EF4444">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $butuhPerbaikan }}</div>
                <div class="stat-label" style="color:#6B7280">Butuh Perbaikan</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#EF4444;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tools as $tool)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E">{{ $tool->kode_alat }}</td>
                    <td>{{ $tool->nama_alat }}</td>
                    <td>{{ $tool->kategori }}</td>
                    <td>{{ $tool->stok_tersedia }}/{{ $tool->stok_total }}</td>
                    <td>
                        @php
                        $badgeClass = match($tool->status_alat) {
                            'TERSEDIA' => 'badge-green',
                            'MAINTENANCE' => 'badge-red',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $tool->status_alat }}</span>
                    </td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('admin.alat.show', $tool->id_alat) }}" class="btn btn-outline btn-sm" style="padding:6px 8px" title="Detail">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.alat.edit', $tool->id_alat) }}" class="btn btn-outline btn-sm" style="padding:6px 8px" title="Edit">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.alat.destroy', $tool->id_alat) }}" style="display:inline" onsubmit="return confirm('Hapus alat {{ $tool->nama_alat }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="padding:6px 8px;color:#EF4444;border-color:#FECACA" title="Hapus">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"><div class="empty-state">Tidak ada alat ditemukan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($tools->hasPages())
    <div class="pagination">{{ $tools->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
