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
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        $cart = session('cart', []);
        $tools = [];

        foreach ($cart as $id => $item) {
            $tool = Tool::find($id);
            if ($tool) {
                $tools[] = [
                    'id_alat' => $tool->id_alat,
                    'kode_alat' => $tool->kode_alat,
                    'nama_alat' => $tool->nama_alat,
                    'kategori' => $tool->kategori,
                    'stok_tersedia' => $tool->stok_tersedia,
                    'foto_alat' => $tool->foto_alat,
                    'jumlah_unit' => $item['jumlah_unit'] ?? 1,
                ];
            }
        }

        return response()->json(['data' => $tools, 'total' => count($tools)]);
    }

    public function tambah(Request $request, int $id_alat): JsonResponse
    {
        $tool = Tool::findOrFail($id_alat);

        if ($tool->status_alat !== 'TERSEDIA' || $tool->stok_tersedia <= 0) {
            return response()->json(['message' => 'Alat tidak tersedia.'], 422);
        }

        $jumlah = max(1, $request->integer('jumlah', 1));
        $cart = session('cart', []);
        $currentQty = $cart[$id_alat]['jumlah_unit'] ?? 0;

        if (($currentQty + $jumlah) > $tool->stok_tersedia) {
            return response()->json(['message' => 'Jumlah melebihi stok tersedia.'], 422);
        }

        $cart[$id_alat] = [
            'id_alat' => $tool->id_alat,
            'jumlah_unit' => $currentQty + $jumlah,
        ];

        session()->put('cart', $cart);

        return response()->json([
            'message' => "{$tool->nama_alat} ditambahkan ke keranjang.",
            'cart_total' => count($cart),
        ]);
    }

    public function hapus(int $id): JsonResponse
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Alat dihapus dari keranjang.', 'cart_total' => count($cart)]);
    }

    public function update(Request $request): JsonResponse
    {
        $cart = session('cart', []);
        foreach ($request->input('kuantitas', []) as $id => $qty) {
            if (isset($cart[$id])) {
                $cart[$id]['jumlah_unit'] = max(1, (int) $qty);
            }
        }
        session()->put('cart', $cart);

        return response()->json(['message' => 'Keranjang diperbarui.', 'cart_total' => count($cart)]);
    }

    public function ajukan(Request $request): JsonResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return response()->json(['message' => 'Keranjang kosong.'], 422);
        }

        foreach ($request->input('kuantitas', []) as $id => $qty) {
            if (isset($cart[$id])) {
                $cart[$id]['jumlah_unit'] = (int) $qty;
            }
        }

        session()->put('cart', $cart);

        $request->validate([
            'keperluan' => 'required|string',
            'tgl_rencana_pinjam' => 'required|date',
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_rencana_pinjam',
        ]);

        $borowing = DB::transaction(function () use ($request, $cart) {
            $borowing = Borowing::create([
                'mahasiswa_id' => auth()->id(),
                'keperluan' => $request->keperluan,
                'tgl_pengajuan' => now(),
                'tgl_rencana_pinjam' => $request->tgl_rencana_pinjam,
                'tgl_rencana_kembali' => $request->tgl_rencana_kembali,
                'status' => 'MENUNGGU',
            ]);

            foreach ($cart as $id_alat => $item) {
                BorrowingItem::create([
                    'id_borrowing' => $borowing->id_borrowing,
                    'id_alat' => $id_alat,
                    'jumlah_unit' => $item['jumlah_unit'],
                ]);
            }

            return $borowing;
        });

        session()->forget('cart');

        AuditLogService::log('PEMINJAMAN', 'CREATE', $borowing->id_borrowing, null, $borowing->toArray());
        N8NWebhookService::send('submitted', $borowing);

        return response()->json(['message' => 'Peminjaman berhasil diajukan.', 'data' => $borowing], 201);
    }
}
