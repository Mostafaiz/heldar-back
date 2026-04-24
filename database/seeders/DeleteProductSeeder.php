<?php

namespace Database\Seeders;

use App\Http\Dto\Request\Product\DeleteProduct;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Seeder;

class DeleteProductSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all()->pluck('id');

        foreach ($products as $key => $value) {
            app(ProductService::class)->delete(DeleteProduct::makeDto(['id' => $value]));
        }
    }
}
