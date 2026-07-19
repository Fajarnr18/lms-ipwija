<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\N8NWebhookService;
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
                'email' => 'Akun Anda sedang menunggu persetujuan Admin atau telah dinonaktifkan',
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
            'nim' => 'required|string',
            'email' => 'required|string|email|max:255',
            'program_studi' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'terms' => 'required|accepted',
            'no_whatsapp' => 'nullable|numeric|digits_between:10,13',
            'jenis_notifikasi' => 'nullable|in:Email,Whatsapp',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nim.required' => 'NIM/NUPTK wajib diisi',
            'email.required' => 'Email wajib diisi',
            'program_studi.required' => 'Program studi wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password_confirmation.same' => 'Konfirmasi password tidak sama',
            'email.email' => 'Format email tidak valid',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            'no_whatsapp.numeric' => 'Nomor WhatsApp harus berupa angka',
            'no_whatsapp.digits_between' => 'Nomor WhatsApp harus antara 10 hingga 13 digit',
        ]);

        $nim = $request->nim;

        $existingByNim = User::where('nim', $nim)->first();
        if ($existingByNim) {
            return back()->withErrors(['nim' => 'NIM/NUPTK sudah digunakan (oleh akun yang aktif)'])->onlyInput('nim');
        }

        $existingByEmail = User::where('email', $request->email)->first();
        if ($existingByEmail) {
            return back()->withErrors(['email' => 'Email sudah digunakan (oleh akun yang aktif)'])->onlyInput('email');
        }

        try {
            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nim' => $nim,
                'email' => $request->email,
                'program_studi' => $request->program_studi,
                'password' => Hash::make($request->password, ['rounds' => 10]),
                'no_whatsapp' => $request->no_whatsapp,
                'jenis_notifikasi' => $request->no_whatsapp ? ($request->jenis_notifikasi ?? 'Email') : 'Email',
                'role' => 'mahasiswa',
                'is_active' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('REGISTRATION ERROR: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }

        AuditLogService::log('User', 'CREATE', $user->id, null, $user->toArray());
        
        N8NWebhookService::send('registered', $user, [
            'message' => 'Registrasi berhasil. Menunggu persetujuan admin.',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan Admin.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
