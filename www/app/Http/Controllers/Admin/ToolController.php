<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tool::query();

        $query->search($request->search)
              ->filterStatus($request->status_alat);

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $tools = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = Tool::select('kategori')->distinct()->pluck('kategori');

        $totalInventaris = Tool::count();
        $kondisiBaik = Tool::where('status_alat', 'TERSEDIA')->count();
        $sedangDipinjam = BorrowingItem::whereHas('borowing', function ($q) {
            $q->where('status', 'DIPINJAM');
        })->distinct('tool_id')->count('tool_id');
        $butuhPerbaikan = Tool::where('status_alat', 'MAINTENANCE')->count();

        return view('admin.alat.index', compact(
            'tools', 'kategoris', 'totalInventaris', 'kondisiBaik', 'sedangDipinjam', 'butuhPerbaikan'
        ));
    }

    public function show(Request $request, int $id_alat): View
    {
        $tool = Tool::withTrashed()->findOrFail($id_alat);

        $query = Borowing::whereHas('borrowingItems', function ($q) use ($id_alat) {
            $q->where('tool_id', $id_alat);
        })->with('mahasiswa');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_borrowing', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.alat.show', compact('tool', 'borrowings'));
    }

    public function create(): View
    {
        return view('admin.alat.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_alat' => 'required|string|max:20|unique:tools,kode_alat',
            'nama_alat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'status_alat' => 'required|in:TERSEDIA,MAINTENANCE',
            'kondisi_fisik' => 'nullable|string|max:50',
            'lokasi' => 'required|string|max:50',
            'foto_alat' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048',
        ], [
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'stok_tersedia.lte' => 'Stok tersedia tidak boleh melebihi stok total.',
            'status_alat.in' => 'Status alat tidak valid.',
            'foto_alat.image' => 'File harus berupa gambar.',
            'foto_alat.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('foto_alat')) {
            $path = $request->file('foto_alat')->store('alat', 'public');
            $validated['foto_alat'] = $path;
        }

        if ((int) $validated['stok_tersedia'] === 0) {
            $validated['status_alat'] = 'MAINTENANCE';
        }

        $tool = Tool::create($validated);

        AuditLogService::log('ALAT', 'CREATE', $tool->id_alat, null, $tool->toArray());

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(int $id_alat): View
    {
        $tool = Tool::withTrashed()->findOrFail($id_alat);
        return view('admin.alat.edit', compact('tool'));
    }

    public function update(Request $request, int $id_alat): RedirectResponse
    {
        $tool = Tool::withTrashed()->findOrFail($id_alat);
        $before = $tool->toArray();

        $validated = $request->validate([
            'kode_alat' => "required|string|max:20|unique:tools,kode_alat,{$id_alat},id_alat",
            'nama_alat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'status_alat' => 'required|in:TERSEDIA,MAINTENANCE',
            'kondisi_fisik' => 'nullable|string|max:50',
            'lokasi' => 'required|string|max:50',
            'foto_alat' => 'nullable|image|mimes:png,jpeg,jpg,webp|max:2048',
        ], [
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'stok_tersedia.lte' => 'Stok tersedia tidak boleh melebihi stok total.',
            'status_alat.in' => 'Status alat tidak valid.',
            'foto_alat.image' => 'File harus berupa gambar.',
            'foto_alat.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('foto_alat')) {
            if ($tool->foto_alat) {
                Storage::disk('public')->delete($tool->foto_alat);
            }
            $path = $request->file('foto_alat')->store('alat', 'public');
            $validated['foto_alat'] = $path;
        }

        if ((int) $validated['stok_tersedia'] === 0) {
            $validated['status_alat'] = 'MAINTENANCE';
        }

        $tool->update($validated);

        AuditLogService::log('ALAT', 'UPDATE', $id_alat, $before, $tool->toArray());

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(int $id_alat): RedirectResponse
    {
        $tool = Tool::withTrashed()->findOrFail($id_alat);

        if ($tool->trashed()) {
            $tool->forceDelete();
        } else {
            $tool->delete();
        }

        AuditLogService::log('ALAT', 'DELETE', $id_alat, $tool->toArray(), null);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
