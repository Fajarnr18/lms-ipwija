<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tool::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_alat', 'like', "%{$request->search}%")
                  ->orWhere('kode_alat', 'like', "%{$request->search}%")
                  ->orWhere('kategori', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->status_alat) {
            $query->where('status_alat', $request->status_alat);
        }

        $tools = $query->where('status_alat', '!=', 'MAINTENANCE')->orderBy('nama_alat')->paginate(12);
        $kategoris = Tool::select('kategori')->distinct()->pluck('kategori');

        return view('mahasiswa.katalog.index', compact('tools', 'kategoris'));
    }

    public function show(Request $request, int $id_alat): View
    {
        $tool = Tool::findOrFail($id_alat);

        if ($tool->status_alat === 'MAINTENANCE') {
            abort(404, 'Alat sedang dalam perbaikan (Maintenance).');
        }

        $query = Borowing::where('mahasiswa_id', auth()->id())
            ->whereHas('borrowingItems', function ($q) use ($id_alat) {
                $q->where('tool_id', $id_alat);
            });

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_borrowing', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('mahasiswa.katalog.show', compact('tool', 'borrowings'));
    }
}
