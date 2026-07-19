<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PdfDataSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [
            ['kode' => 'BB400', 'nama' => 'BreadBoard 400 point', 'qty' => 10],
            ['kode' => 'JMP20', 'nama' => '40x kabel Jumper 20CM (20 M T M 20 F T F)', 'qty' => 10],
            ['kode' => 'SHC', 'nama' => 'Sensor Jarak (Ultra sonicHCSR 04)', 'qty' => 10],
            ['kode' => 'USBBA', 'nama' => 'Kabel USB tipe B to A', 'qty' => 10],
            ['kode' => 'HPIN', 'nama' => 'Header Pin uk 8, 2, 10', 'qty' => 10],
            ['kode' => 'LED5', 'nama' => 'LED 5MM (Putih, Biru, Kuning, Merah, Hijau)', 'qty' => 150],
            ['kode' => 'RES', 'nama' => 'Resistor', 'qty' => 30],
            ['kode' => 'PTM', 'nama' => 'Potensio Meter', 'qty' => 10],
            ['kode' => 'PASBUZZ', 'nama' => 'Passive Buzzer', 'qty' => 10],
            ['kode' => 'LEDRGB', 'nama' => 'LED RGB', 'qty' => 10],
            ['kode' => 'LDR', 'nama' => 'Sensor cahaya (PHoto Resistor/ LDR)', 'qty' => 10],
            ['kode' => 'DHT11', 'nama' => 'Sensor Suhu dan kelembapan (DHT 11)', 'qty' => 10],
            ['kode' => 'JMP3', 'nama' => '3 Kabel Jumper F T M 20 CM', 'qty' => 10],
            ['kode' => 'LCD1602', 'nama' => 'LCD 1602 (16x2)', 'qty' => 10],
            ['kode' => 'RLY', 'nama' => 'Modul Relay 1 Chanel', 'qty' => 10],
            ['kode' => 'UNO.PB', 'nama' => 'Arduino Uno R3 Tipe SMD/ DIP', 'qty' => 10],
            ['kode' => 'SG90', 'nama' => 'Motor servo (SG900)', 'qty' => 10],
            ['kode' => 'DVD', 'nama' => 'DVD tutorial', 'qty' => 10],
        ];

        $now = Carbon::now();

        foreach ($tools as $tool) {
            DB::table('tools')->insert([
                'kode_alat' => $tool['kode'],
                'nama_alat' => $tool['nama'],
                'kategori' => 'Elektronik',
                'deskripsi' => 'Komponen dari PDF Kit Arduino',
                'stok_total' => $tool['qty'],
                'stok_tersedia' => $tool['qty'],
                'status_alat' => 'TERSEDIA',
                'lokasi' => 'Lab Komputer',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
