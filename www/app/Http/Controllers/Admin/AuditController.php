<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('dilakukan_oleh', 'like', "%{$request->search}%")
                  ->orWhere('modul', 'like', "%{$request->search}%")
                  ->orWhere('aksi', 'like', "%{$request->search}%");
            });
        }

        if ($request->modul) {
            $query->where('modul', $request->modul);
        }

        if ($request->aksi) {
            $query->where('aksi', 'like', "%{$request->aksi}%");
        }

        if ($request->role_pelaku) {
            $query->where('role_pelaku', $request->role_pelaku);
        }

        if ($request->dari) {
            $query->whereDate('time_stamp', '>=', $request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('time_stamp', '<=', $request->sampai);
        }

        $logs = $query->orderBy('time_stamp', 'desc')->paginate(20);
        $moduls = AuditLog::select('modul')->distinct()->pluck('modul');

        return view('admin.audit-trail.index', compact('logs', 'moduls'));
    }
}
