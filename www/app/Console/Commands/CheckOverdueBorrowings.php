<?php

namespace App\Console\Commands;

use App\Models\Borowing;
use App\Services\N8NWebhookService;
use Illuminate\Console\Command;

class CheckOverdueBorrowings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-overdue-borrowings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue borrowings and send notifications via n8n';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueBorrowings = Borowing::with('mahasiswa')
            ->where('status', 'DIPINJAM')
            ->where('tgl_rencana_kembali', '<', now())
            ->get();

        $count = 0;
        foreach ($overdueBorrowings as $borrowing) {
            $borrowing->update(['status' => 'TERLAMBAT']);
            N8NWebhookService::sendBorrowingNotification($borrowing, 'overdue', 'Tenggat waktu pengembalian alat telah terlewat. Mohon segera dikembalikan.');
            $count++;
        }

        $this->info("Sent {$count} overdue notifications.");
    }
}

