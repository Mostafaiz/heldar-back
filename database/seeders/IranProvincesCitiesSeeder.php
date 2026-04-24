<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class IranProvincesCitiesSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(file_get_contents(public_path('/iran.json')), true);
        foreach ($data as $province) {
            $provinceModel = Province::create(['name' => $province['province']]);

            foreach ($province['cities'] as $city) {
                City::create([
                    'name' => $city,
                    'province_id' => $provinceModel->id
                ]);
            }
        }
    }
}
