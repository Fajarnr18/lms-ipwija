<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PdfDataSeeder3 extends Seeder
{
    public function run(): void
    {
        $data = [
            // LABORATORIUM KOMPUTER 64
            ['kode' => 'PC-I5-1', 'nama' => 'Komputer core i5', 'qty' => 37, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'PC-I3-1', 'nama' => 'Komputer core i3', 'qty' => 17, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'PC-I7-1', 'nama' => 'Komputer core i7', 'qty' => 15, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'MO-21-1', 'nama' => 'Monitor 21 inch', 'qty' => 15, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'MO-14-1', 'nama' => 'Monitor 14 inch', 'qty' => 17, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'MO-19-1', 'nama' => 'Monitor 19 inch', 'qty' => 9, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'HS-16-1', 'nama' => 'Hub Switch Tplink 16 port', 'qty' => 2, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'HS-6-1', 'nama' => 'Hub Switch Tplink 6 port', 'qty' => 2, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'KY-LOGI-1', 'nama' => 'Keyboard Logitech', 'qty' => 25, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'MOU-LOGI-1', 'nama' => 'Mouse Logitech', 'qty' => 25, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'KY-LENV-1', 'nama' => 'Keyboard Lenovo', 'qty' => 22, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'MOU-LENV-1', 'nama' => 'Mouse Lenovo', 'qty' => 22, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'M-TRIP-1', 'nama' => 'Meja Custom Triple', 'qty' => 16, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'K-FUT-1', 'nama' => 'Kursi Futura', 'qty' => 46, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'V-SONIC-1', 'nama' => 'Viewer View Sonic', 'qty' => 1, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'LV-1', 'nama' => 'Layar Viewer', 'qty' => 1, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'BR-1', 'nama' => 'Braket', 'qty' => 1, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],
            ['kode' => 'AC-1', 'nama' => 'AC MERK', 'qty' => 2, 'lokasi' => 'LABORATORIUM KOMPUTER 64'],

            // LABORATORIUM MULTIMEDIA 78
            ['kode' => 'PC-I5-2', 'nama' => 'Komputer core i5', 'qty' => 40, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'MO-16-2', 'nama' => 'Monitor 16 inch', 'qty' => 39, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'MO-19-2', 'nama' => 'Monitor 19 inch', 'qty' => 1, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'HS-DL-2', 'nama' => 'Hub Switch D-Link1024D', 'qty' => 2, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'MOU-LOGI-2', 'nama' => 'Mouse Logitech', 'qty' => 39, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'KY-LOGI-2', 'nama' => 'Keyboard Logitech', 'qty' => 39, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'BR-2', 'nama' => 'Braket', 'qty' => 1, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'V-EPS-2', 'nama' => 'Viewer Epson', 'qty' => 1, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'LV-2', 'nama' => 'Layar Viewer', 'qty' => 1, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'WB-2', 'nama' => 'White Board Custom', 'qty' => 2, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'K-FUT-2', 'nama' => 'Kursi Futura', 'qty' => 30, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'K-FUTL-2', 'nama' => 'Kursi Futura Lipat', 'qty' => 9, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'M-SING-2', 'nama' => 'Meja Custom Singel', 'qty' => 8, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'M-DOUB-2', 'nama' => 'Meja Custom Double', 'qty' => 17, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'M-2', 'nama' => 'Meja', 'qty' => 2, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],
            ['kode' => 'AC-2', 'nama' => 'AC MERK', 'qty' => 5, 'lokasi' => 'LABORATORIUM MULTIMEDIA 78'],

            // WORKSHOP 42
            ['kode' => 'PC-I3-3', 'nama' => 'Komputer core i3', 'qty' => 22, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'PC-I5-3', 'nama' => 'Komputer core i5', 'qty' => 3, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'MO-14-3', 'nama' => 'Monitor 14 inch', 'qty' => 22, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'MO-19-3', 'nama' => 'Monitor 19 inch', 'qty' => 3, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'V-SONY-3', 'nama' => 'Viewer TV SONY', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'ROUT-MIC-3', 'nama' => 'Router Mikrotik 16', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'HS-TP24-3', 'nama' => 'Hub Switch TPlink 24', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'POE-TP24-3', 'nama' => 'POE Adapt TPlink 24', 'qty' => 2, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'BR-3', 'nama' => 'Braket', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'M-3', 'nama' => 'Meja', 'qty' => 2, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'M-CUST-3', 'nama' => 'Meja Custom', 'qty' => 3, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'K-FUT-3', 'nama' => 'Kursi Futura', 'qty' => 31, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'L-LOCK-3', 'nama' => 'Lemari Locker 6 Pintu', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'L-KACA-3', 'nama' => 'Lemari Kaca/Kayu', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'R-RAK-3', 'nama' => 'Rak 4 Tingkat', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'AC-3', 'nama' => 'AC MERK', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],

            // Peralatan WORKSHOP 42
            ['kode' => 'IP-ROB-1', 'nama' => 'PK Robotik Arduino Sensor Jarak', 'qty' => 5, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'IP-ROB-2', 'nama' => 'PK Robotik Arduino Sensor Suhu', 'qty' => 5, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'IP-JAR-1', 'nama' => 'PKI Jaringan Kabel CAT 5', 'qty' => 305, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'IP-JAR-2', 'nama' => 'PKI Jaringan Kabel CAT 6', 'qty' => 610, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'IP-JAR-3', 'nama' => 'PK Jaringan Range EXT TPLINK N300', 'qty' => 1, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'IP-JAR-4', 'nama' => 'PK Jaringan AP Tenda', 'qty' => 2, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'IP-JAR-5', 'nama' => 'PKI Jaringan Konektor RJ 45', 'qty' => 250, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'TP-JAR-1', 'nama' => 'PKI Jaringan Tang Kriping', 'qty' => 10, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'TP-JAR-2', 'nama' => 'PKI Jaringan LAN Tester', 'qty' => 10, 'lokasi' => 'WORKSHOP 42'],
            ['kode' => 'TP-TOOL-1', 'nama' => 'Tools Praktek Obeng Set', 'qty' => 20, 'lokasi' => 'WORKSHOP 42'],
        ];

        $now = Carbon::now();

        foreach ($data as $item) {
            DB::table('items')->insert([
                'kode_barang' => $item['kode'],
                'nama_barang' => $item['nama'],
                'kategori' => 'Aset',
                'deskripsi' => 'Data Inventaris dari PDF',
                'stok' => $item['qty'],
                'satuan' => 'Unit',
                'kondisi' => 'Baik',
                'lokasi' => $item['lokasi'],
                'tgl_pendataan' => '2026-06-01',
                'created_at' => $now,
            ]);
        }
    }
}
