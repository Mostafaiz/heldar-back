<?php

namespace App\Services;

use App\Exceptions\Guarantee\GuaranteeException;
use App\Http\Dto\Request\Guarantee\CreateGuarantee as CreateGuaranteeDto;
use App\Http\Dto\Request\Guarantee\DeleteGuarantee as DeleteGuaranteeDto;
use App\Http\Dto\Request\Guarantee\UpdateGuarantee as UpdateGuaranteeDto;
use App\Http\Dto\Response\Guarantee as GuaranteeDto;
use App\Models\Guarantee;
use Illuminate\Database\Eloquent\Collection;

class GuaranteeService
{
    public function create(CreateGuaranteeDto $dto): GuaranteeDto
    {
        if (Guarantee::where('serial', $dto->serial)->exists())
            GuaranteeException::duplicateGuaranteeSerial();

        if ($dto->price < 0)
            GuaranteeException::guaranteeInvalidPrice();

        if ($dto->duration <= 0 || is_float($dto->duration))
            GuaranteeException::guaranteeInvalidDuration();

        $guarantee = Guarantee::create([
            'name' => $dto->name,
            'provider' => $dto->provider,
            'serial' => $dto->serial,
            'duration' => $dto->duration,
            'description' => $dto->description,
            'price' => $dto->price,
        ]);

        return GuaranteeDto::from($guarantee);
    }

    public function getAll(): Collection
    {
        $guarantees = Guarantee::latest()->get();

        return $guarantees;
    }

    public function getGuaranteeById(int $id): GuaranteeDto
    {
        $guarantee = Guarantee::find($id);
        if ($guarantee === null)
            GuaranteeException::guaranteeNotFound();

        return GuaranteeDto::from($guarantee);
    }

    public function getGuaranteesByName(string $name): Collection
    {
        return Guarantee::where('name', 'like', "%{$name}%")->orWhere('provider', 'like', "%{$name}%")->get();
    }

    public function update(UpdateGuaranteeDto $dto): GuaranteeDto
    {
        $guarantee = Guarantee::find($dto->id);
        if ($guarantee === null)
            GuaranteeException::GuaranteeNotFound();

        if (Guarantee::where('serial', $dto->serial)->whereNot('id', $dto->id)->exists())
            GuaranteeException::duplicateGuaranteeSerial();

        $guarantee->update([
            'name' => $dto->name,
            'provider' => $dto->provider,
            'serial' => $dto->serial,
            'duration' => $dto->duration,
            'description' => $dto->description,
            'price' => $dto->price,
        ]);

        return GuaranteeDto::from($guarantee);
    }

    public function delete(DeleteGuaranteeDto $dto): GuaranteeDto
    {
        $guarantee = Guarantee::find($dto->id);
        if ($guarantee === null)
            GuaranteeException::guaranteeNotFound();

        $guarantee->delete();

        return GuaranteeDto::from($guarantee);
    }
}
