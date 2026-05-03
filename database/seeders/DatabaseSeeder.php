<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            CitySeeder::class,
            RolePermissionSeeder::class,
            SpecialtySeeder::class,
            MedicineCategorySeeder::class,
            UserSeeder::class,
        ]);
    }
}
