<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => auth()->user()]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'program_studi' => $request->program_studi,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password, ['rounds' => 10]);
        }

        $user->update($data);

        return response()->json(['message' => 'Profil berhasil diperbarui.', 'data' => $user]);
    }
}
