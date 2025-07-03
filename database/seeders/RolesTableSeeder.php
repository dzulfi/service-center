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
        Role::create(['name' => 'developer', 'description' => 'Akses penuh ke semua fitur dan konfigurasi sistem.']);
        Role::create(['name' => 'superadmin', 'description' => 'Melihat semua aktivitas, mengelola kantor cabang, melihat aktivitas user.']);
        Role::create(['name' => 'admin', 'description' => 'Mengelola pelanggan dan daftar barang servis.']);
        Role::create(['name' => 'rma', 'description' => 'Mengelola dan mengerjakan proses servis.']);
    }
}
