<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\BorrowingItem;
use App\Models\Item;
use App\Models\ItemMutation;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->tab ?? 'rekap-peminjaman';
        $from = $request->from;
        $to = $request->to;

        $data = [];

        if ($tab === 'rekap-peminjaman') {
            $query = Borowing::with(['mahasiswa', 'borrowingItems.tool', 'prosesOleh']);
            if ($from) $query->whereDate('tgl_pengajuan', '>=', $from);
            if ($to) $query->whereDate('tgl_pengajuan', '<=', $to);
            if ($request->status) $query->where('status', $request->status);
            $data['borrowings'] = $query->orderBy('tgl_pengajuan', 'desc')->get();
        }

        if ($tab === 'alat-sering-dipinjam') {
            $query = BorrowingItem::select('tool_id', DB::raw('COUNT(*) as total_dipinjam'), DB::raw('SUM(jumlah_unit) as total_unit'))
                ->groupBy('tool_id')
                ->orderBy('total_unit', 'desc');
            if ($from) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengajuan', '>=', $from));
            if ($to) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengajuan', '<=', $to));
            $items = $query->get();
            $data['popularTools'] = $items->map(function ($item) {
                $tool = Tool::find($item->tool_id);
                return [
                    'tool' => $tool,
                    'total_dipinjam' => $item->total_dipinjam,
                    'total_unit' => $item->total_unit,
                ];
            });
        }

        if ($tab === 'inventaris-barang') {
            $query = Item::query();
            if ($request->kondisi) $query->where('kondisi', $request->kondisi);
            if ($request->kategori) $query->where('kategori', $request->kategori);
            $data['items'] = $query->orderBy('nama_barang')->get();
        }

        if ($tab === 'log-mutasi-stok') {
            $query = ItemMutation::with('item');
            if ($from) $query->whereDate('time_stamp', '>=', $from);
            if ($to) $query->whereDate('time_stamp', '<=', $to);
            if ($request->tipe_mutasi) $query->where('tipe_mutasi', $request->tipe_mutasi);
            $data['mutations'] = $query->orderBy('time_stamp', 'desc')->get();
        }

        if ($tab === 'alat-dipinjam') {
            $data['activeBorrowings'] = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
                ->whereIn('status', ['DIPINJAM', 'DISETUJUI'])
                ->orderBy('tgl_rencana_kembali')
                ->get();
        }

        if ($tab === 'rekap-per-mahasiswa') {
            $query = User::whereIn('role', ['mahasiswa', 'dosen'])
                ->withCount(['borrowings as total_peminjaman'])
                ->withCount(['borrowings as total_selesai' => fn($q) => $q->where('status', 'DIKEMBALIKAN')])
                ->withCount(['borrowings as total_disetujui' => fn($q) => $q->where('status', 'DISETUJUI')]);
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', "%{$request->search}%")
                      ->orWhere('nim', 'like', "%{$request->search}%");
                });
            }
            $data['users'] = $query->orderBy('total_peminjaman', 'desc')->get();
        }

        return view('admin.laporan.index', compact('tab', 'from', 'to', 'data'));
    }

    public function export(Request $request)
    {
        $tab = $request->tab ?? 'rekap-peminjaman';
        $from = $request->from;
        $to = $request->to;

        $filename = 'laporan_' . $tab . '_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($tab, $from, $to) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, "\xEF\xBB\xBF");

            if ($tab === 'rekap-peminjaman') {
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Tgl Pengajuan', 'Tgl Pinjam', 'Tgl Kembali Rencana', 'Keperluan', 'Status', 'Jumlah Item', 'Diproses Oleh', 'Tgl Diproses']);
                $query = Borowing::with(['mahasiswa', 'borrowingItems.tool', 'prosesOleh']);
                if ($from) $query->whereDate('tgl_pengajuan', '>=', $from);
                if ($to) $query->whereDate('tgl_pengajuan', '<=', $to);
                $query->orderBy('tgl_pengajuan', 'desc')->chunk(100, function ($borrowings) use ($handle) {
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
                });
            }

            if ($tab === 'alat-sering-dipinjam') {
                fputcsv($handle, ['No', 'Kode Alat', 'Nama Alat', 'Kategori', 'Total Dipinjam', 'Total Unit']);
                $items = BorrowingItem::select('tool_id', DB::raw('COUNT(*) as total_dipinjam'), DB::raw('SUM(jumlah_unit) as total_unit'))
                    ->groupBy('tool_id')->orderBy('total_unit', 'desc')->get();
                foreach ($items as $i => $item) {
                    $tool = Tool::find($item->tool_id);
                    fputcsv($handle, [$i + 1, $tool?->kode_alat, $tool?->nama_alat, $tool?->kategori, $item->total_dipinjam, $item->total_unit]);
                }
            }

            if ($tab === 'inventaris-barang') {
                fputcsv($handle, ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 'Satuan', 'Kondisi', 'Lokasi']);
                Item::orderBy('nama_barang')->chunk(100, function ($items) use ($handle) {
                    foreach ($items as $i => $item) {
                        fputcsv($handle, [$i + 1, $item->kode_barang, $item->nama_barang, $item->kategori, $item->stok, $item->satuan, $item->kondisi, $item->lokasi]);
                    }
                });
            }

            if ($tab === 'log-mutasi-stok') {
                fputcsv($handle, ['No', 'Barang', 'Tipe Mutasi', 'Jumlah', 'Stok Sebelum', 'Stok Sesudah', 'Keterangan', 'Waktu']);
                $query = ItemMutation::with('item');
                if ($from) $query->whereDate('time_stamp', '>=', $from);
                if ($to) $query->whereDate('time_stamp', '<=', $to);
                $query->orderBy('time_stamp', 'desc')->chunk(100, function ($mutations) use ($handle) {
                    foreach ($mutations as $i => $m) {
                        fputcsv($handle, [$i + 1, $m->item?->nama_barang, $m->tipe_mutasi, $m->jumlah, $m->stok_sebelum, $m->stok_sesudah, $m->keterangan, $m->time_stamp?->format('Y-m-d H:i')]);
                    }
                });
            }

            if ($tab === 'alat-dipinjam') {
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Tgl Pinjam', 'Tgl Rencana Kembali', 'Status', 'Jumlah Item']);
                Borowing::with('mahasiswa', 'borrowingItems')
                    ->whereIn('status', ['DIPINJAM', 'DISETUJUI'])
                    ->orderBy('tgl_rencana_kembali')
                    ->chunk(100, function ($borrowings) use ($handle) {
                        foreach ($borrowings as $i => $b) {
                            fputcsv($handle, [$i + 1, $b->mahasiswa?->nama_lengkap, $b->mahasiswa?->nim, $b->tgl_rencana_pinjam?->format('Y-m-d'), $b->tgl_rencana_kembali?->format('Y-m-d'), $b->status, $b->borrowingItems->count()]);
                        }
                    });
            }

            if ($tab === 'rekap-per-mahasiswa') {
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Role', 'Total Peminjaman', 'Disetujui', 'Selesai']);
                User::whereIn('role', ['mahasiswa', 'dosen'])
                    ->withCount(['borrowings as total_peminjaman'])
                    ->withCount(['borrowings as total_selesai' => fn($q) => $q->where('status', 'DIKEMBALIKAN')])
                    ->withCount(['borrowings as total_disetujui' => fn($q) => $q->where('status', 'DISETUJUI')])
                    ->orderBy('total_peminjaman', 'desc')
                    ->chunk(100, function ($users) use ($handle) {
                        foreach ($users as $i => $u) {
                            fputcsv($handle, [$i + 1, $u->nama_lengkap, $u->nim, $u->role, $u->total_peminjaman, $u->total_disetujui, $u->total_selesai]);
                        }
                    });
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
