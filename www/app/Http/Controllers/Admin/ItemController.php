<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemMutation;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = Item::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%")
                  ->orWhere('kategori', 'like', "%{$request->search}%");
            });
        }

        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.items.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kode_barang' => 'required|string|max:20|unique:items,kode_barang',
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Tidak Layak',
            'lokasi' => 'required|string|max:50',
            'tgl_pendataan' => 'required|date',
        ]);

        $item = Item::create($request->all());

        AuditLogService::log('Barang', 'CREATE', $item->id_barang, null, $item->toArray());

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = Item::withTrashed()->findOrFail($id);
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $item = Item::withTrashed()->findOrFail($id);

        $request->validate([
            'kode_barang' => "required|string|max:20|unique:items,kode_barang,{$id},id_barang",
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Tidak Layak',
            'lokasi' => 'required|string|max:50',
            'tgl_pendataan' => 'required|date',
        ]);

        $before = $item->toArray();
        $item->update($request->all());

        AuditLogService::log('Barang', 'UPDATE', (string) $id, $before, $item->toArray());

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = Item::findOrFail($id);
        $item->delete();

        AuditLogService::log('Barang', 'DELETE', (string) $id, $item->toArray(), null);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function mutation(int $id): View
    {
        $item = Item::withTrashed()->findOrFail($id);
        $mutations = ItemMutation::where('id_barang', $id)->orderBy('time_stamp', 'desc')->get();
        return view('admin.items.mutation', compact('item', 'mutations'));
    }

    public function mutationStore(Request $request, int $id): RedirectResponse
    {
        $item = Item::withTrashed()->findOrFail($id);

        $request->validate([
            'tipe_mutasi' => 'required|in:Masuk,Keluar,Penyesuaian',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string',
        ]);

        $stokSebelum = $item->stok;
        $stokSesudah = match ($request->tipe_mutasi) {
            'Masuk' => $stokSebelum + $request->jumlah,
            'Keluar' => $stokSebelum - $request->jumlah,
            'Penyesuaian' => $request->jumlah,
        };

        if ($request->tipe_mutasi === 'Keluar' && $stokSebelum < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi untuk mutasi keluar.');
        }

        DB::transaction(function () use ($request, $item, $stokSebelum, $stokSesudah) {
            $item->update(['stok' => $stokSesudah]);

            ItemMutation::create([
                'id_barang' => $item->id_barang,
                'tipe_mutasi' => $request->tipe_mutasi,
                'jumlah' => $request->jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $request->keterangan,
                'dilakukan_oleh' => auth()->id(),
                'time_stamp' => now(),
            ]);

            AuditLogService::log('Barang', 'MUTASI', $item->id_barang, ['stok' => $stokSebelum], ['stok' => $stokSesudah]);
        });

        return redirect()->route('admin.items.mutation', $id)->with('success', 'Mutasi stok berhasil.');
    }
}
