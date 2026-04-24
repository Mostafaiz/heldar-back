<?php

namespace Database\Seeders;

use App\Models\Slide;
use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Slide::create([
                'link' => null,
                'status' => false,
            ]);
        }
    }
}
