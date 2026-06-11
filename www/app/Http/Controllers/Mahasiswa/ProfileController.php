<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('mahasiswa.profil.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$user->id}",
        ], [
            'email.email' => 'Format email tidak valid.',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ];

        if ($request->filled('password_baru')) {
            $request->validate([
                'password' => 'required|string',
                'password_baru' => 'required|string|min:8',
                'konfirmasi_password_baru' => 'required|string|same:password_baru',
            ], [
                'password_baru.min' => 'Password baru minimal 8 karakter.',
                'konfirmasi_password_baru.same' => 'Konfirmasi password baru tidak cocok.',
            ]);

            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Password saat ini tidak valid.']);
            }

            $data['password'] = Hash::make($request->password_baru);
        }

        $before = $user->fresh()->toArray();
        $user->update($data);
        $after = $user->fresh()->toArray();

        AuditLogService::log('USER', 'UPDATE_PROFILE', $user->id, $before, $after);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
