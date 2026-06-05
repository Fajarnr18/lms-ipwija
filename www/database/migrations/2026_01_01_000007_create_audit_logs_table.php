<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->dateTime('time_stamp');
            $table->string('dilakukan_oleh');
            $table->enum('role_pelaku', ['Admin', 'Mahasiswa', 'System']);
            $table->string('modul', 50);
            $table->string('aksi', 100);
            $table->string('id_record', 50);
            $table->text('data_sebelum')->nullable();
            $table->text('data_sesudah')->nullable();
            $table->string('ip_address', 45);

            $table->index('time_stamp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
