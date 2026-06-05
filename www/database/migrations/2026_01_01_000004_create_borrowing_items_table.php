<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowing_items', function (Blueprint $table) {
            $table->id('id_borrowings_item');
            $table->foreignId('borrowing_id')->constrained('borowings', 'id_borrowing')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools', 'id_alat');
            $table->integer('jumlah_unit');
            $table->enum('kondisi_saat_kembali', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->text('catatan_pengembalian')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('borrowing_id');
            $table->index('tool_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_items');
    }
};
