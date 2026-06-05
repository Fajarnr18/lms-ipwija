<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borowings', function (Blueprint $table) {
            $table->id('id_borrowing');
            $table->foreignId('mahasiswa_id')->constrained('users', 'id');
            $table->dateTime('tgl_pengajuan');
            $table->date('tgl_rencana_pinjam');
            $table->date('tgl_rencana_kembali');
            $table->text('keperluan');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Dipinjam', 'Dikembalikan']);
            $table->foreignId('diproses_oleh')->nullable()->constrained('users', 'id');
            $table->dateTime('tgl_diproses')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->dateTime('tgl_pengembalian_aktual')->nullable();
            $table->timestamps();

            $table->index('mahasiswa_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('tgl_pengajuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borowings');
    }
};
