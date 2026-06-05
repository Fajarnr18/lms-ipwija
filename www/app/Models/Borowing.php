<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Borowing extends Model
{
    protected $table = 'borowings';
    protected $primaryKey = 'id_borrowing';
    public $timestamps = true;

    protected $fillable = [
        'mahasiswa_id', 'tgl_pengajuan', 'tgl_rencana_pinjam', 'tgl_rencana_kembali',
        'keperluan', 'status', 'diproses_oleh', 'tgl_diproses',
        'catatan_admin', 'tgl_pengembalian_aktual',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_borrowing';
    }

    protected function casts(): array
    {
        return [
            'tgl_pengajuan' => 'datetime',
            'tgl_rencana_pinjam' => 'date:Y-m-d',
            'tgl_rencana_kembali' => 'date:Y-m-d',
            'tgl_diproses' => 'datetime',
            'tgl_pengembalian_aktual' => 'datetime',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function prosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function borrowingItems()
    {
        return $this->hasMany(BorrowingItem::class, 'borrowing_id', 'id_borrowing');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            return $query->where(function (Builder $q) use ($search) {
                $q->whereHas('mahasiswa', function (Builder $q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%");
                });
            });
        }
        return $query;
    }
}
