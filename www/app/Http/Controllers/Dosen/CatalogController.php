<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
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

        $tools = $query->orderBy('nama_alat')->paginate(12);
        $kategoris = Tool::select('kategori')->distinct()->pluck('kategori');

        return view('dosen.katalog.index', compact('tools', 'kategoris'));
    }
}
