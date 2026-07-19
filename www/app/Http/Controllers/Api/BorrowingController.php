<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use App\Services\AuditLogService;
use App\Services\N8NWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Borowing::with(['borrowingItems.tool', 'mahasiswa']);

        if ($request->user()->role !== 'admin') {
            $query->where('mahasiswa_id', auth()->id());
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search && $request->user()->role === 'admin') {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('id_borrowing', 'like', "%{$s}%")
                  ->orWhere('keperluan', 'like', "%{$s}%")
                  ->orWhereHas('mahasiswa', function ($q) use ($s) {
                      $q->where('nama_lengkap', 'like', "%{$s}%")
                        ->orWhere('nim', 'like', "%{$s}%");
                  });
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return response()->json([
            'data' => $borrowings->items(),
            'pagination' => [
                'current_page' => $borrowings->currentPage(),
                'last_page' => $borrowings->lastPage(),
                'per_page' => $borrowings->perPage(),
                'total' => $borrowings->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = Borowing::with(['mahasiswa', 'prosesOleh', 'borrowingItems.tool']);

        if (auth()->user()->role !== 'admin') {
            $query->where('mahasiswa_id', auth()->id());
        }

        $borowing = $query->findOrFail($id);

        return response()->json(['data' => $borowing]);
    }

    public function approve(int $id): JsonResponse
    {
        $borowing = Borowing::findOrFail($id);
        $st = strtoupper(trim($borowing->status ?? ''));

        if ($st !== 'MENUNGGU') {
            return response()->json(['message' => 'Hanya peminjaman dengan status MENUNGGU yang dapat disetujui.'], 422);
        }

        $borowing->update([
            'status' => 'DISETUJUI',
            'proses_oleh' => auth()->id(),
        ]);

        AuditLogService::log('PEMINJAMAN', 'APPROVE', $borowing->id_borrowing, null, $borowing->toArray());
        N8NWebhookService::sendBorrowingNotification($borowing, 'approved', 'Peminjaman disetujui');

        return response()->json(['message' => 'Peminjaman berhasil disetujui.', 'data' => $borowing]);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $borowing = Borowing::findOrFail($id);
        $st = strtoupper(trim($borowing->status ?? ''));

        if ($st !== 'MENUNGGU') {
            return response()->json(['message' => 'Hanya peminjaman dengan status MENUNGGU yang dapat ditolak.'], 422);
        }

        $request->validate(['alasan_penolakan' => 'required|string']);

        $borowing->update([
            'status' => 'DITOLAK',
            'alasan_penolakan' => $request->alasan_penolakan,
            'proses_oleh' => auth()->id(),
        ]);

        AuditLogService::log('PEMINJAMAN', 'REJECT', $borowing->id_borrowing, null, $borowing->toArray());
        N8NWebhookService::sendBorrowingNotification($borowing, 'rejected', 'Alasan: ' . $request->alasan_penolakan);

        return response()->json(['message' => 'Peminjaman ditolak.', 'data' => $borowing]);
    }

    public function proses(int $id): JsonResponse
    {
        $borowing = Borowing::with('borrowingItems')->findOrFail($id);
        $st = strtoupper(trim($borowing->status ?? ''));

        if ($st !== 'DISETUJUI') {
            return response()->json(['message' => 'Hanya peminjaman dengan status DISETUJUI yang dapat diproses.'], 422);
        }

        foreach ($borowing->borrowingItems as $item) {
            $tool = Tool::findOrFail($item->id_alat);
            if ($tool->stok_tersedia < $item->jumlah_unit) {
                return response()->json(['message' => "Stok {$tool->nama_alat} tidak mencukupi."], 422);
            }
            $tool->decrement('stok_tersedia', $item->jumlah_unit);
        }

        $borowing->update(['status' => 'DIPINJAM']);

        AuditLogService::log('PEMINJAMAN', 'PROSES', $borowing->id_borrowing, null, $borowing->toArray());

        return response()->json(['message' => 'Peminjaman sedang diproses.', 'data' => $borowing]);
    }

    public function kembali(Request $request, int $id): JsonResponse
    {
        $borowing = Borowing::with('borrowingItems')->findOrFail($id);
        $st = strtoupper(trim($borowing->status ?? ''));

        if ($st !== 'DIPINJAM') {
            return response()->json(['message' => 'Hanya peminjaman dengan status DIPINJAM yang dapat dikembalikan.'], 422);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id_borrowings_item' => 'required|exists:borrowing_items,id_borrowings_item',
            'items.*.kondisi_saat_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Tidak Layak',
            'tgl_pengembalian_aktual' => 'required|date',
        ]);

        foreach ($request->items as $itemData) {
            $borrowingItem = BorrowingItem::findOrFail($itemData['id_borrowings_item']);
            $borrowingItem->update([
                'kondisi_saat_kembali' => $itemData['kondisi_saat_kembali'],
                'catatan_pengembalian' => $itemData['catatan_pengembalian'] ?? null,
                'tgl_kembali' => now(),
            ]);

            $tool = Tool::findOrFail($borrowingItem->id_alat);
            $kondisi = $itemData['kondisi_saat_kembali'];

                if (in_array($kondisi, ['Rusak Ringan', 'Rusak Berat', 'Tidak Layak'])) {
                $tool->decrement('stok_total', $borrowingItem->jumlah_unit);
                $tool->increment('stok_rusak', $borrowingItem->jumlah_unit);
            } else {
                $tool->increment('stok_tersedia', $borrowingItem->jumlah_unit);
                
                if ($tool->stok_tersedia > 0 && $tool->status_alat === 'MAINTENANCE') {
                    $tool->update(['status_alat' => 'TERSEDIA']);
                }
            }
        }

        $borowing->update([
            'status' => 'DIKEMBALIKAN',
            'tgl_pengembalian_aktual' => $request->tgl_pengembalian_aktual,
            'catatan_admin' => $request->catatan_petugas,
        ]);

        AuditLogService::log('PEMINJAMAN', 'RETURN', $borowing->id_borrowing, null, $borowing->toArray());
        N8NWebhookService::sendBorrowingNotification($borowing, 'returned', 'Alat berhasil dikembalikan.');

        return response()->json(['message' => 'Pengembalian berhasil dicatat.', 'data' => $borowing]);
    }

    public function my(Request $request): JsonResponse
    {
        $query = Borowing::where('mahasiswa_id', auth()->id())
            ->with(['borrowingItems.tool']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return response()->json([
            'data' => $borrowings->items(),
            'pagination' => [
                'current_page' => $borrowings->currentPage(),
                'last_page' => $borrowings->lastPage(),
                'per_page' => $borrowings->perPage(),
                'total' => $borrowings->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_rencana_pinjam' => 'required|date',
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_rencana_pinjam',
            'keperluan' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.tool_id' => 'required|exists:tools,id_alat',
            'items.*.jumlah_unit' => 'required|integer|min:1'
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $borowing = Borowing::create([
                'mahasiswa_id' => auth()->id(),
                'tgl_pengajuan' => now(),
                'tgl_rencana_pinjam' => $request->tgl_rencana_pinjam,
                'tgl_rencana_kembali' => $request->tgl_rencana_kembali,
                'keperluan' => $request->keperluan,
                'status' => 'MENUNGGU',
            ]);

            foreach ($request->items as $item) {
                $tool = Tool::findOrFail($item['tool_id']);
                
                if ($tool->stok_tersedia < $item['jumlah_unit']) {
                    throw new \Exception("Stok alat {$tool->nama_alat} tidak mencukupi. Tersedia: {$tool->stok_tersedia}");
                }

                BorrowingItem::create([
                    'borrowing_id' => $borowing->id_borrowing,
                    'tool_id' => $item['tool_id'],
                    'jumlah_unit' => $item['jumlah_unit'],
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            AuditLogService::log('PEMINJAMAN', 'CREATE', $borowing->id_borrowing, null, $borowing->toArray());
            
            return response()->json([
                'message' => 'Pengajuan peminjaman berhasil disubmit.',
                'data' => $borowing->load('borrowingItems.tool')
            ], 201);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

