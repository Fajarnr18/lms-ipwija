<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingItem extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_borrowings_item';
    protected $table = 'borrowing_items';
    protected $fillable = ['borrowing_id', 'tool_id', 'jumlah_unit', 'kondisi_saat_kembali', 'catatan_pengembalian'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function borowing()
    {
        return $this->belongsTo(Borowing::class, 'borrowing_id', 'id_borrowing');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id_alat');
    }
}
