<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\N8NWebhookService;
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

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        if ($request->prodi) {
            $query->where('program_studi', 'like', "%{$request->prodi}%");
        }

        $users = $query->withCount(['borrowings as total_peminjaman' => function ($q) {
            $q->where('status', '!=', 'DITOLAK');
        }])->orderBy('created_at', 'desc')->paginate(20);

        $totalUser = User::whereIn('role', ['mahasiswa', 'dosen'])->count();
        $userAktif = User::whereIn('role', ['mahasiswa', 'dosen'])->where('is_active', true)->count();
        $nonaktif = User::whereIn('role', ['mahasiswa', 'dosen'])->where('is_active', false)->count();
        $mahasiswaBaru = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $programStudis = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->whereNotNull('program_studi')
            ->select('program_studi')
            ->distinct()
            ->pluck('program_studi')
            ->sort();

        return view('admin.users.index', compact('users', 'totalUser', 'userAktif', 'nonaktif', 'mahasiswaBaru', 'programStudis'));
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengubah status admin.');
        }

        $before = $user->toArray();
        $user->update(['is_active' => !$user->is_active]);
        $after = $user->fresh()->toArray();

        AuditLogService::log('USER', 'CHANGE_STATUS', $id, $before, $after);

        if ($user->is_active) {
            N8NWebhookService::send('user_approved', $user, [
                'message' => 'Akun Anda telah disetujui. Silakan login.',
            ]);
        }

        return back()->with('success', 'Status user berhasil diubah.');
    }

    public function reject(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus admin.');
        }

        $before = $user->toArray();
        $user->delete();

        AuditLogService::log('USER', 'REJECT_AND_DELETE', $id, $before, null);

        N8NWebhookService::send('user_rejected', $user, [
            'message' => 'Pendaftaran akun Anda ditolak oleh Admin.',
        ]);

        return back()->with('success', 'Akun pendaftar berhasil ditolak dan dihapus.');
    }
}
