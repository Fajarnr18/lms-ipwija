<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borowing;
use App\Models\Item;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalTools = Tool::count();
        $totalItems = Item::count();
        $activeBorrowings = Borowing::where('status', 'DIPINJAM')->count();
        $pendingBorrowings = Borowing::where('status', 'MENUNGGU')->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $recentLogs = AuditLog::orderBy('time_stamp', 'desc')->take(10)->get();
        $recentBorrowings = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $lowStockTools = Tool::where('stok_tersedia', '<=', 3)
            ->where('status_alat', 'TERSEDIA')
            ->orderBy('stok_tersedia')
            ->take(10)
            ->get();

        // Grafik peminjam per bulan (6 bulan terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $label = $month->format('M Y');
            $chartLabels[] = $label;
            $count = Borowing::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $chartData[] = $count;
        }

        // Item stok rendah (inventaris)
        $lowStockItems = Item::where('stok', '<=', 3)
            ->orderBy('stok')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalTools', 'totalItems', 'activeBorrowings',
            'pendingBorrowings', 'totalMahasiswa', 'totalDosen',
            'recentLogs', 'recentBorrowings', 'lowStockTools',
            'lowStockItems', 'chartLabels', 'chartData'
        ));
    }
}
