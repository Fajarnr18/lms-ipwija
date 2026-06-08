<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Borowing::where('mahasiswa_id', auth()->id())->with(['borrowingItems.tool']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('dosen.borrowings.index', compact('borrowings'));
    }

    public function show(int $id): View
    {
        $borowing = Borowing::with(['mahasiswa', 'prosesOleh', 'borrowingItems.tool'])
            ->where('mahasiswa_id', auth()->id())
            ->findOrFail($id);

        return view('dosen.borrowings.show', compact('borowing'));
    }
}
