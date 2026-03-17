<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // ProjectTypeSeeder::class,
            // ProjectPropertyGroupSeeder::class,
            // ProjectCatalogueSeeder::class,
            // ProjectExampleSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
