<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $userId = auth()->id();
        $query = Borowing::where('mahasiswa_id', $userId)
            ->with(['borrowingItems.tool']);

        if ($request->status && $request->status !== 'SEMUA') {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        $countTotal = Borowing::where('mahasiswa_id', $userId)->count();
        $countDipinjam = Borowing::where('mahasiswa_id', $userId)->where('status', 'DIPINJAM')->count();
        $countSelesai = Borowing::where('mahasiswa_id', $userId)->where('status', 'DIKEMBALIKAN')->count();
        $countDitolak = Borowing::where('mahasiswa_id', $userId)->where('status', 'DITOLAK')->count();

        return view('mahasiswa.peminjaman.index', compact(
            'borrowings', 'countTotal', 'countDipinjam', 'countSelesai', 'countDitolak'
        ));
    }

    public function show(int $id): View
    {
        $borowing = Borowing::with(['mahasiswa', 'prosesOleh', 'borrowingItems.tool'])
            ->findOrFail($id);

        if ($borowing->mahasiswa_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('mahasiswa.peminjaman.show', compact('borowing'));
    }

    public function exportPdf(int $id)
    {
        $borowing = Borowing::with(['borrowingItems.tool', 'mahasiswa', 'prosesOleh'])
            ->findOrFail($id);

        if ($borowing->mahasiswa_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.bukti_pinjam', compact('borowing'));
        
        return $pdf->download('Bukti_Pinjam_REQ-' . str_pad($borowing->id_borrowing, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function riwayat(Request $request): View
    {
        $query = Borowing::where('mahasiswa_id', auth()->id())
            ->with(['borrowingItems.tool']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('mahasiswa.peminjaman.riwayat', compact('borrowings'));
    }
}

