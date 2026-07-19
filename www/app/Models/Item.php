<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'kode_barang', 'nama_barang', 'foto_barang', 'kategori', 'deskripsi',
    'stok', 'satuan', 'kondisi', 'lokasi', 'tgl_pendataan',
])]
class Item extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'tgl_pendataan' => 'date:Y-m-d',
        ];
    }

    public function mutations()
    {
        return $this->hasMany(ItemMutation::class, 'id_barang', 'id_barang');
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto_barang && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto_barang)) {
            return \Illuminate\Support\Facades\Storage::url($this->foto_barang);
        }
        if ($this->foto_barang && str_starts_with($this->foto_barang, 'http')) {
            return $this->foto_barang;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama_barang) . '&background=random&color=fff&size=512';
    }
}
