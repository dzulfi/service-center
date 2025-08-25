<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::firstOrCreate([
            'code' => 'B001',
            'name' => 'Aditya Pratama',
            'phone_number' => '081223344556',
            'company' => 'Pratama Karya',
            'address' => 'Jl. Anggrek No. 12',
            'kelurahan'=> 'Gandekan',
            'kecamatan'=> 'Jebres',
            'kota'=> 'Surakarta',
        ]);

        Customer::firstOrCreate([
            'code' => 'B002',
            'name' => 'Citra Kirana',
            'phone_number' => '087889900112',
            'company' => 'Kirana Berseri',
            'address' => 'Perumahan Bukit Indah Blok B7',
            'kelurahan'=> 'Laweyan',
            'kecamatan'=> 'Laweyan',
            'kota'=> 'Surakarta',
        ]);

        Customer::firstOrCreate([
            'code' => 'B003',
            'name' => 'Eko Prasetyo',
            'phone_number' => '085776655443',
            'company' => 'Eko Sejahtera',
            'address' => 'Jl. Manggis No. 35',
            'kelurahan'=> 'Semarang Barat',
            'kecamatan'=> 'Semarang Barat',
            'kota'=> 'Semarang',
        ]);

        Customer::firstOrCreate([
            'code' => 'B004',
            'name' => 'Grace Natalia',
            'phone_number' => '081332211009',
            'company' => 'Natalia Group',
            'address' => 'Ruko Permata Hijau Kav. 8',
            'kelurahan'=> 'Pedurungan Kidul',
            'kecamatan'=> 'Pedurungan',
            'kota'=> 'Semarang',
        ]);

        Customer::firstOrCreate([
            'code' => 'B005',
            'name' => 'Herman Syahputra',
            'phone_number' => '089900112233',
            'company' => 'Syahputra Logistic',
            'address' => 'Dusun Krajan RT 05 RW 01',
            'kelurahan'=> 'Karanganyar',
            'kecamatan'=> 'Kota Karanganyar',
            'kota'=> 'Karanganyar',
        ]);

        Customer::firstOrCreate([
            'code' => 'B006',
            'name' => 'Indra Wijaya',
            'phone_number' => '081122334400',
            'company' => 'Wijaya Sentosa',
            'address' => 'Jl. Veteran No. 42',
            'kelurahan'=> 'Jateng',
            'kecamatan'=> 'Jateng',
            'kota'=> 'Boyolali',
        ]);

        Customer::firstOrCreate([
            'code' => 'B007',
            'name' => 'Julia Permata',
            'phone_number' => '085677889900',
            'company' => 'Permata Tour',
            'address' => 'Perumahan Griya Asri Blok D1',
            'kelurahan'=> 'Magelang Utara',
            'kecamatan'=> 'Magelang Utara',
            'kota'=> 'Magelang',
        ]);

        Customer::firstOrCreate([
            'code' => 'B008',
            'name' => 'Kevin Sanjaya',
            'phone_number' => '087766554433',
            'company' => 'Sanjaya Motor',
            'address' => 'Jl. Gajahmada No. 50',
            'kelurahan'=> 'Kratonan',
            'kecamatan'=> 'Serengan',
            'kota'=> 'Surakarta',
        ]);

        Customer::firstOrCreate([
            'code' => 'B009',
            'name' => 'Lina Marlina',
            'phone_number' => '081544332211',
            'company' => 'Marlina Fashion',
            'address' => 'Gg. Kenangan No. 10',
            'kelurahan'=> 'Sragen Tengah',
            'kecamatan'=> 'Sragen',
            'kota'=> 'Sragen',
        ]);

        Customer::firstOrCreate([
            'code' => 'B010',
            'name' => 'Naufal Rizki',
            'phone_number' => '082211009988',
            'company' => 'Rizki Cipta',
            'address' => 'Dusun Kebon Agung RT 03 RW 04',
            'kelurahan'=> 'Kebonagung',
            'kecamatan'=> 'Kebonagung',
            'kota'=> 'Demak',
        ]);

        Customer::firstOrCreate([
            'code' => 'B011',
            'name' => 'Olivia Putri',
            'phone_number' => '089566778899',
            'company' => 'Putri Cosmetics',
            'address' => 'Jl. Merapi No. 5',
            'kelurahan'=> 'Kaliurang',
            'kecamatan'=> 'Prambanan',
            'kota'=> 'Klaten',
        ]);
    }
}
