<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::whereIn('role', ['mahasiswa', 'dosen']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('nim', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->withCount(['borrowings as total_peminjaman' => function ($q) {
            $q->where('status', '!=', 'Ditolak');
        }])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengubah status admin.');
        }

        $user->update(['is_active' => !$user->is_active]);

        AuditLogService::log('User', 'UPDATE', (string) $id, null, null);

        return back()->with('success', 'Status user berhasil diubah.');
    }
}
