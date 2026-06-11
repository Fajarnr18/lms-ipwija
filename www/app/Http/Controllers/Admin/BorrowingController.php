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
        $tab = $request->tab ?? 'semua';
        $query = Borowing::with(['mahasiswa', 'borrowingItems.tool']);

        $tabStatuses = [
            'menunggu' => ['MENUNGGU'],
            'aktif' => ['DISETUJUI', 'DIPINJAM'],
            'selesai' => ['DIKEMBALIKAN', 'DITOLAK'],
        ];

        if ($tab !== 'semua' && isset($tabStatuses[$tab])) {
            $query->whereIn('status', $tabStatuses[$tab]);
        }

        $query->search($request->search);

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.peminjaman.index', compact('borrowings', 'tab'));
    }

    public function show(Borowing $borowing): View
    {
        $borowing->load(['mahasiswa', 'prosesOleh', 'borrowingItems.tool']);
        return view('admin.peminjaman.show', compact('borowing'));
    }

    public function approve(Borowing $borowing): RedirectResponse
    {
        DB::transaction(function () use ($borowing) {
            $old = $borowing->toArray();

            $borowing->update([
                'status' => 'DISETUJUI',
                'diproses_oleh' => auth()->id(),
                'tgl_diproses' => now(),
            ]);

            foreach ($borowing->borrowingItems as $item) {
                $tool = Tool::find($item->tool_id);
                if ($tool) {
                    $tool->decrement('stok_tersedia', $item->jumlah_unit);
                }
            }

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
        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $borowing) {
            $old = $borowing->toArray();

            foreach ($borowing->borrowingItems as $item) {
                Tool::where('id_alat', $item->tool_id)
                    ->increment('stok_tersedia', $item->jumlah_unit);
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

    public function aktif(): View
    {
        $borrowings = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
            ->where('status', 'DIPINJAM')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.peminjaman.aktif', compact('borrowings'));
    }

    public function kembali(Request $request, Borowing $borowing): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id_borrowings_item' => 'required|exists:borrowing_items,id_borrowings_item',
            'items.*.kondisi_saat_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'items.*.catatan_pengembalian' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $borowing) {
            $old = $borowing->toArray();

            foreach ($request->items as $itemData) {
                $item = BorrowingItem::findOrFail($itemData['id_borrowings_item']);

                $item->update([
                    'kondisi_saat_kembali' => $itemData['kondisi_saat_kembali'],
                    'catatan_pengembalian' => $itemData['catatan_pengembalian'] ?? null,
                ]);

                if (in_array($itemData['kondisi_saat_kembali'], ['Rusak Ringan', 'Rusak Berat'])) {
                    Tool::where('id_alat', $item->tool_id)
                        ->decrement('stok_total', $item->jumlah_unit);
                } else {
                    Tool::where('id_alat', $item->tool_id)
                        ->increment('stok_tersedia', $item->jumlah_unit);
                }
            }

            $borowing->update([
                'status' => 'DIKEMBALIKAN',
                'tgl_pengembalian_aktual' => now(),
            ]);

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
