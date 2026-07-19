<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class N8NWebhookService
{
    /**
     * Send webhook to n8n
     * 
     * @param string $event
     * @param \App\Models\User|array|null $user
     * @param array $payload
     */
    public static function send(string $event, $user = null, array $payload = []): void
    {
        $webhookUrl = config('services.n8n.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $userInfo = [
            'jenis_notifikasi' => 'Email',
            'email' => null,
            'no_whatsapp' => null,
            'nama' => null,
        ];

        if ($user instanceof \App\Models\User) {
            $phone = $user->no_whatsapp;
            // Ubah awalan 0 menjadi 62 untuk WhatsApp
            if ($phone && str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }
            
            $jenisNotif = $user->jenis_notifikasi ?? 'Email';
            // Normalize to Title Case (e.g. EMAIL -> Email, WHATSAPP -> Whatsapp)
            $jenisNotif = ucfirst(strtolower($jenisNotif));

            $userInfo = [
                'jenis_notifikasi' => $jenisNotif,
                'email' => $user->email,
                'no_whatsapp' => $phone,
                'nama' => $user->nama_lengkap,
            ];
        } elseif (is_array($user)) {
            $userInfo = array_merge($userInfo, $user);
        }

        // Pastikan kompatibilitas dengan node N8N yang baru (selalu menggunakan formatted_message dan email_message)
        if (!isset($payload['formatted_message']) && isset($payload['message'])) {
            $payload['formatted_message'] = $payload['message'];
        }
        if (!isset($payload['email_message']) && isset($payload['message'])) {
            $payload['email_message'] = $payload['message'];
        }

        try {
            Http::timeout(5)->post($webhookUrl, [
                'event' => $event,
                ...$userInfo,
                'data' => $payload,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('[N8N Webhook] Failed: ' . $e->getMessage());
        }
    }

    public static function sendBorrowingNotification(\App\Models\Borowing $borrowing, string $event, string $keterangan = ''): void
    {
        $mahasiswa = $borrowing->mahasiswa;
        $nama = $mahasiswa ? $mahasiswa->nama_lengkap : 'Mahasiswa/Dosen';
        
        $itemsWA = [];
        $itemsEmail = [];
        $isReturned = strtolower($borrowing->status ?? '') === 'dikembalikan';

        if ($borrowing->borrowingItems) {
            foreach($borrowing->borrowingItems as $item) {
                if ($item->tool) {
                    $qty = $item->jumlah_unit ?? 0;
                    $toolName = $item->tool->nama_alat;
                    
                    $line = "- " . $toolName . " (" . $qty . " unit)";
                    if ($isReturned && !empty($item->kondisi_saat_kembali)) {
                        $line .= " [Kondisi: " . $item->kondisi_saat_kembali . "]";
                    }
                    
                    $itemsWA[] = $line;
                    $itemsEmail[] = $line;
                }
            }
        }
        $namaAlatWA = empty($itemsWA) ? '-' : "\n" . implode("\n", $itemsWA);
        $namaAlatEmail = empty($itemsEmail) ? '-' : "\n" . implode("\n", $itemsEmail);

        $waktuPinjam = now()->format('d M Y H:i'); // Real-time timestamp
        $estimasiKembali = $borrowing->tgl_rencana_kembali ? $borrowing->tgl_rencana_kembali->format('d M Y') : '-';
        
        $message = "Halo, *$nama*!\n\n";
        $message .= "Peminjaman alat laboratorium Anda telah diproses. Berikut adalah rincian transaksi peminjaman Anda untuk referensi administrasi:\n\n";
        $message .= "*ID Transaksi:* #" . $borrowing->id_borrowing . "\n";
        $message .= "*Status Peminjaman:* " . strtoupper($borrowing->status ?? 'MENUNGGU') . "\n";
        $message .= "*Daftar Alat:* " . $namaAlatWA . "\n\n";
        $message .= "*Waktu Transaksi:* " . $waktuPinjam . "\n";
        $message .= "*Estimasi Kembali:* " . $estimasiKembali . "\n";
        
        if ($keterangan) {
            $message .= "\n*Keterangan Tambahan:*\n" . $keterangan . "\n";
        }
        
        if (!empty($borrowing->catatan_admin)) {
            $message .= "\n*Catatan Admin:*\n" . $borrowing->catatan_admin . "\n";
        }
        
        $message .= "\nTerima kasih telah menggunakan layanan Laboratorium kami!";

        // Versi bersih untuk Email (tanpa emoji dan tanpa tanda bintang)
        $emailMessage = "Halo, $nama!\n\n";
        $emailMessage .= "Peminjaman alat laboratorium Anda telah diproses. Berikut adalah rincian transaksi peminjaman Anda untuk referensi administrasi:\n\n";
        $emailMessage .= "ID Transaksi: #" . $borrowing->id_borrowing . "\n";
        $emailMessage .= "Status Peminjaman: " . strtoupper($borrowing->status ?? 'MENUNGGU') . "\n";
        $emailMessage .= "Daftar Alat: " . $namaAlatEmail . "\n\n";
        $emailMessage .= "Waktu Transaksi: " . $waktuPinjam . "\n";
        $emailMessage .= "Estimasi Kembali: " . $estimasiKembali . "\n";
        
        if ($keterangan) {
            $emailMessage .= "\nKeterangan Tambahan:\n" . $keterangan . "\n";
        }
        
        if (!empty($borrowing->catatan_admin)) {
            $emailMessage .= "\nCatatan Admin:\n" . $borrowing->catatan_admin . "\n";
        }
        
        $emailMessage .= "\nTerima kasih telah menggunakan layanan Laboratorium kami!";

        // Tentukan pesan mana yang akan dikirim berdasarkan preferensi user
        $jenisNotifikasiUser = ucfirst(strtolower($mahasiswa->jenis_notifikasi ?? 'Email'));
        $finalMessage = ($jenisNotifikasiUser === 'Whatsapp') ? $message : $emailMessage;

        // Trik untuk mengakali N8N karena user malas menambah rute 'DIPINJAM'
        // Kita ubah event menjadi 'approved' dan status di payload menjadi 'DISETUJUI'
        // Tetapi teks pesan ($finalMessage) tetap menampilkan 'DIPINJAM'
        $payloadEvent = $event;
        $payloadStatus = $borrowing->status;
        if ($event === 'processed') {
            $payloadEvent = 'approved';
            $payloadStatus = 'DISETUJUI';
        }

        self::send($payloadEvent, $mahasiswa, [
            'borrowingId' => $borrowing->id_borrowing,
            'status' => $payloadStatus,
            'message' => $finalMessage,
            'formatted_message' => $finalMessage,
            'keterangan' => $keterangan,
        ]);
    }
}

