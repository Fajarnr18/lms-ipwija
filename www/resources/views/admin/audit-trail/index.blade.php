@extends('layouts.app')
@section('title', 'Audit Trail Sistem')
@section('subtitle', 'Pantau seluruh aktivitas pengguna dan perubahan data dalam sistem inventaris.')

@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <form method="GET" action="{{ route('admin.audit-trail.index') }}">
            @foreach(request()->except('search', 'page') as $key => $val)
            @if($val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endif
            @endforeach
            <input type="text" name="search" placeholder="Cari log, user, atau aksi..." value="{{ request('search') }}" style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'" oninput="this.form.submit()">
        </form>
    </div>
</div>
@endsection

@section('header-actions')
<div style="display:flex;gap:8px;align-items:center">
    <a href="{{ route('admin.audit-trail.export', request()->query()) }}" class="btn btn-outline btn-sm" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;font-size:12px">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Ekspor CSV
    </a>
    <a href="{{ route('admin.audit-trail.index') }}" class="btn btn-sm" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;font-size:12px;background:#2563EB;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Refresh Data
    </a>
</div>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.audit-trail.index') }}" style="margin-bottom:20px">
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
        <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:160px">
            <label style="font-size:12px;font-weight:600;color:#374151">RENTANG WAKTU</label>
            <div style="display:flex;gap:8px;align-items:center">
                <input type="date" name="dari" value="{{ request('dari') }}" placeholder="Dari" style="flex:1;padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:0">
                <span style="color:#9CA3AF;font-size:12px">—</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}" placeholder="Sampai" style="flex:1;padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif;min-width:0">
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;min-width:160px">
            <label style="font-size:12px;font-weight:600;color:#374151">PENGGUNA</label>
            <input type="text" name="pengguna" value="{{ request('pengguna') }}" placeholder="Nama pengguna..." style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif">
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;min-width:140px">
            <label style="font-size:12px;font-weight:600;color:#374151">MODUL</label>
            <select name="modul" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif">
                <option value="">Semua Modul</option>
                @foreach($moduls ?? [] as $m)
                <option value="{{ $m }}" @selected(request('modul')===$m)>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;align-items:flex-end;gap:8px">
            <button type="submit" class="btn btn-sm" style="padding:8px 10px;background:#2563EB;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;line-height:1" title="Filter">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </button>
            @if(request('dari') || request('sampai') || request('pengguna') || request('modul') || request('search'))
            <a href="{{ route('admin.audit-trail.index') }}" style="padding:8px 14px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#374151;text-decoration:none;font-weight:500">Reset Filter</a>
            @endif
        </div>
    </div>
</form>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead style="background:#1E3A5F">
                <tr>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">WAKTU</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">USER</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">ROLE</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">MODUL</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">AKSI</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">IP ADDRESS</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">KETERANGAN</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280;font-weight:500">
                        <span title="{{ $log->time_stamp?->format('d/m/Y H:i:s') }}">{{ $log->time_stamp?->diffForHumans() }}</span>
                    </td>
                    <td style="font-weight:600;color:#1A1A2E">{{ $log->dilakukan_oleh }}</td>
                    <td>
                        @php
                        $roleBadge = match(strtolower($log->role_pelaku ?? '')) {
                            'admin' => 'badge-blue',
                            'mahasiswa' => 'badge-green',
                            'dosen' => 'badge-purple',
                            default => 'badge-gray',
                        };
                        @endphp
                        <span class="badge {{ $roleBadge }}">{{ in_array(strtolower($log->role_pelaku ?? ''), ['admin', 'mahasiswa', 'dosen']) ? ucfirst($log->role_pelaku) : '-' }}</span>
                    </td>
                    <td>
                        @php
                        $modColors = ['bg' => '#DBEAFE', 'text' => '#000000'];
                        @endphp
                        <span style="display:inline-block;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $modColors['bg'] }};color:{{ $modColors['text'] }}">{{ $log->modul }}</span>
                    </td>
                    <td>
                        @php
                        $aksiColors = match(strtolower($log->aksi)) {
                            'buat', 'tambah', 'create' => ['bg' => '#22C55E', 'text' => '#fff'],
                            'ubah', 'edit', 'update', 'change_status' => ['bg' => '#FACC15', 'text' => '#000'],
                            'hapus', 'delete', 'reject_and_delete' => ['bg' => '#EF4444', 'text' => '#fff'],
                            'setuju', 'approve' => ['bg' => '#3B82F6', 'text' => '#fff'],
                            'tolak', 'reject' => ['bg' => '#EF4444', 'text' => '#fff'],
                            'kembali', 'return' => ['bg' => '#3B82F6', 'text' => '#fff'],
                            default => ['bg' => '#6B7280', 'text' => '#fff'],
                        };
                        @endphp
                        <span style="display:inline-block;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $aksiColors['bg'] }};color:{{ $aksiColors['text'] }}">{{ $log->aksi }}</span>
                    </td>
                    <td><code style="font-size:12px;background:#F3F4F6;padding:2px 8px;border-radius:4px;color:#6B7280">{{ $log->ip_address }}</code></td>
                    <td style="font-size:12px;color:#6B7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $log->aksi }} pada {{ $log->modul }} (ID: {{ $log->id_record }})</td>
                    <td style="text-align:center">
                        <a href="{{ route('admin.audit-trail.show', $log->id_log) }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1.5px solid #E5E7EB;background:#fff;color:#6B7280;text-decoration:none" title="Detail Log">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8"><div class="empty-state">Tidak ada log ditemukan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @php $p = $logs; $lastPg = $p->lastPage(); @endphp
    <div style="padding:12px 16px;border-top:1px solid #E5E7EB">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:#6B7280">
                <span>Menampilkan semua log</span>
                <form method="GET" action="{{ route('admin.audit-trail.index') }}" style="display:flex;align-items:center;gap:6px">
                    @foreach(request()->except('per_page', 'page') as $key => $val)
                    @if($val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                    @endforeach
                    <span style="font-size:12px;color:#9CA3AF">|</span>
                    <span>Baris</span>
                    <select name="per_page" onchange="this.form.submit()" style="padding:4px 8px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;outline:none;font-family:'Inter',sans-serif">
                        <option value="10" @selected($p->perPage() == 10)>10</option>
                        <option value="20" @selected($p->perPage() == 20)>20</option>
                        <option value="50" @selected($p->perPage() == 50)>50</option>
                        <option value="100" @selected($p->perPage() == 100)>100</option>
                    </select>
                </form>
            </div>
            <div style="display:flex;gap:4px;flex-wrap:wrap">
                @if($lastPg > 1)
                @if($p->onFirstPage())
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                @else
                <a href="{{ $p->previousPageUrl() }}" style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;text-decoration:none;color:#6B7280">&laquo;</a>
                @endif
                @for($i = 1; $i <= $lastPg; $i++)
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
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&laquo;</span>
                <span style="padding:6px 12px;border:1.5px solid #1E3A5F;border-radius:6px;font-size:12px;color:#fff;background:#1E3A5F;font-weight:600">1</span>
                <span style="padding:6px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#D1D5DB;opacity:.4">&raquo;</span>
                @endif
            </div>
        </div>
    </div>

    <div style="padding:10px 16px;border-top:1px solid #E5E7EB;font-size:12px;color:#9CA3AF;display:flex;align-items:center;gap:6px">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <span>Penyimpanan total log: <strong style="color:#374151">
            @php
            $bytes = $storageBytes ?? 0;
            if ($bytes >= 1099511627776) {
                echo number_format($bytes / 1099511627776, 2) . ' TB';
            } elseif ($bytes >= 1073741824) {
                echo number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                echo number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                echo number_format($bytes / 1024, 2) . ' KB';
            } else {
                echo $bytes . ' B';
            }
            @endphp
        </strong> ({{ number_format($totalLogs) }} log records)</span>
    </div>
</div>

@endsection