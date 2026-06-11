<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE tools SET status_alat = 'MAINTENANCE' WHERE status_alat NOT IN ('TERSEDIA', 'MAINTENANCE')");
        DB::statement("ALTER TABLE tools MODIFY COLUMN status_alat ENUM('TERSEDIA','MAINTENANCE') NOT NULL DEFAULT 'TERSEDIA'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tools MODIFY COLUMN status_alat ENUM('TERSEDIA','MAINTENANCE') NOT NULL DEFAULT 'TERSEDIA'");
    }
};
