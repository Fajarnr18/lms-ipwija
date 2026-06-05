<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tool::query();

        if ($request->user()->role === 'mahasiswa') {
            $query->tersedia();
        }

        $query->search($request->search)
              ->filterStatus($request->status_alat);

        $tools = $query->orderBy('created_at', 'desc')
                       ->paginate(10);

        return view('tools.index', compact('tools'));
    }

    public function create(): View
    {
        return view('tools.create');
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
            'status_alat' => 'required|in:Tersedia,Dipinjam,Rusak,Dalam Perbaikan',
            'lokasi' => 'required|string|max:50',
        ], [
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'stok_tersedia.lte' => 'Stok tersedia tidak boleh melebihi stok total.',
            'status_alat.in' => 'Status alat tidak valid.',
        ]);

        Tool::create($validated);

        return redirect()->route('tools.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $tool = Tool::withTrashed()->findOrFail($id);
        return view('tools.edit', compact('tool'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $tool = Tool::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'kode_alat' => "required|string|max:20|unique:tools,kode_alat,{$id},id_alat",
            'nama_alat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'status_alat' => 'required|in:Tersedia,Dipinjam,Rusak,Dalam Perbaikan',
            'lokasi' => 'required|string|max:50',
        ], [
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'stok_tersedia.lte' => 'Stok tersedia tidak boleh melebihi stok total.',
            'status_alat.in' => 'Status alat tidak valid.',
        ]);

        $tool->update($validated);

        return redirect()->route('tools.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $tool = Tool::findOrFail($id);
        $tool->delete();

        return redirect()->route('tools.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
