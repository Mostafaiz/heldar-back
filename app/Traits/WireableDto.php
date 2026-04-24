<?php

namespace App\Traits;

trait WireableDto
{
    public function toLivewire(): array
    {
        $data = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value instanceof Wireable) {
                $data[$key] = $value->toLivewire();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    public static function fromLivewire($value): static
    {
        $args = [];
        foreach ($value as $key => $val) {
            $propertyType = (new \ReflectionClass(static::class))
                ->getConstructor()
                ->getParameters()[array_search($key, array_keys($value))]
                ->getType();

            if (
                $propertyType &&
                !$propertyType->isBuiltin() &&
                is_subclass_of($propertyType->getName(), Wireable::class)
            ) {
                $args[] = $propertyType->getName()::fromLivewire($val);
            } else {
                $args[] = $val;
            }
        }
        return new static(...$args);
    }
}
