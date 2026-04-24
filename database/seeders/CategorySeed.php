<?php

namespace Database\Seeders;

use App\Http\Dto\Category\CreateCategoryDto;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = app('CategoryService');
        $mobile = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'موبایل',
            ])
        );
        $samsung = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'سامسونگ',

            ], $mobile)
        );
        $serieA = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'سری A',
            ], $samsung)
        );
        $serieJ = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'سری J',
            ], $samsung)
        );
        $shiaomi = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'شیائومی',
            ], $mobile)
        );
        $serieNote = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'سری Note',
            ], $shiaomi)
        );
        $cloth = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'لباس',
            ])
        );
        $tshirt = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'تی شرت',
            ], $cloth)
        );
        $shirt = $service->createCategory(
            CreateCategoryDto::makeDto([
                'name' => 'پیراهن',
            ], $cloth)
        );
    }
}
