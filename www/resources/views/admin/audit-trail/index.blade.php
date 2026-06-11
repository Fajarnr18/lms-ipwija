@extends('layouts.app')
@section('title', 'Audit Trail')
@section('subtitle', 'Log aktivitas sistem')

@section('content')
<div class="info-banner">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>Halaman ini bersifat read-only. Data audit trail tidak dapat diubah atau dihapus.</span>
</div>

<form method="GET" action="{{ route('admin.audit-trail.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Modul</label>
            <select name="modul" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach($moduls ?? ['User','Alat','Barang','Peminjaman','Mutasi'] as $m)
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
        <div class="toolbar-item">
            <label>Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari') }}">
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div class="card">
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pelaku</th>
                    <th>Role</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>IP Address</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="white-space:nowrap;font-size:12px;color:#6B7280">{{ $log->time_stamp?->format('d/m/Y H:i:s') }}</td>
                    <td style="font-weight:500;color:#1A1A2E">{{ $log->dilakukan_oleh }}</td>
                    <td><span class="badge {{ $log->role_pelaku === 'admin' ? 'badge-blue' : ($log->role_pelaku === 'dosen' ? 'badge-purple' : 'badge-green') }}">{{ ucfirst($log->role_pelaku ?? '-') }}</span></td>
                    <td>
                        <span class="badge" style="background:{{ match($log->modul) { 'Peminjaman' => '#EEF2FF', 'Alat' => '#ECFDF5', 'Barang' => '#FFFBEB', 'User' => '#F5F3FF', default => '#F3F4F6' } }};color:{{ match($log->modul) { 'Peminjaman' => '#1E4FD8', 'Alat' => '#059669', 'Barang' => '#D97706', 'User' => '#7C3AED', default => '#6B7280' } }}">
                            {{ $log->modul }}
                        </span>
                    </td>
                    <td><span style="font-weight:500;color:#1A1A2E">{{ $log->aksi }}</span></td>
                    <td><code style="font-size:12px;background:#F3F4F6;padding:2px 8px;border-radius:4px;color:#6B7280">{{ $log->ip_address }}</code></td>
                    <td style="text-align:center">
                        <button class="btn btn-outline btn-sm" onclick="showDetailModal({{ $log->id_log }})">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada log aktivitas.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="pagination">{{ $logs->appends(request()->query())->links() }}</div>
    @endif
</div>

@foreach($logs as $log)
<div class="modal-overlay" id="detailModal{{ $log->id_log }}">
    <div class="modal" style="max-width:600px">
        <h2>Detail Audit #{{ $log->id_log }}</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
            <div class="detail-item">
                <div class="label">Waktu</div>
                <div class="value">{{ $log->time_stamp?->format('d/m/Y H:i:s') }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Pelaku</div>
                <div class="value">{{ $log->dilakukan_oleh }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Modul</div>
                <div class="value">{{ $log->modul }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Aksi</div>
                <div class="value">{{ $log->aksi }}</div>
            </div>
            <div class="detail-item">
                <div class="label">ID Record</div>
                <div class="value">{{ $log->id_record }}</div>
            </div>
            <div class="detail-item">
                <div class="label">IP Address</div>
                <div class="value"><code style="font-size:12px;background:#F3F4F6;padding:2px 8px;border-radius:4px">{{ $log->ip_address }}</code></div>
            </div>
        </div>
        @if($log->data_sebelum)
        <div class="form-group">
            <label style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Data Sebelum</label>
            <pre style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:8px;padding:12px;font-size:12px;overflow-x:auto;max-height:200px">{{ json_encode(json_decode($log->data_sebelum), JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
        @if($log->data_sesudah)
        <div class="form-group">
            <label style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.04em">Data Sesudah</label>
            <pre style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:8px;padding:12px;font-size:12px;overflow-x:auto;max-height:200px">{{ json_encode(json_decode($log->data_sesudah), JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
        <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('detailModal{{ $log->id_log }}').classList.remove('show')">Tutup</button>
        </div>
    </div>
</div>
@endforeach

<script>
function showDetailModal(id) {
    document.getElementById('detailModal' + id).classList.add('show');
}
</script>
@endsection
