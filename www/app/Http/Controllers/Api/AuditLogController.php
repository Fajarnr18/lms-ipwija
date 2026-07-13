<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AuditLog::query();

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('modul', 'like', "%{$s}%")
                  ->orWhere('aksi', 'like', "%{$s}%")
                  ->orWhere('role_pelaku', 'like', "%{$s}%");
            });
        }
        
        if ($request->modul) {
            $query->where('modul', $request->modul);
        }

        if ($request->from) {
            $query->whereDate('time_stamp', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('time_stamp', '<=', $request->to);
        }

        $logs = $query->orderBy('time_stamp', 'desc')->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }
}
