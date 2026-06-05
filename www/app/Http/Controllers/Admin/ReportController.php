<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\Tool;
use App\Models\Item;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $recentBorrowings = Borowing::with('mahasiswa')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('recentBorrowings'));
    }

    public function borrowings(Request $request)
    {
        $query = Borowing::with(['mahasiswa', 'borrowingItems.tool']);

        if ($request->from) $query->whereDate('created_at', '>=', $request->from);
        if ($request->to) $query->whereDate('created_at', '<=', $request->to);
        if ($request->status) $query->where('status', $request->status);

        $borrowings = $query->orderBy('created_at', 'desc')->get();

        if ($request->export === 'csv') {
            $filename = 'laporan_peminjaman_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $callback = function () use ($borrowings) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, "\xEF\xBB\xBF");
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Tgl Pengajuan', 'Tgl Rencana Pinjam', 'Tgl Rencana Kembali', 'Keperluan', 'Status', 'Jumlah Item', 'Diproses Oleh', 'Tgl Diproses']);
                foreach ($borrowings as $i => $b) {
                    fputcsv($handle, [
                        $i + 1,
                        $b->mahasiswa?->nama_lengkap,
                        $b->mahasiswa?->nim,
                        $b->tgl_pengajuan?->format('Y-m-d H:i'),
                        $b->tgl_rencana_pinjam?->format('Y-m-d'),
                        $b->tgl_rencana_kembali?->format('Y-m-d'),
                        $b->keperluan,
                        $b->status,
                        $b->borrowingItems->count(),
                        $b->prosesOleh?->nama_lengkap,
                        $b->tgl_diproses?->format('Y-m-d H:i'),
                    ]);
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        if ($request->ajax()) {
            return view('admin.reports._table', compact('borrowings'));
        }

        return view('admin.reports.index', compact('borrowings'));
    }

    public function export(Request $request)
    {
        if ($request->type === 'items') {
            return $this->tools($request);
        }
        return $this->borrowings($request);
    }

    public function tools(Request $request)
    {
        $query = Tool::query();

        if ($request->status) $query->where('status_alat', $request->status);
        if ($request->kategori) $query->where('kategori', $request->kategori);

        $tools = $query->orderBy('nama_alat')->get();

        if ($request->export === 'csv') {
            $filename = 'laporan_alat_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $callback = function () use ($tools) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, "\xEF\xBB\xBF");
                fputcsv($handle, ['No', 'Kode', 'Nama Alat', 'Kategori', 'Stok Total', 'Stok Tersedia', 'Status', 'Lokasi']);
                foreach ($tools as $i => $t) {
                    fputcsv($handle, [$i + 1, $t->kode_alat, $t->nama_alat, $t->kategori, $t->stok_total, $t->stok_tersedia, $t->status_alat, $t->lokasi]);
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        if ($request->ajax()) {
            return view('admin.reports._table_tools', compact('tools'));
        }

        return view('admin.reports.index', compact('tools'));
    }
}
