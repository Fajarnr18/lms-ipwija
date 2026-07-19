<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE borowings MODIFY COLUMN status ENUM('MENUNGGU', 'DISETUJUI', 'DITOLAK', 'DIPINJAM', 'DIKEMBALIKAN', 'TERLAMBAT') DEFAULT 'MENUNGGU'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE borowings MODIFY COLUMN status ENUM('MENUNGGU', 'DISETUJUI', 'DITOLAK', 'DIPINJAM', 'DIKEMBALIKAN') DEFAULT 'MENUNGGU'");
    }
};
