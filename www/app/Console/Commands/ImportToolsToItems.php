<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\Tool;
use Illuminate\Console\Command;

class ImportToolsToItems extends Command
{
    protected $signature = 'import:tools-to-items';
    protected $description = 'Import data dari tabel tools ke items (inventaris)';

    public function handle(): int
    {
        $tools = Tool::all();
        $count = 0;
        $skipped = 0;

        foreach ($tools as $tool) {
            $exists = Item::where('kode_barang', $tool->kode_alat)->exists();

            if ($exists) {
                $this->warn("Lewati {$tool->kode_alat} — sudah ada di items");
                $skipped++;
                continue;
            }

            Item::create([
                'kode_barang' => $tool->kode_alat,
                'nama_barang' => $tool->nama_alat,
                'kategori' => $tool->kategori ?? 'Umum',
                'deskripsi' => $tool->deskripsi ?? '',
                'stok' => $tool->stok_total,
                'satuan' => 'Unit',
                'kondisi' => match ($tool->status_alat) {
                    'TERSEDIA' => 'Baik',
                    'MAINTENANCE' => 'Rusak Ringan',
                    'RUSAK' => 'Rusak Berat',
                    default => 'Baik',
                },
                'lokasi' => $tool->lokasi ?? 'Lab',
                'tgl_pendataan' => $tool->created_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
            ]);

            $this->info("Impor {$tool->kode_alat} — {$tool->nama_alat}");
            $count++;
        }

        $this->newLine();
        $this->info("Selesai! {$count} data diimpor, {$skipped} dilewati.");

        return Command::SUCCESS;
    }
}
