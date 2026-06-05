@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<form method="GET" action="{{ route('admin.users.index') }}">
    <div class="toolbar">
        <div class="toolbar-item">
            <label>Cari</label>
            <input type="text" name="search" placeholder="Nama/NIM/Email" value="{{ request('search') }}" style="min-width:200px">
        </div>
        <div class="toolbar-item">
            <label>Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="1" @selected(request('status') === '1')>Aktif</option>
                <option value="0" @selected(request('status') === '0')>Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm">Cari</button>
    </div>
</form>

<div class="card">
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>NIM</th><th>Nama Lengkap</th><th>Email</th><th>Program Studi</th><th>Total Peminjaman</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->nim }}</td>
                    <td>{{ $user->nama_lengkap }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->program_studi }}</td>
                    <td>{{ $user->total_peminjaman ?? 0 }}</td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'badge-tersedia' : 'badge-dipinjam' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}" style="display:inline" onsubmit="return confirmAction('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')">
                            @csrf
                            <button class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:2rem;color:#64748b">Tidak ada mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="pagination">{{ $users->appends(request()->query())->links() }}</div>@endif
</div>
@endsection
