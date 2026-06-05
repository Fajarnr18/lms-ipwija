@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')

@section('content')
<style>
.d-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:1rem;margin-bottom:1.75rem}
.d-stat{position:relative;background:#fff;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid #f1f5f9;overflow:hidden;transition:all .25s}
.d-stat:hover{transform:translateY(-3px);border-color:#cbd5e1;box-shadow:0 8px 24px rgba(0,0,0,.06)}
@media(prefers-color-scheme:dark){.d-stat{background:#161615;box-shadow:0 1px 2px rgba(0,0,0,.2),0 0 0 1px rgba(255,255,255,.06)}.d-stat:hover{box-shadow:0 8px 24px rgba(0,0,0,.3),0 0 0 1px rgba(99,102,241,.2)}}
.d-stat .stat-accent{position:absolute;top:0;left:0;width:100%;height:3px}
.d-stat .stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;margin-bottom:.75rem}
.d-stat .stat-value{font-size:1.65rem;font-weight:700;color:#0f172a;letter-spacing:-.02em;line-height:1.2;margin-bottom:.15rem}
@media(prefers-color-scheme:dark){.d-stat .stat-value{color:#ededec}}
.d-stat .stat-label{font-size:.8rem;color:#64748b;font-weight:500}
@media(prefers-color-scheme:dark){.d-stat .stat-label{color:#a1a09a}}
</style>

<div class="d-stats">
    <div class="d-stat">
        <div class="stat-accent" style="background:linear-gradient(90deg,#6366f1,#818cf8)"></div>
        <div class="stat-icon" style="background:#eef2ff;color:#4f46e5">🔬</div>
        <div class="stat-value">{{ $availableTools }}</div>
        <div class="stat-label">Alat Tersedia</div>
    </div>
    @if($activeBorrowing)
    <div class="d-stat">
        <div class="stat-accent" style="background:linear-gradient(90deg,#f59e0b,#fbbf24)"></div>
        <div class="stat-icon" style="background:#fffbeb;color:#d97706">📋</div>
        <div class="stat-value" style="font-size:1.1rem">{{ $activeBorrowing->id_borrowing }}</div>
        <div class="stat-label">Peminjaman Aktif: {{ $activeBorrowing->status }}</div>
    </div>
    @endif
</div>

<div class="card">
    <h2 style="font-size:1rem;font-weight:600;margin:0 0 1rem">Peminjaman Terkini</h2>
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>ID</th><th>Tgl Pengajuan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($recentBorrowings as $b)
                <tr>
                    <td>{{ $b->id_borrowing }}</td>
                    <td>{{ $b->tgl_pengajuan?->format('d/m/Y') }}</td>
                    <td><span class="badge @php echo match($b->status) { 'Menunggu'=>'badge-pending','Disetujui'=>'badge-approved','Ditolak'=>'badge-rejected','Dipinjam'=>'badge-borrowed','Dikembalikan'=>'badge-returned',default=>'badge-pending' } @endphp">{{ $b->status }}</span></td>
                    <td><a href="{{ route('mhs.borrowings.show', $b->id_borrowing) }}" class="btn btn-sm btn-outline">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:2rem;color:#64748b">Belum ada peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
