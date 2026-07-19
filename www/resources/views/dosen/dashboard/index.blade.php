@extends('layouts.app')
@section('title', 'Selamat datang, ' . $userName)
@section('subtitle', 'Berikut adalah ringkasan inventaris dan status peminjaman anda hari ini')
@section('subtitle_badge')
<span class="badge badge-purple" style="font-size:11px;vertical-align:middle;margin-left:8px">Dosen</span>
@endsection

@section('header-search')
<div style="display:flex;align-items:center;gap:8px;flex:1;max-width:400px">
    <div style="position:relative;flex:1">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#9CA3AF" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <form method="GET" action="{{ route('dosen.katalog.index') }}">
            <input type="text" name="search" placeholder="Cari alat atau peminjaman" style="width:100%;padding:6px 12px 6px 32px;border:1.5px solid #E5E7EB;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;background:#F9FAFB;outline:none;transition:all .2s" onfocus="this.style.borderColor='#3B82F6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,.1)';this.style.background='#fff'" onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none';this.style.background='#F9FAFB'">
        </form>
    </div>
</div>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#F59E0B,#F97316);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countMenunggu }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Peminjaman Menunggu</div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#3B82F6,#1D4ED8);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countBerjalan }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Peminjaman Aktif</div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#8B5CF6,#6D28D9);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $cartCount }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Item di Keranjang</div>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;padding:20px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 2px rgba(0,0,0,.04)">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#10B981,#059669);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="22" height="22" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:28px;font-weight:700;color:#1A1A2E;line-height:1.1">{{ $countSelesai }}</div>
            <div style="font-size:13px;color:#6B7280;font-weight:500;margin-top:2px">Total Riwayat</div>
        </div>
    </div>
</div>



<div style="display:grid;grid-template-columns: 2fr 1fr;gap:20px;margin-bottom:24px;align-items:start">
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0">Peminjaman Aktif</h2>
        </div>

        @forelse($activeBorrowings as $ab)
        @php
        $items = $ab->borrowingItems;
        $firstItem = $items->first();
        $totalItems = $items->sum('jumlah_unit');
        @endphp
        <div class="card" style="margin-bottom:12px">
            <div style="display:flex;gap:16px">
                <div style="width:72px;height:72px;border-radius:10px;background:#F3F4F6;overflow:hidden;flex-shrink:0">
                    @if($firstItem && $firstItem->tool)
                    <img src="{{ $firstItem->tool->foto_url }}" alt="{{ $firstItem->tool->nama_alat }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#9CA3AF">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div style="flex:1;min-width:0">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start">
                        <div style="font-size:13px;font-weight:600;color:#1A1A2E">#{{ $ab->id_borrowing }}</div>
                        <div style="background:#3B82F6;color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;text-transform:uppercase;letter-spacing:0.05em">
                            {{ $ab->status }}
                        </div>
                    </div>
                    @if($firstItem && $firstItem->tool)
                    <div style="font-size:12px;color:#6B7280;margin-top:2px">{{ $firstItem->tool->nama_alat }}@if($items->count() > 1) +{{ $items->count() - 1 }} lainnya @endif</div>
                    @endif
                    <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:12px">
                        <div>
                            <span style="color:#9CA3AF">Tanggal Kembali</span>
                            <div style="font-weight:600;color:{{ $ab->is_overdue ? '#DC2626' : '#374151' }}">{{ $ab->tgl_rencana_kembali?->format('d/m/Y') }}</div>
                        </div>
                        <div style="text-align:right">
                            <span style="color:#9CA3AF">Jumlah Item</span>
                            <div style="font-weight:600;color:#374151">{{ $totalItems }}</div>
                        </div>
                    </div>

                    <hr style="border:none;border-top:1px solid #E5E7EB;margin:12px 0 10px 0">
                    <div style="text-align:right">
                        <a href="{{ route('dosen.peminjaman.detail', $ab->id_borrowing) }}" style="font-size:12px;color:#3B82F6;text-decoration:none;font-weight:600">Detail Peminjaman &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KOTAK PENGINGAT (DIPISAH) -->
        <div class="card" style="margin-bottom:24px; padding:12px 16px; border-left:4px solid #3B82F6; background:#F9FAFB">
            <div style="font-weight:700; font-size:13px; color:#1A1A2E; margin-bottom:4px;">
                {{ $firstItem && $firstItem->tool ? $firstItem->tool->kode_alat : ('#' . $ab->id_borrowing) }}
            </div>
            <div style="font-size:12px; color:#6B7280;">
                @php
                    $diffInDays = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($ab->tgl_rencana_kembali)->startOfDay(), false);
                @endphp
                @if($diffInDays < 0)
                    <span style="color:#DC2626;font-weight:600">Terlambat {{ abs((int)$diffInDays) }} hari</span>
                @elseif($diffInDays == 0)
                    <span style="color:#D97706;font-weight:600">Kembali hari ini</span>
                @else
                    <span style="font-weight:600;color:#3B82F6">Kembali dalam {{ (int)$diffInDays }} hari</span>
                @endif
                <span style="margin:0 4px;color:#D1D5DB">&bull;</span>
                <span>{{ $ab->tgl_rencana_kembali?->format('d/m/Y') }}</span>
                <span style="margin:0 4px;color:#D1D5DB">&bull;</span>
                <span>{{ $totalItems }} item</span>
            </div>
        </div>
        @empty
        <div class="card" style="margin-bottom:12px">
            <div style="text-align:center;padding:16px;color:#9CA3AF;font-size:13px">Belum ada peminjaman aktif.</div>
        </div>
        @endforelse
    </div>

    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h2 style="font-size:16px;font-weight:700;color:#1A1A2E;margin:0">Aktivitas Terakhir</h2>
            <svg width="20" height="20" fill="none" stroke="#6B7280" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
        </div>
        <div class="card" style="padding:0">
            @if($recentActivity->isNotEmpty())
            <div style="padding:20px">
                @foreach($recentActivity as $index => $ra)
                @php
                $raItems = $ra->borrowingItems;
                $raFirstItem = $raItems->first();
                $isApproved = in_array(strtoupper(trim($ra->status ?? '')), ['DISETUJUI', 'DIPINJAM']);
                $isOverdue = $ra->is_overdue;
                $isLast = $index === $recentActivity->count() - 1 && $cartCount == 0;
                $kodeAlat = $raFirstItem && $raFirstItem->tool ? $raFirstItem->tool->kode_alat : ('#' . $ra->id_borrowing);
                @endphp
                
                @if($isApproved || $isOverdue)
                <div style="display:flex;gap:16px;position:relative;margin-bottom:{{ ($isLast && !$isOverdue) ? '0' : '20px' }}">
                    @if(!($isLast && !$isOverdue))
                    <div style="position:absolute;left:9px;top:24px;bottom:-20px;width:1.5px;background:#E5E7EB"></div>
                    @endif
                    <div style="width:20px;height:20px;border-radius:50%;background:#DBEAFE;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;margin-top:2px">
                        <div style="width:8px;height:8px;border-radius:50%;background:#3B82F6"></div>
                    </div>
                    <div style="flex:1;min-width:0;padding-bottom:{{ ($isLast && !$isOverdue) ? '0' : '10px' }}">
                        <div style="font-size:11px;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em">
                            {{ $ra->updated_at?->format('d M, H:i') }}
                        </div>
                        <div style="font-size:13px;margin-top:4px;line-height:1.5;font-weight:500">
                            <span style="color:#1A1A2E">Peminjaman {{ $kodeAlat }} telah disetujui oleh Admin Lab.</span>
                        </div>
                    </div>
                </div>
                @endif

                @if($isOverdue)
                <div style="display:flex;gap:16px;position:relative;margin-bottom:{{ $isLast ? '0' : '20px' }}">
                    @if(!$isLast)
                    <div style="position:absolute;left:9px;top:24px;bottom:-20px;width:1.5px;background:#E5E7EB"></div>
                    @endif
                    <div style="width:20px;height:20px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;margin-top:2px">
                        <div style="width:8px;height:8px;border-radius:50%;background:#DC2626"></div>
                    </div>
                    <div style="flex:1;min-width:0;padding-bottom:{{ $isLast ? '0' : '10px' }}">
                        <div style="font-size:11px;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em">
                            {{ now()->format('d M, H:i') }}
                        </div>
                        <div style="font-size:13px;margin-top:4px;line-height:1.5;font-weight:500">
                            <span style="color:#DC2626">Peminjaman {{ $kodeAlat }} terlambat dikembalikan.</span>
                        </div>
                    </div>
                </div>
                @endif

                @if(!$isApproved && !$isOverdue)
                <div style="display:flex;gap:16px;position:relative;margin-bottom:{{ $isLast ? '0' : '20px' }}">
                    @if(!$isLast)
                    <div style="position:absolute;left:9px;top:24px;bottom:-20px;width:1.5px;background:#E5E7EB"></div>
                    @endif
                    <div style="width:20px;height:20px;border-radius:50%;background:#DBEAFE;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;margin-top:2px">
                        <div style="width:8px;height:8px;border-radius:50%;background:#3B82F6"></div>
                    </div>
                    <div style="flex:1;min-width:0;padding-bottom:{{ $isLast ? '0' : '10px' }}">
                        <div style="font-size:11px;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em">
                            {{ $ra->updated_at?->format('d M, H:i') }}
                        </div>
                        <div style="font-size:13px;margin-top:4px;line-height:1.5;font-weight:500">
                            @if(strtoupper(trim($ra->status ?? '')) === 'MENUNGGU')
                                <span style="color:#1A1A2E">Peminjaman <span style="color:#3B82F6">{{ $kodeAlat }}</span> sedang menunggu persetujuan.</span>
                            @else
                                <span style="color:#1A1A2E">Peminjaman <span style="color:#3B82F6">{{ $kodeAlat }}</span> {{ strtolower($ra->status) }}.</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                
                @if($cartCount > 0)
                <div style="display:flex;gap:16px;position:relative;margin-bottom:0;margin-top:{{ $recentActivity->isEmpty() ? '0' : '20px' }}">
                    <div style="width:20px;height:20px;border-radius:50%;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;margin-top:2px">
                        <div style="width:8px;height:8px;border-radius:50%;background:#D97706"></div>
                    </div>
                    <div style="flex:1;min-width:0;padding-bottom:0">
                        <div style="font-size:11px;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em">
                            Hari Ini
                        </div>
                        <div style="font-size:13px;color:#1A1A2E;margin-top:4px;line-height:1.5;font-weight:500">
                            Anda menambahkan <span style="font-weight:700">{{ $cartCount }} item</span> baru ke keranjang.
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @elseif($cartCount > 0)
            <div style="padding:20px">
                <div style="display:flex;gap:16px;position:relative;margin-bottom:0">
                    <div style="width:20px;height:20px;border-radius:50%;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;margin-top:2px">
                        <div style="width:8px;height:8px;border-radius:50%;background:#D97706"></div>
                    </div>
                    <div style="flex:1;min-width:0;padding-bottom:0">
                        <div style="font-size:11px;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:0.05em">
                            Hari Ini
                        </div>
                        <div style="font-size:13px;color:#1A1A2E;margin-top:4px;line-height:1.5;font-weight:500">
                            Anda menambahkan <span style="font-weight:700">{{ $cartCount }} item</span> baru ke keranjang.
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div style="padding:30px 20px;text-align:center;color:#9CA3AF;font-size:13px">
                Belum ada aktivitas.
            </div>
            @endif
        </div>
        
        <div class="card" style="background:linear-gradient(135deg,#1E3A5F,#162D4D);border:none;margin-top:20px;padding:20px">
            <div style="text-align:center">
                <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:6px">Butuh alat tambahan?</div>
                <div style="font-size:12px;color:rgba(255,255,255,.7);margin-bottom:16px;line-height:1.4">Cek ketersediaan alat di laboratorium kami secara real-time</div>
                <a href="{{ route('dosen.katalog.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#3B82F6;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Jelajahi Katalog
                </a>
            </div>
        </div>
    </div>
</div>
@endsection