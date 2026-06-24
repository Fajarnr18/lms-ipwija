<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Tool::query()->tersedia();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_alat', 'like', "%{$request->search}%")
                  ->orWhere('kode_alat', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $tools = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 12);

        return response()->json([
            'data' => $tools->items(),
            'pagination' => [
                'current_page' => $tools->currentPage(),
                'last_page' => $tools->lastPage(),
                'per_page' => $tools->perPage(),
                'total' => $tools->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $tool = Tool::findOrFail($id);
        return response()->json(['data' => $tool]);
    }

    public function categories(): JsonResponse
    {
        $categories = Tool::tersedia()
            ->select('kategori')
            ->distinct()
            ->pluck('kategori');

        return response()->json(['data' => $categories]);
    }
}
