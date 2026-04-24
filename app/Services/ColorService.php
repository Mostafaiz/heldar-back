<?php

namespace App\Services;

use App\Http\Dto\CreateColorDto;
use App\Http\Dto\DeleteColorDto;
use App\Http\Dto\UpdateColorDto;
use App\Models\Color;

class ColorService
{
    public function index()
    {
        $colors = Color::all()->sortDesc();
        return $colors;
    }

    public function getAllCustomer()
    {
        return Color::latest()->get(['id', 'name', 'code'])->toArray();
    }

    public function getAllCustomerIfHasProducts()
    {
        return Color::query()
            ->whereHas('patterns', function ($query) {
                $query->whereHas('product');
            })
            ->latest()
            ->get(['id', 'name', 'code'])
            ->toArray();
    }

    public function getColorsByName(string $name)
    {
        return Color::where('name', 'like', "%{$name}%")->get();
    }

    public function store(CreateColorDto $dto, $managerId = 1)
    {
        $color = Color::create([
            'name' => $dto->name,
            'code' => $dto->code,
        ]);

        return $color;
    }
    public function update(UpdateColorDto $dto, $managerId = 1)
    {
        $dto->color->update([
            'name' => $dto->name,
            'code' => $dto->code,
        ]);
        return $dto->color;
    }
    public function delete(DeleteColorDto $dto, $managerId = 1)
    {
        $dto->color->delete();
    }
}
