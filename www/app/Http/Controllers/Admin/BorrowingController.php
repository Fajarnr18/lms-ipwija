<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use App\Services\AuditLogService;
use App\Services\N8NWebhookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Borowing::with(['mahasiswa', 'borrowingItems.tool']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_borrowing', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  })
                  ->orWhereHas('borrowingItems.tool', function ($q) use ($search) {
                      $q->where('nama_alat', 'like', "%{$search}%")
                        ->orWhere('kode_alat', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($role = $request->role) {
            $roleMap = ['mahasiswa' => 'mahasiswa', 'dosen' => 'dosen'];
            if (isset($roleMap[$role])) {
                $query->whereHas('mahasiswa', function ($q) use ($role) {
                    $q->where('role', $role);
                });
            }
        }

        if ($dateFrom = $request->date_from) {
            $query->whereDate('tgl_pengajuan', '>=', $dateFrom);
        }

        if ($dateTo = $request->date_to) {
            $query->whereDate('tgl_pengajuan', '<=', $dateTo);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $countMenunggu = Borowing::where('status', 'MENUNGGU')->count();
        $countDipinjam = Borowing::where('status', 'DIPINJAM')->count();
        $countTerlambat = Borowing::where('status', 'DIPINJAM')
            ->whereDate('tgl_rencana_kembali', '<', now())
            ->count();

        return view('admin.peminjaman.index', compact(
            'borrowings', 'countMenunggu', 'countDipinjam', 'countTerlambat'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Borowing::with(['mahasiswa', 'borrowingItems.tool']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_borrowing', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  })
                  ->orWhereHas('borrowingItems.tool', function ($q) use ($search) {
                      $q->where('nama_alat', 'like', "%{$search}%")
                        ->orWhere('kode_alat', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($role = $request->role) {
            $query->whereHas('mahasiswa', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();

        $filename = 'peminjaman-' . now()->format('Ymd-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($borrowings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID Peminjaman', 'Tanggal Pengajuan', 'Nama Peminjam', 'NIM/NUPTK',
                'Role', 'Keperluan', 'Tanggal Pinjam', 'Estimasi Kembali',
                'Alat', 'Status', 'Catatan Admin',
            ]);

            foreach ($borrowings as $b) {
                $alatList = $b->borrowingItems->map(function ($item) {
                    return $item->tool?->nama_alat . ' (x' . $item->jumlah_unit . ')';
                })->implode('; ');

                fputcsv($handle, [
                    $b->id_borrowing,
                    $b->tgl_pengajuan?->format('d/m/Y H:i'),
                    $b->mahasiswa?->nama_lengkap,
                    $b->mahasiswa?->nim,
                    $b->mahasiswa?->role,
                    $b->keperluan,
                    $b->tgl_rencana_pinjam?->format('d/m/Y'),
                    $b->tgl_rencana_kembali?->format('d/m/Y'),
                    $alatList,
                    $b->status,
                    $b->catatan_admin,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Borowing $borowing): View
    {
        $borowing->load(['mahasiswa', 'prosesOleh', 'borrowingItems.tool']);
        return view('admin.peminjaman.show', compact('borowing'));
    }

    public function updateCatatan(Request $request, Borowing $borowing): RedirectResponse
    {
        $request->validate(['catatan' => 'nullable|string']);

        $borowing->update(['catatan_admin' => $request->catatan]);

        return redirect()->back()->with('success', 'Catatan penggunaan lab berhasil diperbarui.');
    }

    public function approve(Borowing $borowing): RedirectResponse
    {
        if (strtoupper(trim($borowing->status ?? '')) !== 'MENUNGGU') {
            return redirect()->back()->with('error', 'Pengajuan sudah diproses.');
        }

        DB::transaction(function () use ($borowing) {
            $old = $borowing->toArray();

            $borowing->update([
                'status' => 'DISETUJUI',
                'diproses_oleh' => auth()->id(),
                'tgl_diproses' => now(),
            ]);

            AuditLogService::log('PEMINJAMAN', 'APPROVE', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
            N8NWebhookService::send('approved', [
                'borrowingId' => $borowing->id_borrowing,
                'userName' => $borowing->mahasiswa?->nama_lengkap,
                'email' => $borowing->mahasiswa?->email,
                'tanggalPinjam' => $borowing->tgl_rencana_pinjam?->format('Y-m-d'),
            ]);
        });

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, Borowing $borowing): RedirectResponse
    {
        if (strtoupper(trim($borowing->status ?? '')) !== 'MENUNGGU') {
            return redirect()->back()->with('error', 'Pengajuan sudah diproses.');
        }

        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $borowing) {
            $old = $borowing->toArray();

            foreach ($borowing->borrowingItems as $item) {
                Tool::where('id_alat', $item->tool_id)
                    ->increment('stok_tersedia', $item->jumlah_unit);

                $t = Tool::find($item->tool_id);
                if ($t && $t->stok_tersedia > 0 && $t->status_alat === 'MAINTENANCE') {
                    $t->update(['status_alat' => 'TERSEDIA']);
                }
            }

            $borowing->update([
                'status' => 'DITOLAK',
                'diproses_oleh' => auth()->id(),
                'tgl_diproses' => now(),
                'catatan_admin' => $request->catatan_admin,
            ]);

            AuditLogService::log('PEMINJAMAN', 'REJECT', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
            N8NWebhookService::send('rejected', [
                'borrowingId' => $borowing->id_borrowing,
                'userName' => $borowing->mahasiswa?->nama_lengkap,
                'email' => $borowing->mahasiswa?->email,
                'alasanPenolakan' => $request->catatan_admin,
            ]);
        });

        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function prosesPeminjaman(Borowing $borowing): RedirectResponse
    {
        if (strtoupper(trim($borowing->status ?? '')) !== 'DISETUJUI') {
            return redirect()->back()->with('error', 'Pengajuan sudah diproses.');
        }

        DB::transaction(function () use ($borowing) {
            $old = $borowing->toArray();

            $borowing->update([
                'status' => 'DIPINJAM',
                'diproses_oleh' => auth()->id(),
                'tgl_diproses' => now(),
            ]);

            AuditLogService::log('PEMINJAMAN', 'PROSES', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
        });

        return redirect()->back()->with('success', 'Peminjaman sedang diproses.');
    }

   public function aktif(Request $request): View
{
    $query = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
        ->whereIn('status', ['DISETUJUI', 'DIPINJAM', 'DIKEMBALIKAN']);

    if ($search = $request->search) {
        $query->where(function ($q) use ($search) {
            $q->where('id_borrowing', 'like', "%{$search}%")
              ->orWhereHas('mahasiswa', function ($q) use ($search) {
                  $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
              });
        });
    }

    $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

    $totalHariIni = Borowing::where('status', 'DIKEMBALIKAN')
        ->whereDate('tgl_pengembalian_aktual', today())
        ->count();

    $menungguVerifikasi = Borowing::where('status', 'DIPINJAM')->count();

    $alatRusak = BorrowingItem::whereIn('kondisi_saat_kembali', ['Rusak Ringan', 'Rusak Berat'])
        ->whereHas('borowing', function ($q) {
            $q->where('status', 'DIKEMBALIKAN');
        })
        ->count();

    return view('admin.peminjaman.aktif', compact('borrowings', 'totalHariIni', 'menungguVerifikasi', 'alatRusak'));
}
public function formKembali(Borowing $borowing): View
    {
        $borowing->load(['mahasiswa', 'prosesOleh', 'borrowingItems.tool']);

        return view('admin.peminjaman.kembali', compact('borowing'));
    }
    public function kembali(Request $request, Borowing $borowing): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id_borrowings_item' => 'required|exists:borrowing_items,id_borrowings_item',
            'items.*.kondisi_saat_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Tidak Layak',
            'items.*.catatan_pengembalian' => 'nullable|string',
            'tgl_pengembalian_aktual' => 'required|date',
            'catatan_petugas' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $borowing) {
            $old = $borowing->toArray();

            foreach ($request->items as $itemData) {
                $item = BorrowingItem::findOrFail($itemData['id_borrowings_item']);

                $item->update([
                    'kondisi_saat_kembali' => $itemData['kondisi_saat_kembali'],
                    'catatan_pengembalian' => $itemData['catatan_pengembalian'] ?? null,
                ]);

                if (in_array($itemData['kondisi_saat_kembali'], ['Rusak Ringan', 'Rusak Berat', 'Tidak Layak'])) {
                    Tool::where('id_alat', $item->tool_id)
                        ->decrement('stok_total', $item->jumlah_unit);
                } else {
                    Tool::where('id_alat', $item->tool_id)
                        ->increment('stok_tersedia', $item->jumlah_unit);

                    $t = Tool::find($item->tool_id);
                    if ($t && $t->stok_tersedia > 0 && $t->status_alat === 'MAINTENANCE') {
                        $t->update(['status_alat' => 'TERSEDIA']);
                    }
                }
            }

            $updateData = [
                'status' => 'DIKEMBALIKAN',
                'tgl_pengembalian_aktual' => $request->tgl_pengembalian_aktual,
            ];

            if ($request->filled('catatan_petugas')) {
                $updateData['catatan_admin'] = $request->catatan_petugas;
            }

            $borowing->update($updateData);

            AuditLogService::log('PEMINJAMAN', 'RETURN', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
            N8NWebhookService::send('returned', [
                'borrowingId' => $borowing->id_borrowing,
                'userName' => $borowing->mahasiswa?->nama_lengkap,
                'email' => $borowing->mahasiswa?->email,
            ]);
        });

        return redirect()->route('admin.peminjaman.aktif')
            ->with('success', 'Pengembalian berhasil dicatat.');
    }
}
