<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tool\StoreToolRequest;
use App\Http\Requests\Tool\UpdateToolRequest;
use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Tool::query();

        if ($request->user()->role === 'mahasiswa') {
            $query->tersedia();
        }

        $query->search($request->search)
              ->filterStatus($request->status_alat);

        $tools = $query->orderBy('created_at', 'desc')
                       ->paginate($request->per_page ?? 10);

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

    public function store(StoreToolRequest $request): JsonResponse
    {
        $tool = Tool::create($request->validated());

        return response()->json([
            'message' => 'Alat berhasil ditambahkan.',
            'data' => $tool,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $tool = Tool::withTrashed()->findOrFail($id);

        return response()->json([
            'data' => $tool,
        ]);
    }

    public function update(UpdateToolRequest $request, int $id): JsonResponse
    {
        $tool = Tool::withTrashed()->findOrFail($id);
        $tool->update($request->validated());

        return response()->json([
            'message' => 'Alat berhasil diperbarui.',
            'data' => $tool,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $tool = Tool::findOrFail($id);
        $tool->delete();

        return response()->json([
            'message' => 'Alat berhasil dihapus.',
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $tool = Tool::withTrashed()->findOrFail($id);
        $tool->restore();

        return response()->json([
            'message' => 'Alat berhasil dipulihkan.',
            'data' => $tool,
        ]);
    }
}
