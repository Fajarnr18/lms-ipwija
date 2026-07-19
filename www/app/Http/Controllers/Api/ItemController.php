<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemMutation;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Item::query();

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_barang', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%")
                  ->orWhere('kategori', 'like', "%{$s}%");
            });
        }

        $items = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::with(['mutations' => function($q) {
            $q->orderBy('time_stamp', 'desc')->with('admin:id,nama_lengkap');
        }])->findOrFail($id);

        return response()->json(['data' => $item]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kode_barang' => 'required|string|unique:items,kode_barang',
            'nama_barang' => 'required|string',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'kondisi' => 'required|string',
            'lokasi' => 'required|string',
            'tgl_pendataan' => 'nullable|date',
            'deskripsi' => 'nullable|string',
        ]);

        $item = Item::create([
            ...$request->all(),
            'tgl_pendataan' => $request->tgl_pendataan ?? now(),
        ]);

        if ($item->stok > 0) {
            ItemMutation::create([
                'id_barang' => $item->id_barang,
                'tipe_mutasi' => 'MASUK',
                'jumlah' => $item->stok,
                'stok_sebelum' => 0,
                'stok_sesudah' => $item->stok,
                'keterangan' => 'Stok awal',
                'dilakukan_oleh' => auth()->id(),
                'time_stamp' => now(),
            ]);
        }

        AuditLogService::log('INVENTARIS', 'CREATE', $item->id_barang, null, $item->toArray());

        return response()->json(['message' => 'Barang inventaris berhasil ditambahkan.', 'data' => $item], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        $old = $item->toArray();

        $request->validate([
            'kode_barang' => 'required|string|unique:items,kode_barang,' . $id . ',id_barang',
            'nama_barang' => 'required|string',
            'kategori' => 'required|string',
            'satuan' => 'required|string',
            'kondisi' => 'required|string',
            'lokasi' => 'required|string',
            'tgl_pendataan' => 'nullable|date',
            'deskripsi' => 'nullable|string',
        ]);

        $item->update($request->except(['stok']));

        AuditLogService::log('INVENTARIS', 'UPDATE', $item->id_barang, $old, $item->fresh()->toArray());

        return response()->json(['message' => 'Data barang berhasil diperbarui.', 'data' => $item]);
    }

    public function mutate(Request $request, int $id): JsonResponse
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'tipe_mutasi' => 'required|in:MASUK,KELUAR,PENYESUAIAN',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $oldStok = $item->stok;
            $newStok = $oldStok;
            $jumlah = $request->jumlah;
            $tipe = $request->tipe_mutasi;

            if ($tipe === 'KELUAR') {
                if ($oldStok < $jumlah) {
                    throw new \Exception("Stok tidak mencukupi untuk dikeluarkan.");
                }
                $newStok -= $jumlah;
            } elseif ($tipe === 'MASUK') {
                $newStok += $jumlah;
            } else {
                $newStok = $jumlah;
                $diff = $newStok - $oldStok;
                $jumlah = abs($diff);
            }

            $item->update(['stok' => $newStok]);

            $mutation = ItemMutation::create([
                'id_barang' => $item->id_barang,
                'tipe_mutasi' => $tipe,
                'jumlah' => $jumlah,
                'stok_sebelum' => $oldStok,
                'stok_sesudah' => $newStok,
                'keterangan' => $request->keterangan,
                'dilakukan_oleh' => auth()->id(),
                'time_stamp' => now(),
            ]);

            DB::commit();

            AuditLogService::log('INVENTARIS', 'MUTATION', $item->id_barang, ['stok' => $oldStok], ['stok' => $newStok, 'mutasi' => $mutation->toArray()]);

            return response()->json(['message' => 'Mutasi stok berhasil dicatat.', 'data' => $item]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
