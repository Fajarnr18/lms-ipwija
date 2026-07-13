@extends('layouts.app')
@section('title', 'Manajemen User')
@section('subtitle', 'Kelola data mahasiswa, dosen, dan staf laboratorium.')

@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <form method="GET" action="{{ route('admin.users.index') }}">
            @foreach(request()->except('search', 'page') as $key => $val)
            @if($val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endif
            @endforeach
            <input type="text" name="search" placeholder="Search for names or NIM." value="{{ request('search') }}" style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'" oninput="this.form.submit()">
        </form>
    </div>
</div>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px">
    <div class="stat-card" style="border-left:4px solid #1E3A5F">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $totalUser }}</div>
                <div class="stat-label" style="color:#6B7280">Total User</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#1E3A5F;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #10B981">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $userAktif }}</div>
                <div class="stat-label" style="color:#6B7280">User Aktif</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#10B981;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #EF4444">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $nonaktif }}</div>
                <div class="stat-label" style="color:#6B7280">Menunggu</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#F59E0B;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid #8B5CF6">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <div class="stat-value" style="color:#1A1A2E;font-size:22px">{{ $mahasiswaBaru }}</div>
                <div class="stat-label" style="color:#6B7280">Mahasiswa Baru</div>
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:#8B5CF6;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.users.index') }}">
    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:20px">
        <div style="display:flex;flex-direction:column;gap:4px;min-width:200px">
            <label style="font-size:12px;font-weight:600;color:#374151">PROGRAM STUDI</label>
            <select name="prodi" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif">
                <option value="">Semua Program Studi</option>
                @foreach($programStudis ?? [] as $p)
                <option value="{{ $p }}" @selected(request('prodi')===$p)>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;min-width:160px">
            <label style="font-size:12px;font-weight:600;color:#374151">STATUS</label>
            <select name="status" onchange="this.form.submit()" style="padding:8px 12px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:13px;outline:none;font-family:'Inter',sans-serif">
                <option value="">Semua Status</option>
                <option value="1" @selected(request('status') === '1')>Aktif</option>
                <option value="0" @selected(request('status') === '0')>Menunggu</option>
            </select>
        </div>
        <div style="display:flex;align-items:flex-end;gap:8px">
            <button type="submit" class="btn btn-sm" style="padding:8px 10px;background:#2563EB;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;line-height:1" title="Filter">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </button>
            @if(request('prodi') || request('status') !== '' && request('status') !== null || request('search'))
            <a href="{{ route('admin.users.index') }}" style="padding:8px 14px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:12px;color:#374151;text-decoration:none;font-weight:500">Reset Filter</a>
            @endif
        </div>
    </div>
</form>

<div class="card" style="padding:0">
    <div style="overflow-x:auto">
        <table>
            <thead style="background:#1E3A5F">
                <tr>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">NAMA</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">ROLE</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">NIM / NIDN</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">PROGRAM STUDI</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">EMAIL</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:left">STATUS</th>
                    <th style="background:#1E3A5F;color:#fff;padding:10px 12px;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;text-align:center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="font-weight:600;color:#1A1A2E;font-size:13px">{{ $user->nama_lengkap }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'dosen' ? 'badge-purple' : 'badge-blue' }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td style="font-size:13px;color:#374151">{{ $user->nim ?? '-' }}</td>
                    <td style="font-size:13px;color:#374151">{{ $user->program_studi ?? '-' }}</td>
                    <td style="font-size:13px;color:#3B82F6">{{ $user->email }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $user->is_active ? '#DCFCE7' : '#FEF3C7' }};color:{{ $user->is_active ? '#16A34A' : '#D97706' }}">
                            <span style="width:6px;height:6px;border-radius:50%;background:{{ $user->is_active ? '#16A34A' : '#D97706' }};display:inline-block"></span>
                            {{ $user->is_active ? 'Aktif' : 'Menunggu' }}
                        </span>
                    </td>
                    <td style="text-align:center">
                        @if($user->role !== 'admin')
                            @if(!$user->is_active)
                            <div style="display:flex;gap:4px;justify-content:center">
                                <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}" style="display:inline">
                                    @csrf
                                    <button class="btn btn-sm" data-confirm="Setujui pendaftaran akun ini?" style="padding:6px 10px;border:none;border-radius:6px;cursor:pointer;font-size:11px;font-weight:600;font-family:'Inter',sans-serif;background:#16A34A;color:#fff" title="Aktifkan Akun">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm" data-confirm="Tolak dan hapus akun ini secara permanen?" style="padding:6px 10px;border:none;border-radius:6px;cursor:pointer;font-size:11px;font-weight:600;font-family:'Inter',sans-serif;background:#DC2626;color:#fff" title="Tolak Pendaftaran">
                                        Reject
                                    </button>
                                </form>
                            </div>
                            @else
                            <div style="display:flex;gap:4px;justify-content:center">
                                <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}" style="display:inline">
                                    @csrf
                                    <button class="btn btn-sm" data-confirm="Nonaktifkan akun ini?" style="padding:6px 14px;border:none;border-radius:6px;cursor:pointer;font-size:11px;font-weight:600;font-family:'Inter',sans-serif;background:#F59E0B;color:#fff">
                                        Nonaktifkan
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm" data-confirm="Hapus akun ini secara permanen?" style="padding:6px 14px;border:none;border-radius:6px;cursor:pointer;font-size:11px;font-weight:600;font-family:'Inter',sans-serif;background:#DC2626;color:#fff">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            @endif
                        @else
                        <span style="font-size:12px;color:#9CA3AF">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"><div class="empty-state">Tidak ada pengguna ditemukan.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    {{ $users->appends(request()->query())->links() }}
    @endif
</div>
@endsection