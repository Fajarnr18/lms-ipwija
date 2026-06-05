<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'time_stamp', 'dilakukan_oleh', 'role_pelaku', 'modul',
    'aksi', 'id_record', 'data_sebelum', 'data_sesudah', 'ip_address',
])]
class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'time_stamp' => 'datetime',
        ];
    }
}
