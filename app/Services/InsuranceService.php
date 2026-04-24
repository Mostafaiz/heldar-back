<?php

namespace App\Services;

use App\Exceptions\Insurance\InsuranceException;
use App\Http\Dto\Request\Insurance\CreateInsurance as CreateInsuranceDto;
use App\Http\Dto\Request\Insurance\DeleteInsurance as DeleteInsuranceDto;
use App\Http\Dto\Request\Insurance\UpdateInsurance as UpdateInsuranceDto;
use App\Http\Dto\Response\Insurance as InsuranceDto;
use App\Models\Insurance;
use Illuminate\Database\Eloquent\Collection;

class InsuranceService
{
    public function create(CreateInsuranceDto $dto): InsuranceDto
    {
        if (Insurance::where('insurance_code', $dto->insuranceCode)->exists())
            InsuranceException::duplicateInsuranceCode();

        if ($dto->price < 0)
            InsuranceException::insuranceInvalidPrice();

        if ($dto->duration <= 0 || is_float($dto->duration))
            InsuranceException::insuranceInvalidDuration();

        $insurance = Insurance::create([
            'name' => $dto->name,
            'provider' => $dto->provider,
            'insurance_code' => $dto->insuranceCode,
            'duration' => $dto->duration,
            'description' => $dto->description,
            'price' => $dto->price,
        ]);

        return InsuranceDto::from($insurance);
    }

    public function getAll(): Collection
    {
        $insurance = Insurance::latest()->get();

        return $insurance;
    }

    public function getInsuranceById(int $id): InsuranceDto
    {
        $insurance = Insurance::find($id);
        if ($insurance === null)
            InsuranceException::insuranceNotFound();

        return InsuranceDto::from($insurance);
    }

    public function getInsurancesByName(string $name): Collection
    {
        return Insurance::where('name', 'like', "%{$name}%")->orWhere('provider', 'like', "%{$name}%")->get();
    }

    public function delete(DeleteInsuranceDto $dto): InsuranceDto
    {
        $insurance = Insurance::find($dto->id);
        if ($insurance === null)
            InsuranceException::insuranceNotFound();

        $insurance->delete();

        return InsuranceDto::from($insurance);
    }

    public function update(UpdateInsuranceDto $dto): InsuranceDto
    {
        $insurance = Insurance::find($dto->id);
        if ($insurance === null)
            InsuranceException::insuranceNotFound();

        if ($dto->price < 0)
            InsuranceException::insuranceInvalidPrice();

        if ($dto->duration <= 0 || is_float($dto->duration))
            InsuranceException::insuranceInvalidDuration();

        if (Insurance::where('insurance_code', $dto->insuranceCode)->whereNot('id', $dto->id)->exists())
            InsuranceException::duplicateInsuranceCode();

        $insurance->update([
            'name' => $dto->name,
            'provider' => $dto->provider,
            'insurance_code' => $dto->insuranceCode,
            'duration' => $dto->duration,
            'description' => $dto->description,
            'price' => $dto->price,
        ]);

        return InsuranceDto::from($insurance);
    }
}