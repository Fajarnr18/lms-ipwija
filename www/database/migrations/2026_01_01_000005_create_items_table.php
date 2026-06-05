<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('kode_barang', 20)->unique();
            $table->string('nama_barang', 100);
            $table->string('kategori', 50);
            $table->text('deskripsi');
            $table->integer('stok');
            $table->string('satuan', 20);
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Tidak Layak']);
            $table->string('lokasi', 50);
            $table->date('tgl_pendataan');
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();

            $table->index('id_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
