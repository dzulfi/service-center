<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            InitialUserAndBranchSeeder::class,
            // ItemTypeSeeder::class,
            // MerkSeeder::class,
            // SparepartSeeder::class,
            // CustomerSeeder::class,
        ]);
    }
}
