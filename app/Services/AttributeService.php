<?php

namespace App\Services;

use App\Http\Dto\Attribute\CreateAttributeDto;
use App\Http\Dto\Attribute\CreateAttributeGroupDto;
use App\Http\Dto\Attribute\DeleteAttributeDto;
use App\Http\Dto\Attribute\DeleteAttributeGroupDto;
use App\Http\Dto\Attribute\UpdateAttributeDto;
use App\Http\Dto\Attribute\UpdateAttributeGroupDto;
use App\Models\AttributeGroup;

class AttributeService
{

    public function createAttributeGroup(CreateAttributeGroupDto $dto)
    {
        $attributeGroup = AttributeGroup::create([
            'name' => $dto->name,
        ]);

        $attributes = array_map(
            fn($attribute) => ['key' => $attribute],
            $dto->attributes
        );

        $attributeGroup->attributes()->createMany($attributes);

        return $attributeGroup;
    }

    public function createAttribute(CreateAttributeDto $dto)
    {
        return $dto->attributeGroup->attributes()->create([
            'key' => $dto->key
        ]);
    }

    public function all()
    {
        return AttributeGroup::with('attributes')->get()->sortDesc();
    }

    public function getAttributeGroupsByName(string $name)
    {
        return AttributeGroup::where('name', 'like', "%{$name}%")->with('attributes')->get();
    }

    public function updateAttribute(UpdateAttributeDto $dto)
    {
        $dto->attribute->update([
            'key' => $dto->key
        ]);
    }

    public function updateAttributeGroup(UpdateAttributeGroupDto $dto)
    {
        $attributeGroup = $dto->attributeGroup->update([
            'name' => $dto->name
        ]);
        return $attributeGroup;
    }

    public function deleteAttribute(DeleteAttributeDto $dto)
    {
        $dto->attribute->delete();
    }

    public function deleteAttributeGroup(DeleteAttributeGroupDto $dto)
    {
        $dto->attributeGroup->delete();
    }
}