<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\Tool;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $activeBorrowings = Borowing::where('mahasiswa_id', $userId)
            ->whereIn('status', ['DISETUJUI', 'DIPINJAM', 'TERLAMBAT'])
            ->with(['borrowingItems.tool'])
            ->latest()
            ->get();

        $recentBorrowings = Borowing::where('mahasiswa_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $recentActivity = Borowing::where('mahasiswa_id', $userId)
            ->whereIn('status', ['MENUNGGU', 'DISETUJUI', 'DITOLAK', 'DIKEMBALIKAN', 'DIPINJAM', 'TERLAMBAT'])
            ->with(['borrowingItems.tool'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $availableTools = Tool::tersedia()->count();

        $countMenunggu = Borowing::where('mahasiswa_id', $userId)->where('status', 'MENUNGGU')->count();
        $countBerjalan = Borowing::where('mahasiswa_id', $userId)->whereIn('status', ['DISETUJUI', 'DIPINJAM', 'TERLAMBAT'])->count();
        $countSelesai = Borowing::where('mahasiswa_id', $userId)->where('status', 'DIKEMBALIKAN')->count();
        $cartCount = collect(session('cart', []))->sum('jumlah_unit');

        $userName = ucwords(mb_strtolower(auth()->user()->nama_lengkap));

        return view('dosen.dashboard.index', compact(
            'activeBorrowings', 'recentBorrowings', 'recentActivity', 'availableTools',
            'countMenunggu', 'countBerjalan', 'countSelesai', 'cartCount', 'userName'
        ));
    }
}

