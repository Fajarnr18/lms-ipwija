<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = $request->email;

        $user = User::where('email', $input)
            ->orWhere('nim', $input)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau NIM/NUPTK atau password salah',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }

        return redirect()->route('mhs.dashboard');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim_nip' => 'required|string',
            'email' => 'required|string|email|max:255',
            'program_studi' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'konfirmasi_password' => 'required|string|same:password',
        ], [
            'password.min' => 'Password minimal 8 karakter',
            'konfirmasi_password.same' => 'Konfirmasi password tidak sama',
            'email.email' => 'Format email tidak valid',
        ]);

        $nim = $request->nim_nip;

        $existingByNim = User::where('nim', $nim)->first();
        if ($existingByNim) {
            return back()->withErrors(['nim_nip' => 'NIM/NUPTK sudah digunakan'])->onlyInput('nim_nip');
        }

        $existingByEmail = User::where('email', $request->email)->first();
        if ($existingByEmail) {
            return back()->withErrors(['email' => 'Email sudah digunakan'])->onlyInput('email');
        }

        $role = preg_match('/^\d{16}$/', $nim) ? 'dosen' : 'mahasiswa';

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nim' => $nim,
            'email' => $request->email,
            'program_studi' => $request->program_studi,
            'password' => Hash::make($request->password, ['rounds' => 10]),
            'role' => $role,
            'is_active' => true,
        ]);

        AuditLogService::log('User', 'CREATE', $user->id, null, $user->toArray());

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
