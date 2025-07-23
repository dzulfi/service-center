<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sparepart::firstOrCreate([
            'code' => 'SP-LPT-BAT001',
            'name' => 'Baterai Laptop 11.4V 42Wh',
            'description' => 'Baterai pengganti untuk laptop umum, cocok untuk model seri tertentu.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-SMT-LCD002',
            'name' => 'Layar AMOLED Smartphone',
            'description' => 'Modul layar AMOLED lengkap dengan touchscreen untuk penggantian layar smartphone.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-TV-REM003',
            'name' => 'Remote Control Universal TV LED',
            'description' => 'Remote control kompatibel untuk berbagai merek dan model TV LED.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-AC-FIL004',
            'name' => 'Filter Udara AC Split',
            'description' => 'Filter udara pengganti untuk unit AC split, ukuran standar.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-KUL-SEAL005',
            'name' => 'Seal Karet Pintu Kulkas',
            'description' => 'Karet seal untuk pintu kulkas, menjaga kerapatan dan efisiensi pendinginan.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-CPU-FAN006',
            'name' => 'Kipas Pendingin CPU',
            'description' => 'Kipas dan heatsink untuk pendinginan prosesor komputer.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-PRT-CAR007',
            'name' => 'Cartridge Tinta Printer Hitam',
            'description' => 'Cartridge tinta warna hitam untuk printer inkjet model tertentu.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-WMC-PUM008',
            'name' => 'Motor Pompa Air Mesin Cuci',
            'description' => 'Motor pompa untuk pembuangan air pada mesin cuci.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-AUD-DRV009',
            'name' => 'Driver Speaker 6.5 Inch',
            'description' => 'Unit driver speaker pengganti berukuran 6.5 inci untuk speaker audio.',
        ]);

        Sparepart::firstOrCreate([
            'code' => 'SP-USB-PORT010',
            'name' => 'Port USB Type-C PCB',
            'description' => 'Modul port USB Type-C lengkap dengan PCB untuk perbaikan.',
        ]);
    }
}
