<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function admin(): JsonResponse
    {
        $totalAlat = Tool::count();
        $peminjamanAktif = Borowing::whereIn('status', ['DISETUJUI', 'DIPINJAM', 'TERLAMBAT'])->count();
        $stokRendah = Tool::where('stok_tersedia', '<=', 3)->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();

        return response()->json([
            'data' => [
                'total_alat' => $totalAlat,
                'peminjaman_aktif' => $peminjamanAktif,
                'stok_rendah' => $stokRendah,
                'total_mahasiswa' => $totalMahasiswa,
                'total_dosen' => $totalDosen,
            ],
        ]);
    }

    public function mahasiswa(): JsonResponse
    {
        $userId = auth()->id();
        $countMenunggu = Borowing::where('mahasiswa_id', $userId)->where('status', 'MENUNGGU')->count();
        $countBerjalan = Borowing::where('mahasiswa_id', $userId)->whereIn('status', ['DISETUJUI', 'DIPINJAM', 'TERLAMBAT'])->count();
        $countSelesai = Borowing::where('mahasiswa_id', $userId)->where('status', 'DIKEMBALIKAN')->count();
        $cartCount = count(session('cart', []));

        return response()->json([
            'data' => [
                'menunggu' => $countMenunggu,
                'berjalan' => $countBerjalan,
                'selesai' => $countSelesai,
                'keranjang' => $cartCount,
            ],
        ]);
    }

    public function dosen(): JsonResponse
    {
        return $this->mahasiswa();
    }
}

