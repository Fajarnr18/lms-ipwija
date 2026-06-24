<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\Tool;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $activeBorrowing = Borowing::where('mahasiswa_id', $userId)
            ->whereIn('status', ['DISETUJUI', 'DIPINJAM'])
            ->with(['borrowingItems.tool'])
            ->latest()
            ->first();

        $recentBorrowings = Borowing::where('mahasiswa_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $availableTools = Tool::tersedia()->count();

        $countMenunggu = Borowing::where('mahasiswa_id', $userId)->where('status', 'MENUNGGU')->count();
        $countBerjalan = Borowing::where('mahasiswa_id', $userId)->whereIn('status', ['DISETUJUI', 'DIPINJAM'])->count();
        $countSelesai = Borowing::where('mahasiswa_id', $userId)->where('status', 'DIKEMBALIKAN')->count();
        $cartCount = count(session('cart', []));

        return view('mahasiswa.dashboard.index', compact(
            'activeBorrowing', 'recentBorrowings', 'availableTools',
            'countMenunggu', 'countBerjalan', 'countSelesai', 'cartCount'
        ));
    }
}
