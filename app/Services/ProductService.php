<?php

namespace App\Services;

use App\Enums\ProductLevelsEnum;
use App\Exceptions\Category\CategoryException;
use App\Exceptions\Product\ProductException;
use App\Http\Dto\Request\Category\GetCategory;
use App\Http\Dto\Request\Product\DeleteProduct as DeleteProductDto;
use App\Http\Dto\Request\Product\UpdateProduct as UpdateProductDto;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Http\Dto\Request\Product\CreateProduct as CreateProductDto;
use App\Http\Dto\Request\Product\GetProduct;
use App\Models\Category;
use App\Models\Color;
use App\Models\Guarantee;
use App\Models\Insurance;
use App\Models\Pattern;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Dto\Response\Customer\Product\ProductCard;
use App\Models\Demand;
use App\Models\File;

class ProductService
{
    public function create(CreateProductDto $dto)
    {
        DB::beginTransaction();

        try {
            if (Product::where('name', $dto->name)->exists())
                ProductException::NameAlreadyExists();

            if (Product::where('slug', $dto->slug)->exists())
                ProductException::SlugAlreadyExists();

            $product = Product::create([
                'name' => $dto->name,
                'english_name' => $dto->englishName,
                'slug' => $dto->slug,
                'brand' => $dto->brand,
                'description' => $dto->description,
                'level' => $dto->level,
                'status' => $dto->status ?? 1,
            ]);

            $product->categories()->sync($dto->categoryIds);

            foreach ($dto->patterns as $pattern) {
                $sizeIds = $pattern->sizes[0]->id !== null ? array_column($pattern->sizes ?? [], 'id') : [];
                if (count($sizeIds) !== Size::whereIn('id', $sizeIds)->count())
                    ProductException::InvalidSize();
                if (Guarantee::whereIn('id', $pattern->guaranteeIds)->count() !== count($pattern->guaranteeIds))
                    ProductException::InvalidGuaranteeIds();
                if (Insurance::whereIn('id', $pattern->insuranceIds)->count() !== count($pattern->insuranceIds))
                    ProductException::InvalidInsuranceIds();
                if (Color::whereIn('id', $pattern->colorIds)->count() !== count($pattern->colorIds))
                    ProductException::InvalidColorIds();

                $patternName = trim($pattern->name ?? '') !== '' ? $pattern->name : null;

                $newPattern = Pattern::create([
                    'name' => $patternName,
                    'product_id' => $product->id,
                ]);

                $newPattern->colors()->sync($pattern->colorIds);
                $newPattern->guarantees()->sync($pattern->guaranteeIds);
                $newPattern->insurances()->sync($pattern->insuranceIds);
                $newPattern->sizes()->sync($sizeIds);
                $newPattern->files()->sync($pattern->imageIds);

                if (!empty($sizeIds)) {
                    foreach ($pattern->sizes as $size) {
                        if ($dto->price < 0)
                            ProductException::InvalidPrice();

                        if ($size->sku !== null && $size->sku != '' && ProductVariant::where('sku', $size->sku)->exists())
                            ProductException::DuplicateSku();

                        ProductVariant::create([
                            'price' => $dto->price,
                            'discount' => $size->discount ?? 0,
                            'quantity' => $size->quantity,
                            'sku' => $size->sku,
                            'product_id' => $product->id,
                            'pattern_id' => $newPattern->id,
                            'size_id' => $size->id,
                        ]);
                    }
                } else {
                    if ($dto->price < 0)
                        ProductException::InvalidPrice();

                    ProductVariant::create([
                        'price' => $dto->price,
                        'discount' => $pattern->sizes[0]->discount ?? 0,
                        'quantity' => $pattern->sizes[0]->quantity ?? 0,
                        'sku' => $pattern->sizes[0]->sku,
                        'product_id' => $product->id,
                        'pattern_id' => $newPattern->id,
                        'size_id' => null,
                    ]);
                }
            }

            if (!empty($dto->attributes) && is_iterable($dto->attributes->items)) {
                $product->attribute_group_id = $dto->attributes->items[0]['attribute_group_id'] ?? null;
                $product->save();

                $attributeData = [];

                foreach ($dto->attributes->items as $item) {
                    if (empty($item['value']))
                        continue;

                    $attributeData[$item['id']] = [
                        'value' => $item['value'],
                    ];
                }

                if (!empty($attributeData)) {
                    $product->attributes()->sync($attributeData);
                }
            }

            DB::commit();

            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw new Exception("Failed to create product. Please try again later. Reason: " . $e->getMessage());
        }
    }

    public function update(UpdateProductDto $dto)
    {
        $smsService = new SmsProviderService();
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($dto->id);

            if (Product::where('name', $dto->name)->where('id', '!=', $dto->id)->exists())
                ProductException::NameAlreadyExists();

            if (Product::where('slug', $dto->slug)->where('id', '!=', $dto->id)->exists())
                ProductException::SlugAlreadyExists();

            $product->update([
                'name' => $dto->name,
                'english_name' => $dto->englishName,
                'slug' => $dto->slug,
                'brand' => $dto->brand,
                'description' => $dto->description,
                'level' => $dto->level,
                'status' => $dto->status ?? $product->status,
            ]);

            $product->categories()->sync($dto->categoryIds);

            $existingPatternIds = $product->patterns()->pluck('id')->toArray();

            $incomingExistingPatternIds = collect($dto->patterns)
                ->where('local', false)
                ->pluck('id')
                ->toArray();

            $patternsToDelete = array_diff($existingPatternIds, $incomingExistingPatternIds);

            if (!empty($patternsToDelete)) {
                ProductVariant::whereIn('pattern_id', $patternsToDelete)->delete();
                Pattern::whereIn('id', $patternsToDelete)->delete();
            }

            foreach ($dto->patterns as $pattern) {

                $sizeIds = $pattern->sizes[0]->id !== null
                    ? array_column($pattern->sizes ?? [], 'id')
                    : [];

                if (count($sizeIds) !== Size::whereIn('id', $sizeIds)->count())
                    ProductException::InvalidSize();

                if (Guarantee::whereIn('id', $pattern->guaranteeIds)->count() !== count($pattern->guaranteeIds))
                    ProductException::InvalidGuaranteeIds();

                if (Insurance::whereIn('id', $pattern->insuranceIds)->count() !== count($pattern->insuranceIds))
                    ProductException::InvalidInsuranceIds();

                if (Color::whereIn('id', $pattern->colorIds)->count() !== count($pattern->colorIds))
                    ProductException::InvalidColorIds();

                $patternName = trim($pattern->name ?? '') !== '' ? $pattern->name : null;

                if ($pattern->local === true) {

                    $newPattern = Pattern::create([
                        'name' => $patternName,
                        'product_id' => $product->id,
                    ]);
                } else {

                    $newPattern = Pattern::where('product_id', $product->id)
                        ->where('id', $pattern->id)
                        ->firstOrFail();

                    $newPattern->update([
                        'name' => $patternName,
                    ]);
                }

                $newPattern->colors()->sync($pattern->colorIds);
                $newPattern->guarantees()->sync($pattern->guaranteeIds);
                $newPattern->insurances()->sync($pattern->insuranceIds);
                $newPattern->sizes()->sync($sizeIds);
                $newPattern->files()->sync($pattern->imageIds);

                if ($dto->price < 0)
                    ProductException::InvalidPrice();

                if (!empty($sizeIds)) {

                    foreach ($pattern->sizes as $size) {


                        if (
                            !empty($size->sku) &&
                            ProductVariant::where('sku', $size->sku)
                            ->where('product_id', '!=', $product->id)
                            ->exists()
                        )
                            ProductException::DuplicateSku();

                        if ($pattern->local) {
                            ProductVariant::create([
                                'price' => $dto->price,
                                'discount' => $size->discount ?? 0,
                                'quantity' => $size->quantity,
                                'sku' => $size->sku,
                                'product_id' => $product->id,
                                'pattern_id' => $newPattern->id,
                                'size_id' => $size->id,
                            ]);
                            continue;
                        }

                        $variant = ProductVariant::where('pattern_id', $newPattern->id)->first();
                        $demands = Demand::where('product_variant_id', $variant->id)->with('user')->get();

                        if ($variant->quantity == 0 && $size->quantity > 0) {
                            foreach ($demands as $demand) {
                                $smsService->sendCustomer(
                                    $demand->user->username,
                                    $product->slug,
                                    $patternName,
                                    $size->quantity
                                );
                                $demand->delete();
                            }
                        }

                        $variant->update([
                            'price' => $dto->price,
                            'discount' => $size->discount ?? 0,
                            'quantity' => $size->quantity,
                            'sku' => $size->sku,
                        ]);
                    }
                } else {
                    $s = $pattern->sizes[0];

                    if ($pattern->local) {
                        ProductVariant::create([
                            'price' => $dto->price,
                            'discount' => $s->discount ?? 0,
                            'quantity' => $s->quantity,
                            'sku' => $s->sku,
                            'product_id' => $product->id,
                            'pattern_id' => $newPattern->id,
                            'size_id' => null,
                        ]);
                        continue;
                    }

                    $variant = ProductVariant::where('pattern_id', $newPattern->id)->first();
                    $demands = Demand::where('product_variant_id', $variant->id)->with('user')->get();

                    if ($variant->quantity == 0 && $s->quantity > 0 && $demands != null) {
                        foreach ($demands as $demand) {
                            $smsService->sendCustomer(
                                $demand->user->username,
                                $product->slug,
                                $patternName,
                                $s->quantity
                            );
                            $demand->delete();
                        }
                    }

                    $variant->update([
                        'price' => $dto->price,
                        'discount' => $s->discount ?? 0,
                        'quantity' => $s->quantity,
                        'sku' => $s->sku,
                    ]);
                }
            }

            if (!empty($dto->attributes) && is_iterable($dto->attributes->items)) {

                $product->attribute_group_id = $dto->attributes->items[0]['attribute_group_id'] ?? null;
                $product->save();

                $attributeData = [];

                foreach ($dto->attributes->items as $item) {
                    if (empty($item['value']))
                        continue;

                    $attributeData[$item['id']] = [
                        'value' => $item['value'],
                    ];
                }

                $product->attributes()->sync($attributeData);
            }

            DB::commit();
            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception(
                "Failed to update product. Reason: " . $e->getMessage()
            );
        }
    }


    // public function getAllProducts(int $page = 1)
    // {
    //     $paginator = ProductVariant::with([
    //         'product',
    //         'pattern.files',
    //         'pattern.colors',
    //         'size',
    //     ])
    //         ->latest()
    //         ->paginate(30, page: $page);

    //     if ($paginator->isEmpty()) {
    //         return null;
    //     }

    //     $data = $paginator
    //         ->groupBy(fn($variant) => $variant->product_id)
    //         ->map(function ($variants) {

    //             $variant = $variants->first();

    //             return (object) [
    //                 'productId' => $variant->product?->id,
    //                 'productName' => $variant->product?->name,
    //                 'image' => optional($variant->pattern?->firstImage())->path,
    //                 'patternName' => $variant->pattern?->name,
    //                 'colors' => $variant->pattern?->colors
    //                     ? $variant->pattern->colors->map(fn($c) => (object) [
    //                         'name' => $c->name,
    //                         'code' => $c->code,
    //                     ])->values()
    //                     : null,
    //                 'sizeName' => $variant->size?->name,
    //                 'price' => $variant->price,
    //                 'quantity' => $variant->quantity,
    //                 'sku' => $variant->sku,
    //                 'status' => $variant->product?->status,
    //                 'createdAt' => $variant->product?->created_at,
    //             ];
    //         })
    //         ->values();

    //     return (object) [
    //         'data' => $data,
    //         'meta' => (object) [
    //             'currentPage' => $paginator->currentPage(),
    //             'lastPage' => $paginator->lastPage(),
    //             'perPage' => $paginator->perPage(),
    //             'total' => $paginator->total(),
    //         ]
    //     ];
    // }


    public function getAllProducts(int $page = 1)
    {
        $paginator = Product::with([
            'variants.pattern.files',
            'variants.pattern.colors',
            'variants.size',
        ])
            ->latest()
            ->paginate(20, page: $page);

        if ($paginator->isEmpty()) {
            return null;
        }

        // IMPORTANT: use getCollection(), not map() on paginator
        $data = $paginator
            ->map(function ($product) {

                // choose the first variant as representative
                $variant = $product->variants->first();

                return (object) [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'image' => optional($variant?->pattern?->firstImage())->path,
                    'patternName' => $variant?->pattern?->name,
                    'colors' => $variant?->pattern?->colors
                        ? $variant->pattern->colors->map(fn($c) => (object) [
                            'name' => $c->name,
                            'code' => $c->code,
                        ])->values()
                        : null,
                    'sizeName' => $variant?->size?->name,
                    'price' => $variant?->price,
                    'discount' => $variant?->discount,
                    'quantity' => $variant?->quantity,
                    'sku' => $variant?->sku,
                    'status' => $product->status,
                    'level' => $product->level,
                    'createdAt' => $product->created_at,
                ];
            })
            ->values(); // ensures clean consecutive indexing

        return (object) [
            'data' => $data,
            'meta' => (object) [
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

    public function getProductById(int $id)
    {
        $product = Product::with([
            'categories',
            'attributes',
            'attributeGroup:id,name',
            'variants.pattern.files',
            'variants.pattern.colors',
            'variants.pattern.guarantees',
            'variants.pattern.insurances',
            'variants.size',
        ])->find($id);

        if (!$product) {
            return null;
        }

        $categories = [];
        if ($product->categories->isNotEmpty()) {
            $categories = $product->categories->select(['id', 'name', 'description_category', 'description_page', 'image']);
        }

        $patterns = $product->variants->groupBy('pattern_id')->map(function ($variants, $patternId) {
            $pattern = $variants->first()->pattern;

            return (object) [
                'id' => $pattern->id,
                'name' => $pattern->name,
                'colors' => $pattern->colors->map(fn($color) => (object) [
                    'id' => $color->id,
                    'name' => $color->name,
                    'code' => $color->code,
                ])->toArray(),
                'guarantees' => $pattern->guarantees->map(fn($g) => (object) [
                    'id' => $g->id,
                    'name' => $g->name,
                ])->toArray(),
                'insurances' => $pattern->insurances->map(fn($i) => (object) [
                    'id' => $i->id,
                    'name' => $i->name,
                    'provider' => $i->provider,
                    'price' => $i->price,
                ])->toArray(),
                'images' => $pattern->files->map(fn($f) => (object) [
                    'id' => $f->id,
                    'path' => $f->path,
                ])->toArray(),
                'sizes' => $variants->map(fn($variant) => (object) [
                    'id' => $variant->id,
                    'size' => $variant->size?->name,
                    'price' => $variant->price,
                    'discount' => $variant->discount,
                    'quantity' => $variant->quantity,
                    'sku' => $variant?->sku,
                ])->toArray(),
            ];
        })->values()->toArray();

        $attributes = $product->attributes->map(fn($item) => (object) [
            'id' => $item->id,
            'key' => $item->key,
            'value' => $item->pivot->value,
        ])->toArray();

        return (object) [
            'id' => $product->id,
            'name' => $product->name,
            'englishName' => $product->english_name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'description' => $product->description,
            'level' => $product->level,
            'status' => $product->status,
            'attributeGroup' => (object) [
                'id' => $product->attributeGroup->id ?? null,
                'name' => $product->attributeGroup->name ?? null,
            ],
            'categories' => $categories,
            'patterns' => $patterns,
            'attributes' => $attributes,
        ];
    }

    public function getSinglePageProduct(GetProduct $dto)
    {

        $product = Product::with([
            'categories',
            'attributes',
            'variants.pattern.files',
            'variants.pattern.colors',
            'variants.pattern.guarantees',
            'variants.pattern.insurances',
            'variants.size',
        ])->find($dto->id);

        if (!$product) {
            return null;
        }

        $userLevel = auth()->user()->level();

        if (
            $userLevel === ProductLevelsEnum::BORONZE->value && $product->level === ProductLevelsEnum::GOLD->value ||
            $userLevel === ProductLevelsEnum::BORONZE->value && $product->level === ProductLevelsEnum::SILVER->value ||
            $userLevel === ProductLevelsEnum::SILVER->value && $product->level === ProductLevelsEnum::GOLD->value
        )
            abort(403);

        $breadcrumbs = [];
        if ($product->categories->isNotEmpty()) {
            $category = $product->categories->sortByDesc('parent_id')->first();
            $breadcrumbs = $this->buildBreadcrumb($category);
        }

        $patterns = $product->variants->groupBy('pattern_id')->map(function ($variants, $patternId) {
            $pattern = $variants->first()->pattern;

            return (object) [
                'id' => $pattern->id,
                'name' => $pattern->name,
                'colors' => $pattern->colors->map(fn($color) => (object) [
                    'id' => $color->id,
                    'name' => $color->name,
                    'code' => $color->code,
                ])->toArray(),
                'guarantees' => $pattern->guarantees->map(fn($g) => (object) [
                    'id' => $g->id,
                    'name' => $g->name,
                ])->toArray(),
                'insurances' => $pattern->insurances->map(fn($i) => (object) [
                    'id' => $i->id,
                    'name' => $i->name,
                    'provider' => $i->provider,
                    'price' => $i->price,
                ])->toArray(),
                'images' => $pattern->files->map(fn($f) => (object) [
                    'id' => $f->id,
                    'path' => $f->path,
                ])->toArray(),
                'sizes' => $variants->map(fn($variant) => (object) [
                    'id' => $variant->size?->id,
                    'name' => $variant->size?->name,
                    'price' => $variant->price,
                    'discount' => $variant->discount,
                    'quantity' => $variant->quantity,
                    'sku' => $variant->sku,
                ])->toArray(),
            ];
        })->values()->toArray();

        $attributes = $product->attributes->map(fn($item) => (object) [
            'id' => $item->id,
            'key' => $item->key,
            'value' => $item->pivot->value,
        ])->toArray();

        return (object) [
            'id' => $product->id,
            'name' => $product->name,
            'englishName' => $product->english_name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'description' => $product->description,
            'status' => $product->status,
            'attribute_group_id' => $product->attribute_group_id,
            'breadcrumbs' => $breadcrumbs,
            'patterns' => $patterns,
            'attributes' => $attributes,
        ];
    }

    public function getSinglePageProductVariants(int $id)
    {
        $product = Product::with(['variants.pattern.files', 'attributes'])->find($id);
        if ($product === null)
            ProductException::ProductNotFound();
        $cartItems = app(CartService::class)->getActiveCart()->items()->get(['product_variant_id', 'quantity']);
        $images = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->pattern->files as $image) {
                $images[] = $image;
            }
        }
        return [
            $product,
            $images,
            $cartItems,
        ];
    }

    public function buildBreadcrumb(Category $category)
    {
        $breadcrumb = [];
        $current = $category;
        while ($current) {
            $breadcrumb[] = [
                'id' => $current->id,
                'name' => $current->name,
            ];
            $current = $current->parent;
        }
        return array_reverse($breadcrumb);
    }

    public function delete(DeleteProductDto $dto)
    {
        $product = Product::find($dto->id);

        if ($product === null) {
            ProductException::ProductNotFound();
        }

        return DB::transaction(function () use ($product) {
            $product->patterns->each(function ($pattern) {
                $pattern->files()->detach();
                $pattern->delete();
            });

            $product->demands()->delete();

            return $product->delete();
        });

        $product->delete();
    }

    public function getProductsBySearch(string $keyword, int $page = 1)
    {
        $keyword = trim($keyword);
        $rows = [];
        $perPage = 30;

        $products = Product::with([
            'variants.pattern.files',
            'variants.pattern.colors',
            'variants.size',
        ])
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('english_name', 'like', "%{$keyword}%")
            ->orWhereHas('variants', function ($query) use ($keyword) {
                $query->where('sku', 'like', "%{$keyword}%");
            })
            ->latest()
            ->get();

        if ($products->isEmpty()) {
            return null;
        }

        foreach ($products as $product) {
            foreach ($product->variants as $variant) {

                $rows[] = [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'image' => optional($variant->pattern?->firstImage())->path,
                    'patternName' => $variant->pattern?->name,
                    'colors' => $variant->pattern?->colors
                        ? $variant->pattern->colors->map(fn($c) => (object) [
                            'name' => $c->name,
                            'code' => $c->code,
                        ])->values()
                        : null,
                    'sizeName' => $variant->size?->name,
                    'price' => $variant->price,
                    'discount' => $variant->discount,
                    'quantity' => $variant->quantity,
                    'sku' => $variant->sku,
                    'status' => $product->status,
                    'level' => $product->level,
                    'createdAt' => $product->created_at,
                ];
            }
        }

        if (empty($rows)) {
            return null;
        }


        $collection = collect($rows)->map(fn($item) => (object) $item);
        $total = $collection->count();

        $lastPage = (int) ceil($total / $perPage);
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $paginated = new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return (object) [
            'data' => $paginated->items(),
            'meta' => (object) [
                'currentPage' => $paginated->currentPage(),
                'lastPage' => $paginated->lastPage(),
                'perPage' => $paginated->perPage(),
                'total' => $paginated->total(),
            ]
        ];
    }

    public function getAllProductsCustomer()
    {
        $products = Product::with([
            'variants:id,product_id,price,quantity,discount',
            'patterns.files:id,alt,name,mime_type,path'
        ])
            ->select('id', 'name')
            ->where('level')
            ->latest()
            ->get();
        if ($products->isEmpty())
            return null;
        return $products->map(function ($Product) {
            $firstVariant = $Product->variants->first();
            $firstImage = $Product->patterns
                ->flatMap(fn($pattern) => $pattern->files)
                ->first();
            return ProductCard::from($Product, $firstVariant, $firstImage);
        })->toArray();
    }

    public function getCategoryProductsCustomer(GetCategory $dto)
    {
        $Category = Category::find($dto->id);

        if (!$Category)
            CategoryException::categoryNotFound();

        $products = $Category
            ->products()
            ->with('patterns.files:id,alt,name,mime_type,path')
            ->get();

        return $products->map(function ($Product) {
            $firstVariant = $Product
                ->variants
                ->first();
            $firstImage = $Product->patterns
                ->flatMap(fn($pattern) => $pattern->files)
                ->first();
            return ProductCard::from($Product, $firstVariant, $firstImage);
        })->toArray();
    }

    public function filterProducts(array $filters, ?string $search, int $paginateNumber, string $orderBy = 'newest')
    {
        $filters = array_filter($filters, fn($v) => $v !== null);
        $query = Product::query()->with([
            'variants.pattern.files',
            'variants.pattern.colors',
            'variants.size',
            'categories'
        ]);

        $userLevel = auth()->user()?->level() ?? ProductLevelsEnum::BORONZE->value;

        $levels = match ($userLevel) {
            ProductLevelsEnum::BORONZE->value => [ProductLevelsEnum::BORONZE],
            ProductLevelsEnum::SILVER->value => [ProductLevelsEnum::BORONZE, ProductLevelsEnum::SILVER],
            ProductLevelsEnum::GOLD->value => [ProductLevelsEnum::BORONZE, ProductLevelsEnum::SILVER, ProductLevelsEnum::GOLD],
            default => [ProductLevelsEnum::BORONZE],
        };

        $query->whereIn('level', $levels);


        if (!empty($filters['category'])) {
            $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $filters['category']));
        }

        if (!empty($filters['brand'])) {
            $query->whereIn('brand', $filters['brand']);
        }

        if (!empty($filters['color'])) {
            $query->whereHas('variants.pattern.colors', fn($q) => $q->where('colors.id', $filters['color']));
        }

        if (!empty($filters['size'])) {
            $query->whereHas('variants', fn($q) => $q->whereIn('size_id', $filters['size']));
        }

        if (!empty($filters['minPrice']) && !empty($filters['maxPrice'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereBetween('price', [$filters['minPrice'], $filters['maxPrice']]);
            });
        }

        if ($filters['discount']) {
            $query->whereHas('variants', fn($q) => $q->where('discount', '>', 0));
        }

        if ($filters['quantity']) {
            $query->whereHas('variants', fn($q) => $q->where('quantity', '>', 0));
        }

        $searchParts = explode(' ', $search);

        if ($search) {
            $query->where(function ($q) use ($searchParts) {
                foreach ($searchParts as $part) {
                    if (trim($part) !== '') {
                        $q->where('name', 'like', "%{$part}%");
                    }
                }
            });
        }

        if ($orderBy === 'newest' || $orderBy === 'default') {
            $query->latest();
        } else if ($orderBy === 'cheapest') {
            $query->withMin('variants', 'price')
                ->orderBy('variants_min_price', 'desc');
        } else if ($orderBy === 'most-expensive') {
            $query->withMax('variants', 'price')
                ->orderBy('variants_max_price', 'desc');
        } else if ($orderBy === 'bestseller') {
            $query->withSum([
                'transactionItems as total_sold' => function ($q) {
                    $q->whereHas('transaction', function ($q) {
                        $q->where('status', 'success');
                    });
                }
            ], 'quantity')
                ->orderByDesc('total_sold')
                ->get();
        }

        $paginate = $query->paginate($paginateNumber);
        $products = $paginate->items();

        return [collect($products)->map(function ($product) use ($filters) {
            $firstVariant = $product->variants->first();

            $firstImage = null;

            if (!empty($filters['color'])) {
                $colorIds = is_array($filters['color']) ? $filters['color'] : [$filters['color']];

                $variantWithColor = $product->variants->first(function ($variant) use ($colorIds) {
                    return $variant->pattern->colors->pluck('id')->intersect($colorIds)->isNotEmpty();
                });

                if ($variantWithColor) {
                    $firstImage = $variantWithColor->pattern->files->first();
                    $firstVariant = $variantWithColor;
                }
            }

            if (!$firstImage && $firstVariant) {
                $firstImage = $firstVariant->pattern->files->first();
            }

            return ProductCard::from($product, $firstVariant, $firstImage);
        })->toArray(), $paginate->hasMorePages()];
    }

    public function getLastProductsCustomer(int $count = 10)
    {
        $userLevel = auth()->user()?->level() ?? ProductLevelsEnum::BORONZE->value;

        $levels = match ($userLevel) {
            ProductLevelsEnum::BORONZE->value => [ProductLevelsEnum::BORONZE],
            ProductLevelsEnum::SILVER->value => [ProductLevelsEnum::BORONZE, ProductLevelsEnum::SILVER],
            ProductLevelsEnum::GOLD->value => [ProductLevelsEnum::BORONZE, ProductLevelsEnum::SILVER, ProductLevelsEnum::GOLD],
            default => [ProductLevelsEnum::BORONZE],
        };

        $lastProduct = Product::with("patterns.files:id,alt,name,mime_type,path")
            ->whereIn('level', $levels)
            ->latest()
            ->take($count)
            ->get();
        if ($lastProduct->isEmpty())
            return null;
        return $lastProduct->map(function ($Product) {
            $firstVariant = $Product
                ->variants
                ->first();

            $firstImage = $Product->patterns
                ->flatMap(fn($pattern) => $pattern->files)
                ->first();
            return ProductCard::from($Product, $firstVariant, $firstImage);
        })->toArray();
    }

    public function getLastDiscountedProductsCustomer()
    {
        $productDiscounteds = Product::whereHas('variants', function ($query) {
            $query->where('discount', '>', 0);
        })
            ->with("patterns.files:id,alt,name,mime_type,path")
            ->latest()
            ->take(10)
            ->get();

        if ($productDiscounteds->isEmpty())
            return null;

        return $productDiscounteds->map(function ($Product) {
            $firstVariant = $Product
                ->variants
                ->first();

            $firstImage = $Product->patterns
                ->flatMap(fn($pattern) => $pattern->files)
                ->first();
            return ProductCard::from($Product, $firstVariant, $firstImage);
        })->toArray();
    }

    public function getAllBrands()
    {
        return Product::whereNot('brand', '')->distinct()->pluck('brand')->toArray();
    }

    public function getMinPrice()
    {
        return ProductVariant::min('price');
    }

    public function getMaxPrice()
    {
        return ProductVariant::max('price');
    }
}
