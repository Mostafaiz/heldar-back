<?php

namespace App\Services;

use App\Exceptions\Size\SizeException;
use App\Http\Dto\Request\Size\CreateSize as CreateSizeDto;
use App\Http\Dto\Response\Size as SizeDto;
use App\Http\Dto\Request\Size\DeleteSize as DeleteSizeDto;
use App\Http\Dto\Request\Size\UpdateSize as UpdateSizeDto;
use App\Models\Size;
use Illuminate\Database\Eloquent\Collection;


class SizeService
{
    public function create(CreateSizeDto $dto): SizeDto
    {
        if (Size::where('name', $dto->name)->exists())
            SizeException::duplicateSizeName();

        $size = Size::create([
            'name' => $dto->name,
        ]);

        return SizeDto::from($size);
    }

    public function getAll(): Collection
    {
        $sizes = Size::latest()->get();

        return $sizes;
    }

    public function getAllCustomer()
    {
        return Size::latest()->get(['id', 'name'])->toArray();
    }

    public function getSizesByName(string $name)
    {
        return Size::where('name', 'like', "%{$name}%")->get();
    }

    public function update(UpdateSizeDto $dto): SizeDto
    {
        $size = Size::find($dto->id);
        if ($size === null)
            SizeException::sizeNotFound();

        if (Size::where('name', $dto->name)->whereNot('id', $dto->id)->exists())
            SizeException::duplicateSizeName();

        $size->update([
            'name' => $dto->name,
        ]);

        return SizeDto::from($size);
    }

    public function delete(DeleteSizeDto $dto): SizeDto
    {
        $size = Size::find($dto->id);
        if ($size === null)
            SizeException::sizeNotFound();

        $size->delete();
        return SizeDto::from($size);
    }
}
