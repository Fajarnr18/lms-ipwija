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
            <thead><tr><th>Waktu</th><th>Pelaku</th><th>Modul</th><th>Aksi</th><th>Deskripsi</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="white-space:nowrap;font-size:.8rem">{{ $log->time_stamp?->format('d/m/Y H:i:s') }}</td>
                    <td style="font-size:.82rem">{{ $log->dilakukan_oleh }}</td>
                    <td>
                        @php
                            $modBadge = match($log->modul) {
                                'User' => 'background:#eef2ff;color:#4f46e5',
                                'Alat' => 'background:#ecfdf5;color:#059669',
                                'Barang' => 'background:#fffbeb;color:#d97706',
                                'Peminjaman' => 'background:#f3e8ff;color:#6b21a8',
                                'Mutasi' => 'background:#fce7f3;color:#be185d',
                                default => 'background:#f1f5f9;color:#475569',
                            };
                        @endphp
                        <span class="badge" style="{{ $modBadge }}">{{ $log->modul }}</span>
                    </td>
                    <td>{{ $log->aksi }}</td>
                    <td style="max-width:300px;white-space:normal;font-size:.82rem">{{ $log->deskripsi }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:#64748b">Tidak ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())<div class="pagination">{{ $logs->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
