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
        $activeBorrowing = Borowing::where('mahasiswa_id', auth()->id())
            ->whereIn('status', ['DISETUJUI', 'DIPINJAM'])
            ->with(['borrowingItems.tool'])
            ->latest()
            ->first();

        $recentBorrowings = Borowing::where('mahasiswa_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $availableTools = Tool::tersedia()->count();

        return view('mahasiswa.dashboard.index', compact(
            'activeBorrowing', 'recentBorrowings', 'availableTools'
        ));
    }
}
