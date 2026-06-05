<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMutation extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_log';
    protected $table = 'item_mutations';
    protected $fillable = ['id_barang', 'tipe_mutasi', 'jumlah', 'stok_sebelum', 'stok_sesudah', 'keterangan', 'dilakukan_oleh', 'time_stamp'];

    protected function casts(): array
    {
        return [
            'time_stamp' => 'datetime',
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang', 'id_barang');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
