<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PermissionSeeder::class,
            MainManagerPermissionsSeeder::class,
            SliderSeeder::class,
            IranProvincesCitiesSeeder::class
        ]);
    }
}
