<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;

use App\Models\User;

use App\Services\AuditLogService;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->nim;

        $existing = User::where('nim', $input)->first();

        if ($existing) {
            return response()->json([
                'message' => 'NIM/NUPTK sudah terdaftar.',
            ], 422);
        }

        if (preg_match('/^\d{16}$/', $input)) {
            $userData = [
                'nama_lengkap' => $request->nama_lengkap,
                'nim' => $input,
                'email' => $request->email,
                'program_studi' => $request->program_studi,
                'password' => $request->password,
                'no_whatsapp' => $request->no_whatsapp,
                'jenis_notifikasi' => $request->no_whatsapp ? ($request->jenis_notifikasi ?? 'Email') : 'Email',
                'role' => 'dosen',
                'is_active' => true,
            ];
        } else {
            $userData = [
                'nama_lengkap' => $request->nama_lengkap,
                'nim' => $input,
                'email' => $request->email,
                'program_studi' => $request->program_studi,
                'password' => $request->password,
                'no_whatsapp' => $request->no_whatsapp,
                'jenis_notifikasi' => $request->no_whatsapp ? ($request->jenis_notifikasi ?? 'Email') : 'Email',
                'role' => 'mahasiswa',
                'is_active' => true,
            ];
        }
        $user = User::create($userData);

        AuditLogService::log('User', 'CREATE', $user->id, null, $user->toArray());

        $token = $user->createToken('auth-token', ['role:' . $user->role])->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required_without_all:nim|email',
            'nim' => 'required_without_all:email|string',
            
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('nim', $request->nim)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak valid.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan.',
            ], 403);
        }

        $token = $user->createToken('auth-token', ['role:' . $user->role])->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
                'redirect' => $user->role === 'admin' ? '/dashboard/admin' : ($user->role === 'dosen' ? '/dashboard/dosen' : '/dashboard/mahasiswa'),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
