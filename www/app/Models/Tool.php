<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'kode_alat', 'nama_alat', 'kategori', 'deskripsi',
    'stok_total', 'stok_tersedia', 'status_alat', 'lokasi', 'foto_alat', 'kondisi_fisik',
])]
class Tool extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_alat';

    protected static function booted(): void
    {
        static::saving(function (Tool $tool) {
            if ($tool->stok_tersedia <= 0 && $tool->status_alat === 'TERSEDIA') {
                $tool->status_alat = 'MAINTENANCE';
            }
            // Dihapus pengecekan elseif yang memaksa status menjadi TERSEDIA 
            // jika stok > 0, karena ini akan menimpa perubahan manual dari Admin.
        });
    }

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
        $query->where('status_alat', 'TERSEDIA')->where('stok_tersedia', '>', 0);
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
        if ($status === 'RUSAK') {
            return $query->where(function (Builder $q) {
                $q->where('status_alat', 'RUSAK')
                  ->orWhere('stok_rusak', '>', 0);
            });
        } elseif ($status) {
            return $query->where('status_alat', $status);
        }
        return $query;
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto_alat && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto_alat)) {
            return \Illuminate\Support\Facades\Storage::url($this->foto_alat);
        }
        if ($this->foto_alat && str_starts_with($this->foto_alat, 'http')) {
            return $this->foto_alat;
        }
        // Fallback auto-generate image from UI Avatars based on tool name
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama_alat) . '&background=random&color=fff&size=512';
    }
}
