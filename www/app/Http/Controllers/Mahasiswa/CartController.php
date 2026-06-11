<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use App\Services\AuditLogService;
use App\Services\N8NWebhookService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $tools = Tool::whereIn('id_alat', array_keys($cart))->get();
        return view('mahasiswa.keranjang.index', compact('cart', 'tools'));
    }

    public function tambah(int $id_alat): RedirectResponse
    {
        $tool = Tool::findOrFail($id_alat);

        if ($tool->status_alat !== 'TERSEDIA' || $tool->stok_tersedia < 1) {
            return back()->with('error', 'Alat tidak tersedia.');
        }

        $cart = session()->get('cart', []);

        $currentQty = isset($cart[$id_alat]) ? $cart[$id_alat]['jumlah_unit'] : 0;

        if (($currentQty + 1) > $tool->stok_tersedia) {
            return back()->with('error', "Stok tersedia hanya {$tool->stok_tersedia}.");
        }

        $cart[$tool->id_alat] = [
            'tool_id' => $tool->id_alat,
            'nama_alat' => $tool->nama_alat,
            'kode_alat' => $tool->kode_alat,
            'jumlah_unit' => $currentQty + 1,
            'stok_tersedia' => $tool->stok_tersedia,
        ];

        session()->put('cart', $cart);

        return redirect()->route('keranjang.index')->with('success', 'Alat ditambahkan ke keranjang.');
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
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        try {
            DB::transaction(function () use ($request, $cart) {
                $activeCount = Borowing::where('mahasiswa_id', auth()->id())
                    ->whereIn('status', ['DISETUJUI', 'DIPINJAM'])
                    ->count();

                if ($activeCount > 0) {
                    throw new Exception('Masih ada peminjaman aktif.');
                }

                foreach ($cart as $item) {
                    $tool = Tool::lockForUpdate()->find($item['tool_id']);
                    if (!$tool || $tool->stok_tersedia < $item['jumlah_unit']) {
                        throw new Exception("Stok {$tool?->nama_alat} tidak mencukupi.");
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
                }

                session()->forget('cart');

                N8NWebhookService::send('submitted', [
                    'borrowingId' => $borowing->id_borrowing,
                    'userName' => auth()->user()->nama_lengkap,
                    'email' => auth()->user()->email,
                ]);
                AuditLogService::log('PEMINJAMAN', 'CREATE', $borowing->id_borrowing, null, $borowing->toArray());
            });

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil diajukan.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
