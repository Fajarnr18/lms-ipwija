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
                $q->where('kode_barang', 'like', "%{$request->search}%")
                  ->orWhere('nama_barang', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        $mutations = collect();
        $totalPergerakan = 0;
        $totalMasuk = 0;
        $totalKeluar = 0;

        if ($request->tab === 'mutasi') {
            $mQuery = ItemMutation::with(['item', 'admin']);

            if ($request->search) {
                $search = $request->search;
                $mQuery->whereHas('item', function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            }

            if ($request->from) {
                $mQuery->whereDate('time_stamp', '>=', $request->from);
            }

            if ($request->to) {
                $mQuery->whereDate('time_stamp', '<=', $request->to);
            }

            if ($request->tipe_mutasi) {
                $mQuery->where('tipe_mutasi', $request->tipe_mutasi);
            }

            $totalPergerakan = (clone $mQuery)->count();
            $totalMasuk = (clone $mQuery)->where('tipe_mutasi', 'Masuk')->count();
            $totalKeluar = (clone $mQuery)->where('tipe_mutasi', 'Keluar')->count();

            $mutations = $mQuery->orderBy('time_stamp', 'desc')->paginate(20);
        }

        $totalBarang = Item::count();
        $baikCount = Item::where('kondisi', 'Baik')->count();
        $rusakCount = Item::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Tidak Layak'])->count();
        $totalStok = Item::sum('stok');
        $kategoris = Item::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        return view('admin.inventaris.index', compact('items', 'mutations', 'totalPergerakan', 'totalMasuk', 'totalKeluar', 'totalBarang', 'baikCount', 'rusakCount', 'totalStok', 'kategoris'));
    }

    public function create(): View
    {
        return view('admin.inventaris.create');
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
            'foto_barang' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'kode_barang.max' => 'Kode barang maksimal 20 karakter.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 100 karakter.',
            'kategori.required' => 'Kategori wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'satuan.required' => 'Satuan wajib diisi.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'kondisi.in' => 'Kondisi tidak valid.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'tgl_pendataan.date' => 'Format tanggal tidak valid.',
            'foto_barang.image' => 'File harus berupa gambar.',
            'foto_barang.mimes' => 'Format gambar harus png, jpg, jpeg, atau webp.',
            'foto_barang.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->except('foto_barang');
        if ($request->hasFile('foto_barang')) {
            $data['foto_barang'] = $request->file('foto_barang')->store('inventaris', 'public');
        }

        $item = Item::create($data);

        AuditLogService::log('INVENTARIS', 'CREATE', $item->id_barang, null, $item->toArray());

        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = Item::withTrashed()->findOrFail($id);
        return view('admin.inventaris.edit-form', compact('item'));
    }

    public function detail(int $id): View
    {
        $item = Item::withTrashed()->findOrFail($id);
        return view('admin.inventaris.detail', compact('item'));
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
            'foto_barang' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'kode_barang.max' => 'Kode barang maksimal 20 karakter.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 100 karakter.',
            'kategori.required' => 'Kategori wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'satuan.required' => 'Satuan wajib diisi.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'kondisi.in' => 'Kondisi tidak valid.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'tgl_pendataan.required' => 'Tanggal pendataan wajib diisi.',
            'tgl_pendataan.date' => 'Format tanggal tidak valid.',
            'foto_barang.image' => 'File harus berupa gambar.',
            'foto_barang.mimes' => 'Format gambar harus png, jpg, jpeg, atau webp.',
            'foto_barang.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $before = $item->toArray();
        $data = $request->except('foto_barang');
        
        if ($request->hasFile('foto_barang')) {
            if ($item->foto_barang && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->foto_barang)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_barang);
            }
            $data['foto_barang'] = $request->file('foto_barang')->store('inventaris', 'public');
        }

        $item->update($data);

        AuditLogService::log('INVENTARIS', 'UPDATE', $id, $before, $item->toArray());

        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = Item::withTrashed()->findOrFail($id);
        $before = $item->toArray();
        $item->delete();

        AuditLogService::log('INVENTARIS', 'DELETE', $id, $before, null);

        return redirect()->route('admin.inventaris.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function mutasi(int $id): View
    {
        $item = Item::withTrashed()->findOrFail($id);
        $mutations = ItemMutation::where('id_barang', $id)->orderBy('time_stamp', 'desc')->get();
        return view('admin.inventaris.mutasi', compact('item', 'mutations'));
    }

    public function mutasiStore(Request $request, int $id): RedirectResponse
    {
        $item = Item::withTrashed()->findOrFail($id);

        $request->validate([
            'tipe_mutasi' => 'required|in:Masuk,Keluar,Penyesuaian',
            'jumlah' => $request->tipe_mutasi === 'Penyesuaian' ? 'required|integer|min:0' : 'required|integer|min:1',
            'keterangan' => 'required|string',
            'tgl_mutasi' => 'nullable|date',
        ], [
            'tipe_mutasi.required' => 'Tipe mutasi wajib dipilih.',
            'tipe_mutasi.in' => 'Tipe mutasi tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah harus lebih dari 0.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'tgl_mutasi.date' => 'Format tanggal tidak valid.',
        ]);

        $stokSebelum = $item->stok;
        $stokSesudah = match ($request->tipe_mutasi) {
            'Masuk' => $stokSebelum + $request->jumlah,
            'Keluar' => $stokSebelum - $request->jumlah,
            'Penyesuaian' => $request->jumlah,
        };

        if ($request->tipe_mutasi === 'Keluar' && $stokSebelum < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
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
                'time_stamp' => $request->tgl_mutasi ? \Carbon\Carbon::parse($request->tgl_mutasi) : now(),
            ]);

            AuditLogService::log('INVENTARIS', 'MUTASI', $item->id_barang, ['stok' => $stokSebelum], ['stok' => $stokSesudah]);
        });

        return redirect()->route('admin.inventaris.mutasi', $id)->with('success', 'Mutasi stok berhasil.');
    }
}
