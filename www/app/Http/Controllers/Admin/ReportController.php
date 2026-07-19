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
        $error = null;

        if ($from && $to && $to < $from) {
            $error = 'Tanggal akhir tidak valid';
        }

        $data = [];
        $totalLaporan = 0;
        $totalDisetujui = 0;
        $totalDitolak = 0;
        $totalDikembalikan = 0;
        $totalAlat = 0;
        $totalPinjamanAktif = 0;
        $totalTerlambat = 0;
        $kembaliHariIni = 0;
        $peminjamUnik = 0;
        $totalPergerakan = 0;
        $totalMasuk = 0;
        $totalKeluar = 0;
        $totalBarang = 0;
        $totalStok = 0;
        $kondisiBaik = 0;
        $kondisiRusak = 0;
        $totalMahasiswa = 0;
        $statusAktif = 0;
        $peminjamAktif = 0;
        $terlambatKembali = 0;
        $chartData = [];

        if ($tab === 'rekap-peminjaman') {
            $query = Borowing::with(['mahasiswa', 'borrowingItems.tool', 'prosesOleh']);

            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('mahasiswa', fn($sq) => $sq->where('nama_lengkap', 'like', "%{$search}%"))
                      ->orWhere('id_borrowing', 'like', "%{$search}%")
                      ->orWhereHas('borrowingItems.tool', fn($sq) => $sq->where('nama_alat', 'like', "%{$search}%"));
                });
            }

            if ($from) $query->whereDate('tgl_pengajuan', '>=', $from);
            if ($to) $query->whereDate('tgl_pengajuan', '<=', $to);
            if ($request->status) $query->where('status', $request->status);

            $allQuery = clone $query;
            $totalLaporan = (clone $allQuery)->count();
            $totalDisetujui = (clone $allQuery)->where('status', 'DISETUJUI')->count();
            $totalDitolak = (clone $allQuery)->where('status', 'DITOLAK')->count();
            $totalDikembalikan = (clone $allQuery)->where('status', 'DIKEMBALIKAN')->count();

            $data['borrowings'] = $query->orderBy('tgl_pengajuan', 'desc')->paginate(10);

        $totalAlat = 0;
        $chartData = [];
            for ($m = 1; $m <= 12; $m++) {
                $count = Borowing::whereMonth('tgl_pengajuan', $m)
                    ->whereYear('tgl_pengajuan', now()->year)
                    ->count();
                $chartData[] = $count;
            }
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
                $activeCount = Borowing::whereHas('borrowingItems', fn($q) => $q->where('tool_id', $item->tool_id))
                    ->where('status', 'DIPINJAM')->count();
                return [
                    'tool' => $tool,
                    'total_dipinjam' => $item->total_dipinjam,
                    'total_unit' => $item->total_unit,
                    'status' => $activeCount > 0 ? 'Dipinjam' : 'Tersedia',
                ];
            });
            $data['popularTools'] = $data['popularTools']->take(10);
            $totalAlat = Tool::count();
        }

        if ($tab === 'inventaris-barang') {
            $query = Item::query();
            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            }
            if ($request->kondisi) $query->where('kondisi', $request->kondisi);
            if ($request->kategori) $query->where('kategori', $request->kategori);
            $data['items'] = $query->orderBy('nama_barang')->paginate(15);

            $totalBarang = Item::count();
            $totalStok = Item::sum('stok');
            $kondisiBaik = Item::where('kondisi', 'Baik')->count();
            $kondisiRusak = Item::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();
        }

        if ($tab === 'log-mutasi-stok') {
            $query = ItemMutation::with(['item', 'admin']);
            if ($from) $query->whereDate('time_stamp', '>=', $from);
            if ($to) $query->whereDate('time_stamp', '<=', $to);
            if ($request->tipe_mutasi) $query->where('tipe_mutasi', $request->tipe_mutasi);
            $statsQuery = clone $query;
            $allMutations = $statsQuery->get();
            $totalPergerakan = $allMutations->count();
            $totalMasuk = $allMutations->where('tipe_mutasi', 'Masuk')->count();
            $totalKeluar = $allMutations->where('tipe_mutasi', 'Keluar')->count();
            $data['mutations'] = $query->orderBy('time_stamp', 'desc')->paginate(15);
        }

        if ($tab === 'alat-dipinjam') {
            $query = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
                ->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT']);
            $data['activeBorrowings'] = (clone $query)->orderBy('tgl_rencana_kembali')->get();

            $totalPinjamanAktif = (clone $query)->count();
            $totalTerlambat = (clone $query)->get()->filter(fn($b) => $b->is_overdue)->count();
            $kembaliHariIni = (clone $query)->whereDate('tgl_rencana_kembali', now())->count();
            $peminjamUnik = (clone $query)->distinct('mahasiswa_id')->count('mahasiswa_id');
        }

        if ($tab === 'rekap-per-mahasiswa') {
            $query = User::whereIn('role', ['mahasiswa', 'dosen'])
                ->withCount(['borrowings as total_peminjaman'])
                ->withCount(['borrowings as total_selesai' => fn($q) => $q->where('status', 'DIKEMBALIKAN')])
                ->withCount(['borrowings as total_disetujui' => fn($q) => $q->where('status', 'DISETUJUI')])
                ->withCount(['borrowings as total_dipinjam' => fn($q) => $q->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT'])]);
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', "%{$request->search}%")
                      ->orWhere('nim', 'like', "%{$request->search}%");
                });
            }
            $perPage = $request->per_page ?? 10;
            $data['users'] = $query->orderBy('total_peminjaman', 'desc')->paginate($perPage);

            $totalMahasiswa = User::whereIn('role', ['mahasiswa', 'dosen'])->count();
            $statusAktif = User::whereIn('role', ['mahasiswa', 'dosen'])->where('is_active', true)->count();
            $peminjamAktif = User::whereIn('role', ['mahasiswa', 'dosen'])->whereHas('borrowings', fn($q) => $q->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT']))->count();
            $terlambatKembali = Borowing::whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT'])->get()->filter(fn($b) => $b->is_overdue)->count();
        }

        if ($tab === 'alat-rusak') {
            $query = BorrowingItem::with(['tool', 'borowing.mahasiswa', 'borowing.prosesOleh'])
                ->whereIn('kondisi_saat_kembali', ['Rusak Ringan', 'Rusak Berat', 'Tidak Layak']);
            
            if ($from) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengembalian_aktual', '>=', $from));
            if ($to) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengembalian_aktual', '<=', $to));
            
            $data['damagedTools'] = $query->orderByDesc('id_borrowings_item')->paginate(15);
        }

        return view('admin.laporan.index', compact('tab', 'from', 'to', 'data', 'totalLaporan', 'totalDisetujui', 'totalDitolak', 'totalDikembalikan', 'chartData', 'error', 'totalAlat', 'totalPinjamanAktif', 'totalTerlambat', 'kembaliHariIni', 'peminjamUnik', 'totalPergerakan', 'totalMasuk', 'totalKeluar', 'totalBarang', 'totalStok', 'kondisiBaik', 'kondisiRusak', 'totalMahasiswa', 'statusAktif', 'peminjamAktif', 'terlambatKembali'));
    }

        public function export(Request $request)
    {
        $tab = $request->tab ?? 'rekap-peminjaman';
        $from = $request->from;
        $to = $request->to;

        if ($from && $to && $to < $from) {
            return back()->with('error', 'Tanggal akhir tidak valid');
        }

        $filename = 'laporan_' . $tab . '_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($tab, $from, $to) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, "\xEF\xBB\xBF");

            if ($tab === 'rekap-peminjaman') {
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Tgl Pengajuan', 'Tgl Pinjam', 'Tgl Kembali Rencana', 'Keperluan', 'Status', 'Jumlah Item', 'Diproses Oleh', 'Tgl Diproses'], ';');
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
                        ], ';');
                    }
                });
            }

            if ($tab === 'alat-sering-dipinjam') {
                fputcsv($handle, ['No', 'Kode Alat', 'Nama Alat', 'Kategori', 'Total Dipinjam', 'Total Unit'], ';');
                $items = BorrowingItem::select('tool_id', DB::raw('COUNT(*) as total_dipinjam'), DB::raw('SUM(jumlah_unit) as total_unit'))
                    ->groupBy('tool_id')->orderBy('total_unit', 'desc')->get();
                foreach ($items as $i => $item) {
                    $tool = Tool::find($item->tool_id);
                    fputcsv($handle, [$i + 1, $tool?->kode_alat, $tool?->nama_alat, $tool?->kategori, $item->total_dipinjam, $item->total_unit], ';');
                }
            }

            if ($tab === 'inventaris-barang') {
                fputcsv($handle, ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 'Satuan', 'Kondisi', 'Lokasi'], ';');
                Item::orderBy('nama_barang')->chunk(100, function ($items) use ($handle) {
                    foreach ($items as $i => $item) {
                        fputcsv($handle, [$i + 1, $item->kode_barang, $item->nama_barang, $item->kategori, $item->stok, $item->satuan, $item->kondisi, $item->lokasi], ';');
                    }
                });
            }

            if ($tab === 'log-mutasi-stok') {
                fputcsv($handle, ['No', 'Barang', 'Tipe Mutasi', 'Jumlah', 'Stok Sebelum', 'Stok Sesudah', 'Keterangan', 'Waktu'], ';');
                $query = ItemMutation::with('item');
                if ($from) $query->whereDate('time_stamp', '>=', $from);
                if ($to) $query->whereDate('time_stamp', '<=', $to);
                $query->orderBy('time_stamp', 'desc')->chunk(100, function ($mutations) use ($handle) {
                    foreach ($mutations as $i => $m) {
                        fputcsv($handle, [$i + 1, $m->item?->nama_barang, $m->tipe_mutasi, $m->jumlah, $m->stok_sebelum, $m->stok_sesudah, $m->keterangan, $m->time_stamp?->format('Y-m-d H:i')], ';');
                    }
                });
            }

            if ($tab === 'alat-dipinjam') {
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Tgl Pinjam', 'Tgl Rencana Kembali', 'Status', 'Jumlah Item'], ';');
                Borowing::with('mahasiswa', 'borrowingItems')
                    ->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT'])
                    ->orderBy('tgl_rencana_kembali')
                    ->chunk(100, function ($borrowings) use ($handle) {
                        foreach ($borrowings as $i => $b) {
                            fputcsv($handle, [$i + 1, $b->mahasiswa?->nama_lengkap, $b->mahasiswa?->nim, $b->tgl_rencana_pinjam?->format('Y-m-d'), $b->tgl_rencana_kembali?->format('Y-m-d'), $b->status, $b->borrowingItems->count()], ';');
                        }
                    });
            }

            if ($tab === 'rekap-per-mahasiswa') {
                fputcsv($handle, ['No', 'Nama', 'NIM', 'Role', 'Total Peminjaman', 'Disetujui', 'Selesai'], ';');
                User::whereIn('role', ['mahasiswa', 'dosen'])
                    ->withCount(['borrowings as total_peminjaman'])
                    ->withCount(['borrowings as total_selesai' => fn($q) => $q->where('status', 'DIKEMBALIKAN')])
                    ->withCount(['borrowings as total_disetujui' => fn($q) => $q->where('status', 'DISETUJUI')])
                    ->orderBy('total_peminjaman', 'desc')
                    ->chunk(100, function ($users) use ($handle) {
                        foreach ($users as $i => $u) {
                            fputcsv($handle, [$i + 1, $u->nama_lengkap, $u->nim, $u->role, $u->total_peminjaman, $u->total_disetujui, $u->total_selesai], ';');
                        }
                    });
            }

            if ($tab === 'alat-rusak') {
                fputcsv($handle, ['No', 'Tanggal', 'Nama Alat', 'Nama Peminjam', 'Petugas Pengembalian', 'Stok Rusak', 'Kondisi Barang'], ';');
                $query = BorrowingItem::with(['tool', 'borowing.mahasiswa', 'borowing.prosesOleh'])
                    ->whereIn('kondisi_saat_kembali', ['Rusak Ringan', 'Rusak Berat', 'Tidak Layak']);
                if ($from) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengembalian_aktual', '>=', $from));
                if ($to) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengembalian_aktual', '<=', $to));
                $query->orderByDesc('id_borrowings_item')->chunk(100, function ($items) use ($handle) {
                    foreach ($items as $i => $item) {
                        fputcsv($handle, [
                            $i + 1, 
                            $item->borowing?->tgl_pengembalian_aktual?->format('Y-m-d H:i'), 
                            $item->tool?->nama_alat, 
                            $item->borowing?->mahasiswa?->nama_lengkap, 
                            $item->borowing?->prosesOleh?->nama_lengkap, 
                            $item->jumlah_unit, 
                            $item->kondisi_saat_kembali
                        ], ';');
                    }
                });
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $tab = $request->tab ?? 'rekap-peminjaman';
        $from = $request->from;
        $to = $request->to;
        $records = collect();
        $stats = [];

        $tabTitles = [
            'rekap-peminjaman' => 'Laporan Rekap Peminjaman',
            'alat-sering-dipinjam' => 'Laporan Alat Sering Dipinjam',
            'alat-dipinjam' => 'Laporan Alat Sedang Dipinjam',
            'log-mutasi-stok' => 'Laporan Log Mutasi Stok',
            'inventaris-barang' => 'Laporan Inventaris Barang',
            'rekap-per-mahasiswa' => 'Rekapitulasi Per Mahasiswa',
            'alat-rusak' => 'Laporan Alat Rusak',
        ];
        $title = $tabTitles[$tab] ?? 'Laporan';

        if ($tab === 'rekap-peminjaman') {
            $query = Borowing::with(['mahasiswa', 'borrowingItems.tool', 'prosesOleh']);
            if ($from) $query->whereDate('tgl_pengajuan', '>=', $from);
            if ($to) $query->whereDate('tgl_pengajuan', '<=', $to);
            if ($request->status) $query->where('status', $request->status);

            $allQuery = clone $query;
            $stats['totalLaporan'] = (clone $allQuery)->count();
            $stats['totalDisetujui'] = (clone $allQuery)->where('status', 'DISETUJUI')->count();
            $stats['totalDitolak'] = (clone $allQuery)->where('status', 'DITOLAK')->count();
            $stats['totalDikembalikan'] = (clone $allQuery)->where('status', 'DIKEMBALIKAN')->count();

            $records = $query->orderBy('tgl_pengajuan', 'desc')->get();
        }

        if ($tab === 'alat-sering-dipinjam') {
            $query = BorrowingItem::select('tool_id', DB::raw('COUNT(*) as total_dipinjam'), DB::raw('SUM(jumlah_unit) as total_unit'))
                ->groupBy('tool_id')->orderBy('total_unit', 'desc');
            if ($from) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengajuan', '>=', $from));
            if ($to) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengajuan', '<=', $to));
            $items = $query->get();
            $records = $items->map(function ($item) {
                $tool = Tool::find($item->tool_id);
                $activeCount = Borowing::whereHas('borrowingItems', fn($q) => $q->where('tool_id', $item->tool_id))
                    ->where('status', 'DIPINJAM')->count();
                return [
                    'tool' => $tool,
                    'total_dipinjam' => $item->total_dipinjam,
                    'total_unit' => $item->total_unit,
                    'status' => $activeCount > 0 ? 'Dipinjam' : 'Tersedia',
                ];
            });
        }

        if ($tab === 'alat-dipinjam') {
            $query = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
                ->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT']);
            $records = (clone $query)->orderBy('tgl_rencana_kembali')->get();

            $stats['totalPinjamanAktif'] = (clone $query)->count();
            $stats['totalTerlambat'] = (clone $query)->get()->filter(fn($b) => $b->is_overdue)->count();
            $stats['kembaliHariIni'] = (clone $query)->whereDate('tgl_rencana_kembali', now())->count();
            $stats['peminjamUnik'] = (clone $query)->distinct('mahasiswa_id')->count('mahasiswa_id');
        }

        if ($tab === 'log-mutasi-stok') {
            $query = ItemMutation::with(['item', 'admin']);
            if ($from) $query->whereDate('time_stamp', '>=', $from);
            if ($to) $query->whereDate('time_stamp', '<=', $to);
            if ($request->tipe_mutasi) $query->where('tipe_mutasi', $request->tipe_mutasi);
            $allData = (clone $query)->get();
            $stats['totalPergerakan'] = $allData->count();
            $stats['totalMasuk'] = $allData->where('tipe_mutasi', 'Masuk')->count();
            $stats['totalKeluar'] = $allData->where('tipe_mutasi', 'Keluar')->count();
            $records = $query->orderBy('time_stamp', 'desc')->get();
        }

        if ($tab === 'inventaris-barang') {
            $query = Item::query();
            if ($request->kondisi) $query->where('kondisi', $request->kondisi);
            if ($request->kategori) $query->where('kategori', $request->kategori);
            $records = $query->orderBy('nama_barang')->get();
            $stats['totalBarang'] = Item::count();
            $stats['totalStok'] = Item::sum('stok');
            $stats['kondisiBaik'] = Item::where('kondisi', 'Baik')->count();
            $stats['kondisiRusak'] = Item::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();
        }

        if ($tab === 'rekap-per-mahasiswa') {
            $records = User::whereIn('role', ['mahasiswa', 'dosen'])
                ->withCount(['borrowings as total_peminjaman'])
                ->withCount(['borrowings as total_selesai' => fn($q) => $q->where('status', 'DIKEMBALIKAN')])
                ->withCount(['borrowings as total_disetujui' => fn($q) => $q->where('status', 'DISETUJUI')])
                ->withCount(['borrowings as total_dipinjam' => fn($q) => $q->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT'])])
                ->orderBy('total_peminjaman', 'desc')->get();
            $stats['totalMahasiswa'] = User::whereIn('role', ['mahasiswa', 'dosen'])->count();
            $stats['statusAktif'] = User::whereIn('role', ['mahasiswa', 'dosen'])->where('is_active', true)->count();
            $stats['peminjamAktif'] = User::whereIn('role', ['mahasiswa', 'dosen'])->whereHas('borrowings', fn($q) => $q->whereIn('status', ['DIPINJAM', 'DISETUJUI', 'TERLAMBAT']))->count();
        }

        if ($tab === 'alat-rusak') {
            $query = BorrowingItem::with(['tool', 'borowing.mahasiswa', 'borowing.prosesOleh'])
                ->whereIn('kondisi_saat_kembali', ['Rusak Ringan', 'Rusak Berat', 'Tidak Layak']);
            if ($from) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengembalian_aktual', '>=', $from));
            if ($to) $query->whereHas('borowing', fn($q) => $q->whereDate('tgl_pengembalian_aktual', '<=', $to));
            $records = $query->orderByDesc('id_borrowings_item')->get();
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('tab', 'title', 'from', 'to', 'records', 'stats'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'laporan_' . $tab . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
