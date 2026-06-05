<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_mutations', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('id_barang')->constrained('items', 'id_barang');
            $table->enum('tipe_mutasi', ['Masuk', 'Keluar', 'Penyesuaian']);
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->text('keterangan');
            $table->foreignId('dilakukan_oleh')->constrained('users', 'id');
            $table->dateTime('time_stamp');

            $table->index('id_barang');
            $table->index('time_stamp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_mutations');
    }
};
