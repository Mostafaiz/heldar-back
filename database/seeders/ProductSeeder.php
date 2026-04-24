<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Pattern;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Guarantee;
use App\Models\Insurance;
use App\Models\Color;
use App\Models\File;
use App\Models\Attribute;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Collections / arrays used by seeder
        $categories = Category::pluck('id')->toArray();
        $guarantees = Guarantee::pluck('id')->toArray();
        $insurances = Insurance::pluck('id')->toArray();
        $files = File::where('folder_id', '=', '6')->get(); // Eloquent collection
        $attributes = Attribute::all();

        // colors that exist in your DB (IDs you listed)
        $colors = Color::whereIn('id', [3, 4, 5, 6, 7, 8, 9])->get();

        // a base list of Persian product names (digikala-style)
        $productNames = [
            'گوشی موبایل شیائومی Redmi Note 14 4G',
            'گوشی موبایل سامسونگ Galaxy A06',
            'گوشی موبایل شیائومی Redmi A5',
            'گوشی موبایل سامسونگ Galaxy A16 4G',
            'گوشی موبایل شیائومی Poco C75',
            'گوشی موبایل شیائومی Redmi 13',
            'گوشی موبایل آنر Honor X6b',
            'گوشی موبایل نوکیا Nokia G21',
            'گوشی موبایل Nothing Phone 1',
            'گوشی موبایل سامسونگ Galaxy A55',
            'گوشی موبایل اپل iPhone 15 Pro Max',
            'گوشی موبایل اپل iPhone 14 Plus',
            'گوشی موبایل سامسونگ Galaxy S24 Ultra',
            'گوشی موبایل شیائومی Redmi Note 13 Pro',
            'گوشی موبایل هوآوی nova 11i',
            'گوشی موبایل سامسونگ Galaxy M14 5G',
            'گوشی موبایل شیائومی Poco X6 Pro',
            'گوشی موبایل شیائومی Redmi Note 12S',
            'گوشی موبایل آنر Honor X9a',
            'گوشی موبایل سامسونگ Galaxy Z Flip5',
            'گوشی موبایل شیائومی Redmi Note 14',
            'گوشی موبایل سامسونگ Galaxy A35',
            'گوشی موبایل شیائومی Poco F6 Pro',
            'گوشی موبایل اپل iPhone 13',
            'گوشی موبایل هوآوی Mate 60 Pro',
            'گوشی موبایل آنر Magic 6 Pro',
            'گوشی موبایل Infinix Note 30',
            'گوشی موبایل Infinix Zero Ultra',
            'گوشی موبایل Tecno Camon 20 Pro',
            'گوشی موبایل Tecno Phantom X2',
        ];

        // Ensure we will create 200 unique-ish product names (append series)
        $totalProducts = 200;
        for ($i = 0; $i < $totalProducts; $i++) {
            $base = $productNames[$i % count($productNames)];
            $name = "{$base} | سری " . ($i + 1); // unique by index
            $slugBase = Str::slug($name);
            $slug = $slugBase;

            // ensure slug uniqueness
            if (Product::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . rand(100, 999);
            }

            $product = Product::create([
                'name' => $name,
                'english_name' => null,
                'slug' => $slug,
                'brand' => explode(' ', $base)[0] ?? $base,
                'description' => 'توضیحات محصول ' . $name,
                'status' => 1,
            ]);

            // sync categories if available
            if (count($categories) > 0) {
                $take = min(2, count($categories));
                $product->categories()->sync(collect($categories)->shuffle()->take($take)->toArray());
            }

            // number of patterns: 1 - 3
            $patternCount = rand(1, 3);
            for ($p = 0; $p < $patternCount; $p++) {
                // choose 1-2 colors (max 2)
                $numColors = min(2, max(1, rand(1, 2)));
                $selectedColors = $colors->shuffle()->take($numColors);
                $patternName = $selectedColors->pluck('name')->implode(' ');

                $pattern = Pattern::create([
                    'product_id' => $product->id,
                    'name' => $patternName ?: 'بدون طرح',
                ]);

                // sync color ids if any
                if ($selectedColors->isNotEmpty()) {
                    $pattern->colors()->sync($selectedColors->pluck('id')->toArray());
                }

                // attach 1-3 images from files (folder_id=3), safe if none exist
                if ($files->count() > 0) {
                    $imageCount = rand(1, min(3, $files->count()));
                    $selectedFiles = $files->shuffle()->take($imageCount)->pluck('id')->toArray();
                    // If your relationship is files() on Pattern -> use that; adjust if different
                    $pattern->files()->sync($selectedFiles);
                }

                // create 1-2 variants for this pattern
                $variantCount = rand(1, 2);
                for ($v = 0; $v < $variantCount; $v++) {
                    // price ends with 000 -> choose base in thousands to get realistic numbers
                    // here: produce 5,000,000 .. 20,000,000 by picking 5000..20000 * 1000
                    $price = rand(5000, 20000) * 1000;
                    // discount: 0 or some multiple of 1000 but less than price
                    $discountOptions = [0, 1000, 2000, 5000, 10000];
                    $discount = $discountOptions[array_rand($discountOptions)];
                    // if discount >= price (unlikely with these ranges) set 0
                    if ($discount >= $price) $discount = 0;

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'pattern_id' => $pattern->id,
                        'size_id' => null,
                        'price' => $price,
                        'discount' => $discount,
                        'quantity' => rand(0, 50),
                        'sku' => 'SKU-' . strtoupper(Str::random(8)),
                    ]);
                }

                // sync guarantees & insurances if exist
                if (count($guarantees) > 0) {
                    $pattern->guarantees()->sync(collect($guarantees)->shuffle()->take(min(1, count($guarantees)))->toArray());
                }
                if (count($insurances) > 0) {
                    $pattern->insurances()->sync(collect($insurances)->shuffle()->take(min(1, count($insurances)))->toArray());
                }
            }

            // attach attributes (if any) as pivot value => ['value' => '...']
            if ($attributes->isNotEmpty()) {
                $attrData = [];
                foreach ($attributes as $attr) {
                    $attrData[$attr->id] = ['value' => 'مقدار ' . Str::random(3)];
                }
                $product->attributes()->sync($attrData);
            }
        }
    }
}
