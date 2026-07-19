<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('dosen.profil.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$user->id},id,deleted_at,NULL",
        ], [
            'email.email' => 'Email tidak valid',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_whatsapp' => $request->no_whatsapp,
            'jenis_notifikasi' => $request->jenis_notifikasi,
        ];

        if ($request->hasFile('foto_profil')) {
            $request->validate([
                'foto_profil' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $data['foto_profil'] = $request->file('foto_profil')->store('profiles', 'public');
        }

        if ($request->filled('password_baru')) {
            $request->validate([
                'password' => 'required|string',
                'password_baru' => 'required|string|min:8',
                'konfirmasi_password_baru' => 'required|string|same:password_baru',
            ], [
                'password_baru.min' => 'Password baru minimal 8 karakter.',
                'konfirmasi_password_baru.same' => 'Password tidak sama',
            ]);

            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Password salah']);
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
