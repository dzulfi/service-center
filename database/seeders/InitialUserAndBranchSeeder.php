<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserAndBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developerRole = Role::where('name', 'developer')->first();
        $superadminRole = Role::where('name', 'superadmin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $rmaRole = Role::where('name', 'rma')->first();
        $rmaAdminRole = Role::where('name', 'rma_admin')->first();

        // buat kantor cabang default jika belum ada 
        $mainBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Pusat'],
            [
                'code' => 'PST',
                'address' => 'Jl. Merdeka No. 1',
                'sub_district' => 'Menteng',
                'district' => 'Menteng',
                'city' => 'Jakarta Pusat',
            ]
        );

        // buat user developer
        User::firstOrCreate(
            ['email' => 'developer@techma.id'],
            [
                'name' => 'Developer User', 
                'password' => Hash::make('password'),
                'role_id' => $developerRole->id ?? null,
                'branch_office_id' => $mainBranchOffice->id,
                'phone_number' => '081234567890'
            ]
        );

        // buat user superadmin 
        User::firstOrCreate(
            ['email' => 'superadmin@techma.id'],
            [
                'name' => 'Superadmin User',
                'password' => Hash::make('password'),
                'role_id' => $superadminRole->id ?? null,
                'branch_office_id' => $mainBranchOffice->id,
                'phone_number' => '081234567891',
            ]
        );

        // buat user admin
        User::firstOrCreate(
            ['email' => 'admin@techma.id'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $mainBranchOffice->id,
                'phone_number' => '081234567892',
            ]
        );

        User::firstOrCreate(
            ['email' => 'rma@techma.id'],
            [
                'name' => 'RMA Teknisi',
                'password' => Hash::make('password'),
                'role_id' => $rmaRole->id ?? null,
                'branch_office_id' => $mainBranchOffice->id,
                'phone_number' => '081234567895'
            ]
        );

        User::firstOrCreate(
            ['email' => 'rmaadmin@techma.id'],
            [
                'name' => 'Admin RMA',
                'password' => Hash::make('password'),
                'role_id' => $rmaAdminRole->id ?? null,
                'branch_office_id' => $mainBranchOffice->id,
                'phone_number' => '081234567896'
            ]
        );
    }
}
