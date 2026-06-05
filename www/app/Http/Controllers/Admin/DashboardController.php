<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borowing;
use App\Models\Item;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalTools = Tool::count();
        $totalItems = Item::count();
        $activeBorrowings = Borowing::whereIn('status', ['Disetujui', 'Dipinjam'])->count();
        $pendingBorrowings = Borowing::where('status', 'Menunggu')->count();
        $lowStockItems = Item::where('stok', '<=', 5)->count();
        $totalUsers = \App\Models\User::where('role', 'mahasiswa')->count();
        $recentLogs = AuditLog::orderBy('time_stamp', 'desc')->take(10)->get();

        return view('admin.dashboard.index', compact(
            'totalTools', 'totalItems', 'activeBorrowings', 'pendingBorrowings',
            'lowStockItems', 'totalUsers', 'recentLogs'
        ));
    }
}
