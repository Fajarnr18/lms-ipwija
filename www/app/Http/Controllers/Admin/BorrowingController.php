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

        if ($tab !== 'semua') {
            $statuses = match ($tab) {
                'menunggu' => ['Menunggu'],
                'aktif' => ['Disetujui', 'Dipinjam'],
                'selesai' => ['Dikembalikan'],
                default => [],
            };
            $query->whereIn('status', $statuses);
        }

        $query->search($request->search);

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.borrowings.index', compact('borrowings', 'tab'));
    }

    public function approve(Borowing $borowing): RedirectResponse
    {
        DB::transaction(function () use ($borowing) {
            $old = $borowing->toArray();

            $borowing->update([
                'status' => 'Disetujui',
                'diproses_oleh' => auth()->id(),
                'tgl_diproses' => now(),
            ]);

            foreach ($borowing->borrowingItems as $item) {
                $tool = Tool::find($item->tool_id);
                if ($tool) {
                    $tool->decrement('stok_tersedia', $item->jumlah_unit);
                }
            }

            AuditLogService::log('Peminjaman', 'APPROVE', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
            N8NWebhookService::send('borrowing.approved', $borowing);
        });

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, Borowing $borowing): RedirectResponse
    {
        $request->validate(['catatan_admin' => 'required|string']);

        DB::transaction(function () use ($request, $borowing) {
            $old = $borowing->toArray();

            foreach ($borowing->borrowingItems as $item) {
                Tool::where('id_alat', $item->tool_id)
                    ->increment('stok_tersedia', $item->jumlah_unit);
            }

            $borowing->update([
                'status' => 'Ditolak',
                'diproses_oleh' => auth()->id(),
                'tgl_diproses' => now(),
                'catatan_admin' => $request->catatan_admin,
            ]);

            AuditLogService::log('Peminjaman', 'REJECT', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
            N8NWebhookService::send('borrowing.rejected', $borowing);
        });

        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function returnForm(Borowing $borowing): View
    {
        $borowing->load(['mahasiswa', 'borrowingItems.tool']);
        return view('admin.borrowings.return', compact('borowing'));
    }

    public function returnSubmit(Request $request, Borowing $borowing): RedirectResponse
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
                'status' => 'Dikembalikan',
                'tgl_pengembalian_aktual' => now(),
            ]);

            AuditLogService::log('Peminjaman', 'RETURN', $borowing->id_borrowing, $old, $borowing->fresh()->toArray());
            N8NWebhookService::send('borrowing.returned', $borowing);
        });

        return redirect()->route('admin.borrowings.index', ['tab' => 'aktif'])
            ->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function show(int $id): View
    {
        $borowing = Borowing::with(['mahasiswa', 'prosesOleh', 'borrowingItems.tool'])->findOrFail($id);
        return view('admin.borrowings.show', compact('borowing'));
    }
}
