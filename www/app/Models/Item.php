<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'kode_barang', 'nama_barang', 'kategori', 'deskripsi',
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
}
