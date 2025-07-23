<?php

namespace Database\Seeders;

use App\Models\Merk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merk::firstOrCreate([
            'merk_name' => 'Techma',
        ]);
        
        Merk::firstOrCreate([
            'merk_name' => 'Hikvision',
        ]);
        
        Merk::firstOrCreate([
            'merk_name' => 'Dahua',
        ]);
    }
}
