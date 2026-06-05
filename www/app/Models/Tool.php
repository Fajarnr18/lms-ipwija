<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'kode_alat', 'nama_alat', 'kategori', 'deskripsi',
    'stok_total', 'stok_tersedia', 'status_alat', 'lokasi', 'foto_alat',
])]
class Tool extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_alat';

    protected function casts(): array
    {
        return [
            'stok_total' => 'integer',
            'stok_tersedia' => 'integer',
        ];
    }

    #[Scope]
    protected function tersedia(Builder $query): void
    {
        $query->where('status_alat', 'Tersedia')->where('stok_tersedia', '>', 0);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search) {
            return $query->where(function (Builder $q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('kode_alat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if ($status) {
            return $query->where('status_alat', $status);
        }
        return $query;
    }
}
