@extends('layouts.app')
@section('title', 'Audit Trail')

@section('content')
<form method="GET" action="{{ route('admin.audit.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Modul</label>
            <select name="modul" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach(['User','Alat','Barang','Peminjaman','Mutasi'] as $m)
                <option value="{{ $m }}" @selected(request('modul')===$m)>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-item">
            <label>Aksi</label>
            <select name="aksi" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach(['Buat','Ubah','Hapus','Setuju','Tolak','Kembali'] as $a)
                <option value="{{ $a }}" @selected(request('aksi')===$a)>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-item">
            <label>Pelaku</label>
            <input type="text" name="pelaku" value="{{ request('pelaku') }}" placeholder="Nama...">
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div class="card">
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>Modul</th><th>Aksi</th><th>Dilakukan Oleh</th><th>IP Address</th><th>Timestamp</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        @php
                            $modBadge = match($log->modul) {
                                'User' => 'badge-blue',
                                'Alat' => 'badge-green',
                                'Barang' => 'badge-yellow',
                                'Peminjaman' => 'badge-purple',
                                'Mutasi' => 'badge-red',
                                default => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $modBadge }}">{{ $log->modul }}</span>
                    </td>
                    <td><span style="font-weight:500;color:#1A1A2E">{{ $log->aksi }}</span></td>
                    <td>{{ $log->dilakukan_oleh }}</td>
                    <td><code style="font-size:12px;background:#F3F4F6;padding:2px 8px;border-radius:4px;color:#6B7280">{{ $log->ip_address }}</code></td>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280">{{ $log->time_stamp?->format('d/m/Y H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-state">Tidak ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())<div class="pagination">{{ $logs->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
