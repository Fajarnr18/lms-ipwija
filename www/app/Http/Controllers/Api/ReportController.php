<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function borrowings(Request $request): JsonResponse
    {
        $query = Borowing::with(['mahasiswa', 'borrowingItems.tool', 'prosesOleh']);

        if ($request->from) {
            $query->whereDate('tgl_pengajuan', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('tgl_pengajuan', '<=', $request->to);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('tgl_pengajuan', 'desc')->paginate($request->per_page ?? 10);

        return response()->json([
            'data' => $borrowings->items(),
            'pagination' => [
                'current_page' => $borrowings->currentPage(),
                'last_page' => $borrowings->lastPage(),
                'per_page' => $borrowings->perPage(),
                'total' => $borrowings->total(),
            ],
            'summary' => [
                'total_laporan' => $borrowings->total(),
                'total_disetujui' => (clone $query)->where('status', 'DISETUJUI')->count(),
                'total_ditolak' => (clone $query)->where('status', 'DITOLAK')->count(),
                'total_dikembalikan' => (clone $query)->where('status', 'DIKEMBALIKAN')->count(),
            ]
        ]);
    }

    public function items(Request $request): JsonResponse
    {
        $query = Item::query();

        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $items = $query->orderBy('nama_barang')->paginate($request->per_page ?? 10);

        return response()->json([
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
            'summary' => [
                'total_barang' => $items->total(),
                'total_stok' => (clone $query)->sum('stok'),
                'kondisi_baik' => (clone $query)->where('kondisi', 'Baik')->count(),
                'kondisi_rusak' => (clone $query)->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count(),
            ]
        ]);
    }
}
