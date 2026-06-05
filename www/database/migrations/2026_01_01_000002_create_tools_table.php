<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id('id_alat');
            $table->string('kode_alat', 20)->unique();
            $table->string('nama_alat', 100);
            $table->string('kategori', 50);
            $table->text('deskripsi');
            $table->integer('stok_total');
            $table->integer('stok_tersedia');
            $table->enum('status_alat', ['Tersedia', 'Dipinjam', 'Rusak', 'Dalam Perbaikan']);
            $table->string('lokasi', 50);
            $table->string('foto_alat', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
