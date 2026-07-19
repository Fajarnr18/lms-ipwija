<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('dilakukan_oleh', 'like', "%{$request->search}%")
                  ->orWhere('modul', 'like', "%{$request->search}%")
                  ->orWhere('aksi', 'like', "%{$request->search}%");
            });
        }

        if ($request->modul) {
            $query->where('modul', $request->modul);
        }

        if ($request->pengguna) {
            $query->where('dilakukan_oleh', 'like', "%{$request->pengguna}%");
        }

        if ($request->dari) {
            $query->whereDate('time_stamp', '>=', $request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('time_stamp', '<=', $request->sampai);
        }

        $perPage = $request->per_page ?? 20;
        $logs = $query->orderBy('time_stamp', 'desc')->paginate($perPage);
        $moduls = AuditLog::select('modul')->distinct()->pluck('modul');

        // Storage size
        $totalLogs = AuditLog::count();
        $storageBytes = 0;
        try {
            $tableStats = DB::select("SHOW TABLE STATUS WHERE Name = 'audit_logs'");
            if (!empty($tableStats)) {
                $storageBytes = ($tableStats[0]->Data_length ?? 0) + ($tableStats[0]->Index_length ?? 0);
            }
        } catch (\Exception $e) {
            $storageBytes = $totalLogs * 512;
        }

        return view('admin.audit-trail.index', compact('logs', 'moduls', 'storageBytes', 'totalLogs'));
    }

    public function show(int $id_log): View
    {
        $log = AuditLog::findOrFail($id_log);

        $dataSebelum = [];
        $dataSesudah = [];
        
        $allowedFields = [
            'nama_alat', 'status_alat', 'lokasi_rak', 'stok_akhir', 'id_kategori',
            'nama_barang', 'kode_barang', 'stok', 'jumlah', 'satuan', 'kondisi',
            'judul_peminjaman', 'tgl_peminjaman', 'tgl_rencana_kembali', 'tgl_kembali',
            'status', 'nama_lengkap', 'email', 'role', 'is_active', 'nim', 'nuptk',
            'program_studi', 'tipe_mutasi', 'keterangan'
        ];

        if ($log->data_sebelum) {
            $decoded = json_decode($log->data_sebelum, true) ?? [];
            if (is_array($decoded)) {
                $decodedLower = array_change_key_case($decoded, CASE_LOWER);
                foreach ($allowedFields as $field) {
                    if (array_key_exists($field, $decodedLower)) {
                        $dataSebelum[$field] = $decodedLower[$field];
                    }
                }
            }
        }
        
        if ($log->data_sesudah) {
            $decoded = json_decode($log->data_sesudah, true) ?? [];
            if (is_array($decoded)) {
                $decodedLower = array_change_key_case($decoded, CASE_LOWER);
                foreach ($allowedFields as $field) {
                    if (array_key_exists($field, $decodedLower)) {
                        $dataSesudah[$field] = $decodedLower[$field];
                    }
                }
            }
        }

        // --- INJECTION FOR USER REQUIREMENT ---
        // The user specifically wants to see Tool details in Komparasi Data even for Peminjaman logs.
        if (strtoupper(trim($log->modul)) === 'PEMINJAMAN') {
            $borrowing = \App\Models\Borowing::with('borrowingItems.tool')->find($log->id_record);
            if ($borrowing && $borrowing->borrowingItems->first() && $borrowing->borrowingItems->first()->tool) {
                $tool = $borrowing->borrowingItems->first()->tool;
                
                $toolData = [
                    'nama_alat' => $tool->nama_alat,
                    'status_alat' => $tool->status_alat,
                    'lokasi_rak' => $tool->lokasi_rak,
                    'stok_akhir' => $tool->stok_akhir,
                    'id_kategori' => $tool->id_kategori,
                ];

                // Combine the tool data with the existing data to show what they want, in the exact order.
                $dataSebelum = array_merge($toolData, $dataSebelum);
                $dataSesudah = array_merge($toolData, $dataSesudah);
            }
        }

        $perubahanTerdeteksi = !empty($dataSebelum) && !empty($dataSesudah) && $dataSebelum !== $dataSesudah;

        return view('admin.audit-trail.detail', compact('log', 'dataSebelum', 'dataSesudah', 'perubahanTerdeteksi'));
    }

    public function export(Request $request)
    {
        $query = AuditLog::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('dilakukan_oleh', 'like', "%{$request->search}%")
                  ->orWhere('modul', 'like', "%{$request->search}%")
                  ->orWhere('aksi', 'like', "%{$request->search}%");
            });
        }

        if ($request->modul) $query->where('modul', $request->modul);
        if ($request->pengguna) $query->where('dilakukan_oleh', 'like', "%{$request->pengguna}%");
        if ($request->dari) $query->whereDate('time_stamp', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('time_stamp', '<=', $request->sampai);

        $filename = 'audit_trail_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['No', 'Waktu', 'User', 'Role', 'Modul', 'Aksi', 'IP Address', 'Keterangan']);

            $query->orderBy('time_stamp', 'desc')->chunk(100, function ($logs) use ($handle) {
                foreach ($logs as $i => $log) {
                    $keterangan = $log->aksi . ' pada ' . $log->modul . ' (ID: ' . $log->id_record . ')';
                    fputcsv($handle, [
                        $i + 1,
                        $log->time_stamp?->format('Y-m-d H:i:s'),
                        $log->dilakukan_oleh,
                        $log->role_pelaku,
                        $log->modul,
                        $log->aksi,
                        $log->ip_address,
                        $keterangan,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
