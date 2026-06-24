<?php

use App\Models\Tool;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Tool::where('stok_tersedia', '<=', 0)
            ->where('status_alat', 'TERSEDIA')
            ->update(['status_alat' => 'MAINTENANCE']);

        Tool::where('stok_tersedia', '>', 0)
            ->where('status_alat', 'MAINTENANCE')
            ->update(['status_alat' => 'TERSEDIA']);
    }

    public function down(): void
    {
        // no rollback
    }
};
