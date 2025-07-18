<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'developer'], 
            ['description' => 'Akses penuh ke semua fitur dan konfigurasi sistem.']
        );
        Role::updateOrCreate(
            ['name' => 'superadmin'], 
            ['description' => 'Melihat semua aktivitas, mengelola kantor cabang, melihat aktivitas user.']
        );
        Role::updateOrCreate(
            ['name' => 'admin'], 
            ['description' => 'Mengelola pelanggan dan daftar barang servis.']
        );
        Role::updateOrCreate(
            ['name' => 'rma'], 
            ['description' => 'Mengelola dan mengerjakan proses servis.']
        );
        Role::updateOrCreate(
            ['name'=> 'rma_admin'], 
            ['description'=> 'Membantu RMA dalam administari, menerima service masuk dan kirim balik.']
        );
    }
}
