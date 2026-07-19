<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use App\Services\AuditLogService;
use App\Services\N8NWebhookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $validCart = [];
        $tools = collect();
        $changed = false;

        if (!empty($cart)) {
            $tools = Tool::whereIn('id_alat', array_keys($cart))->get();
            foreach ($cart as $id => $item) {
                $tool = $tools->firstWhere('id_alat', $id);
                if ($tool && $tool->status_alat === 'TERSEDIA' && $tool->stok_tersedia > 0) {
                    $validCart[$id] = $item;
                } else {
                    $changed = true;
                }
            }
        }

        if ($changed) {
            $cart = $validCart;
            session()->put('cart', $cart);
            $tools = Tool::whereIn('id_alat', array_keys($cart))->get();
        }

        return view('dosen.keranjang.index', compact('cart', 'tools'));
    }

    public function tambah(Request $request, int $id_alat): JsonResponse|RedirectResponse
    {
        $tool = Tool::findOrFail($id_alat);

        if ($tool->status_alat !== 'TERSEDIA' || $tool->stok_tersedia < 1) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Alat tidak tersedia.'], 400);
            }
            return back()->with('error', 'Alat tidak tersedia.');
        }

        $jumlah = max(1, $request->integer('jumlah', 1));

        $cart = session()->get('cart', []);

        $currentQty = isset($cart[$id_alat]) ? $cart[$id_alat]['jumlah_unit'] : 0;

        if (($currentQty + $jumlah) > $tool->stok_tersedia) {
            if ($request->expectsJson()) {
                return response()->json(['error' => "Stok tersedia hanya {$tool->stok_tersedia}."], 400);
            }
            return back()->with('error', "Stok tersedia hanya {$tool->stok_tersedia}.");
        }

        $cart[$tool->id_alat] = [
            'tool_id' => $tool->id_alat,
            'nama_alat' => $tool->nama_alat,
            'kode_alat' => $tool->kode_alat,
            'foto_alat' => $tool->foto_alat,
            'jumlah_unit' => $currentQty + $jumlah,
            'stok_tersedia' => $tool->stok_tersedia,
        ];

        session()->put('cart', $cart);

        $cartCount = collect(session('cart', []))->sum('jumlah_unit');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "$jumlah {$tool->nama_alat} ditambahkan ke keranjang.",
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->route('dosen.keranjang.index')->with('success', "$jumlah alat ditambahkan ke keranjang.");
    }

    public function hapus(int $id): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Alat dihapus dari keranjang.');
    }

    public function ajukan(Request $request): RedirectResponse
    {
        $request->validate([
            'tgl_rencana_pinjam' => 'required|date|after_or_equal:today',
            'tgl_rencana_kembali' => 'required|date|after:tgl_rencana_pinjam',
            'keperluan' => 'required|string',
            'kuantitas' => 'nullable|array',
            'kuantitas.*' => 'integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        foreach ($request->input('kuantitas', []) as $id => $qty) {
            if (isset($cart[$id])) {
                $cart[$id]['jumlah_unit'] = (int) $qty;
            }
        }

        session()->put('cart', $cart);

        try {
            DB::transaction(function () use ($request, $cart) {
                $activeCount = Borowing::where('mahasiswa_id', auth()->id())
                    ->whereIn('status', ['DISETUJUI', 'DIPINJAM', 'TERLAMBAT'])
                    ->count();

                if ($activeCount > 0) {
                    throw new Exception('Masih ada peminjaman aktif.');
                }

                foreach ($cart as $item) {
                    $tool = Tool::lockForUpdate()->find($item['tool_id']);
                    if (!$tool || $tool->status_alat !== 'TERSEDIA' || $tool->stok_tersedia < $item['jumlah_unit']) {
                        throw new Exception("Alat {$tool?->nama_alat} tidak tersedia atau stok tidak mencukupi.");
                    }
                }

                $borowing = Borowing::create([
                    'mahasiswa_id' => auth()->id(),
                    'tgl_pengajuan' => now(),
                    'tgl_rencana_pinjam' => $request->tgl_rencana_pinjam,
                    'tgl_rencana_kembali' => $request->tgl_rencana_kembali,
                    'keperluan' => $request->keperluan,
                    'status' => 'MENUNGGU',
                ]);

                foreach ($cart as $item) {
                    BorrowingItem::create([
                        'borrowing_id' => $borowing->id_borrowing,
                        'tool_id' => $item['tool_id'],
                        'jumlah_unit' => $item['jumlah_unit'],
                    ]);

                    Tool::where('id_alat', $item['tool_id'])
                        ->decrement('stok_tersedia', $item['jumlah_unit']);

                    $t = Tool::find($item['tool_id']);
                    if ($t && $t->stok_tersedia == 0) {
                        $t->update(['status_alat' => 'MAINTENANCE']);
                    }
                }

                session()->forget('cart');

                N8NWebhookService::sendBorrowingNotification($borowing, 'submitted', 'Pengajuan peminjaman Anda berhasil dikirim dan sedang menunggu persetujuan Admin.');
                AuditLogService::log('PEMINJAMAN', 'CREATE', $borowing->id_borrowing, null, $borowing->toArray());
            });

            return redirect()->route('dosen.peminjaman.index')
                ->with('success', 'Peminjaman berhasil diajukan.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

