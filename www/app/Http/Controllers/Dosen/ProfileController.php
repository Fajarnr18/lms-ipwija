<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('dosen.profile.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8',
            'password_baru' => 'nullable|string|min:8',
            'konfirmasi_password_baru' => 'nullable|string|same:password_baru',
        ], [
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'konfirmasi_password_baru.same' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ];

        if ($request->filled('password_baru')) {
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Password saat ini tidak valid.']);
            }
            $data['password'] = $request->password_baru;
        }

        $before = $user->fresh()->toArray();
        $user->update($data);
        $after = $user->fresh()->toArray();

        AuditLogService::log('User', 'UPDATE', $user->id, $before, $after);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
