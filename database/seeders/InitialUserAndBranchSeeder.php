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
            ['name' => 'Kantor Pusat Soba'],
            [
                'code' => 'PSTSOBA',
                'address' => 'Jl. Ir. Soekarno No.32',
                'sub_district' => 'Madegondo',
                'district' => 'Grogol',
                'city' => 'Sukoharjo',
            ]
        );

        $retailBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Cabang Retail'],
            [
                'code' => 'KCRTL',
                'address' => 'Jl. Teuku Umar No. 1',
                'sub_district' => 'Keprabon',
                'district' => 'Banjarsari',
                'city' => 'Surakarta',
            ]
        );

        $jagalanBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Cabang Jagalan'],
            [
                'code' => 'KCJGL',
                'address' => 'Jl. Kali Merbau',
                'sub_district' => 'Jagalan',
                'district' => 'Jebres',
                'city' => 'Surakarta',
            ]
        );

        $semarangBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Cabang Semarang'],
            [
                'code' => 'KCSMG',
                'address' => 'Jl. Simongan Raya No.68A',
                'sub_district' => 'Ngemplak Simongan',
                'district' => 'Semarang Barat',
                'city' => 'Semarang',
            ]
        );

        $yogyakartaBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Cabang Yogyakarta'],
            [
                'code' => 'KCJOGJA',
                'address' => 'Jl. Perintis Kemerdekaan No.106',
                'sub_district' => 'Pandeyan',
                'district' => 'Umbulharjo',
                'city' => 'Yogyakarta',
            ]
        );

        $surabayaBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Cabang Surabaya'],
            [
                'code' => 'KCSBY',
                'address' => 'Jl. Dr. Ir. H. Soekarno',
                'sub_district' => 'Gn. Anyar',
                'district' => 'Gn. Anyar',
                'city' => 'Surabaya',
            ]
        );

        $jakartaBranchOffice = BranchOffice::firstOrCreate(
            ['name' => 'Kantor Cabang Jakarta'],
            [
                'code' => 'KCJKT',
                'address' => 'Jl. Gn. Sahari No.5 blok E',
                'sub_district' => 'Ancol',
                'district' => 'Pademangan',
                'city' => 'Jakarta',
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
                'name' => 'RMA Admin',
                'password' => Hash::make('password'),
                'role_id' => $rmaAdminRole->id ?? null,
                'branch_office_id' => $mainBranchOffice->id,
                'phone_number' => '081234567896'
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin_retail@techma.id'],
            [
                'name' => 'Admin Cabang Retail',
                'password' => Hash::make('jemdiaretail631'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $retailBranchOffice->id,
                'phone_number' => '081234567892',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin_jagalan@techma.id'],
            [
                'name' => 'Admin Cabang Jagalan',
                'password' => Hash::make('techmajagalan321'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $jagalanBranchOffice->id,
                'phone_number' => '081234567893',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin_semarang@techma.id'],
            [
                'name' => 'Admin Cabang Semarang',
                'password' => Hash::make('techmasmg123'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $semarangBranchOffice->id,
                'phone_number' => '081234525893',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin_jogja@techma.id'],
            [
                'name' => 'Admin Cabang Yogyakarta',
                'password' => Hash::make('jmediajogja*123'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $yogyakartaBranchOffice->id,
                'phone_number' => '081234525893',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin_sby@techma.id'],
            [
                'name' => 'Admin Cabang Surabaya',
                'password' => Hash::make('techmasby*111'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $surabayaBranchOffice->id,
                'phone_number' => '081285625893',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin_jkt@techma.id'],
            [
                'name' => 'Admin Cabang Jakarta',
                'password' => Hash::make('jmediajkt*321'),
                'role_id' => $adminRole->id ?? null,
                'branch_office_id' => $jakartaBranchOffice->id,
                'phone_number' => '081234529733',
            ]
        );
    }
}
