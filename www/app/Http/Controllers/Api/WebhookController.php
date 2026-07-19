<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borowing;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Get overdue borrowings for N8N scheduler.
     * Protected by simple API Key from header or query string.
     */
    public function overdue(Request $request)
    {
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        
        if ($apiKey !== config('services.n8n.api_key')) {
            return response()->json(['error' => 'Unauthorized. Invalid API Key.'], 401);
        }

        $overdueBorrowings = Borowing::with(['mahasiswa', 'borrowingItems.tool'])
            ->where('status', 'DIPINJAM')
            ->where('tgl_rencana_kembali', '<', now())
            ->get();

        $results = [];

        foreach ($overdueBorrowings as $borrowing) {
            $user = $borrowing->mahasiswa;
            if (!$user) continue;

            $items = $borrowing->borrowingItems->map(function ($item) {
                return [
                    'nama_alat' => $item->tool?->nama_alat ?? 'Unknown',
                    'jumlah' => $item->jumlah_unit,
                ];
            });

            $phone = $user->no_whatsapp;
            if ($phone && str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }

            $results[] = [
                'borrowingId' => $borrowing->id_borrowing,
                'tanggalHarusKembali' => $borrowing->tgl_rencana_kembali?->format('Y-m-d'),
                'keperluan' => $borrowing->keperluan,
                'items' => $items,
                'user' => [
                    'nama' => $user->nama_lengkap,
                    'email' => $user->email,
                    'no_whatsapp' => $phone,
                    'jenis_notifikasi' => $user->jenis_notifikasi ?? 'Email'
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'count' => count($results),
            'data' => $results,
            'timestamp' => now()->toIso8601String()
        ]);
    }
}

