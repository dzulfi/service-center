<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tipe Produk Tecma
        ItemType::firstOrCreate([
            'type_name' => 'Tecma IP Camera Bullet',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Tecma NVR PoE',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Tecma Full Color IP Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Tecma PA IP System',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Tecma SIP Phone',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Tecma PoE Switch',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Tecma Thermal Camera',
        ]);

        // Tipe Produk Dahua
        ItemType::firstOrCreate([
            'type_name' => 'Dahua WizMind IP Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Dahua WizSense NVR',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Dahua HDCVI Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Dahua Full-color HDCVI',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Dahua XVR',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Dahua Access Control Terminal',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Dahua Video Intercom',
        ]);

        // Tipe Produk Hikvision
        ItemType::firstOrCreate([
            'type_name' => 'Hikvision ColorVu Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Hikvision AcuSense NVR',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Hikvision Turbo HD Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Hikvision DeepinView IP Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Hikvision PTZ Camera',
        ]);
        ItemType::firstOrCreate([
            'type_name' => 'Hikvision DVR',
        ]);
    }
}
