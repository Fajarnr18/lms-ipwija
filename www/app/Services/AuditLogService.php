<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    public static function log(
        string $modul,
        string $aksi,
        $idRecord,
        ?array $dataSebelum = null,
        ?array $dataSesudah = null
    ): void {
        $user = auth()->user();

        AuditLog::create([
            'time_stamp' => now(),
            'dilakukan_oleh' => $user ? $user->id . ':' . $user->nama_lengkap : 'system',
            'role_pelaku' => $user ? $user->role : 'system',
            'modul' => $modul,
            'aksi' => $aksi,
            'id_record' => (string) $idRecord,
            'data_sebelum' => $dataSebelum ? json_encode($dataSebelum) : null,
            'data_sesudah' => $dataSesudah ? json_encode($dataSesudah) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
