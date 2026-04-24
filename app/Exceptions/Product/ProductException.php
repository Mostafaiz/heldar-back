<?php

namespace App\Exceptions\Product;

use App\Enums\Product\ProductExceptionsEnum;
use App\Exceptions\BaseException;

class ProductException extends BaseException
{
    public static function SlugAlreadyExists(): self
    {
        throw new self(
            ProductExceptionsEnum::SLUG_ALREADY_EXISTS->value,
            409,
            ProductExceptionsEnum::SLUG_ALREADY_EXISTS_CODE->value
        );
    }

    public static function NameAlreadyExists(): self
    {
        throw new self(
            ProductExceptionsEnum::NAME_ALREADY_EXISTS->value,
            409,
            ProductExceptionsEnum::NAME_ALREADY_EXISTS_CODE->value
        );
    }
    public static function InvalidSize(): self
    {
        throw new self(
            ProductExceptionsEnum::INVALID_SIZE->value,
            422,
            ProductExceptionsEnum::INVALID_SIZE_CODE->value
        );
    }

    public static function InvalidPrice(): self
    {
        throw new self(
            ProductExceptionsEnum::INVALID_PRICE->value,
            422,
            ProductExceptionsEnum::INVALID_PRICE_CODE->value
        );
    }

    public static function DuplicateSku(): self
    {
        throw new self(
            ProductExceptionsEnum::DUPLICATE_SKU->value,
            422,
            ProductExceptionsEnum::DUPLICATE_SKU_CODE->value
        );
    }

    public static function InvalidColorIds(): self
    {
        throw new self(
            ProductExceptionsEnum::INVALID_COLOR_IDS->value,
            422,
            ProductExceptionsEnum::INVALID_COLOR_IDS_CODE->value
        );
    }

    public static function InvalidSizeIds(): self
    {
        throw new self(
            ProductExceptionsEnum::INVALID_SIZE_IDS->value,
            422,
            ProductExceptionsEnum::INVALID_SIZE_IDS_CODE->value
        );
    }

    public static function InvalidGuaranteeIds(): self
    {
        throw new self(
            ProductExceptionsEnum::INVALID_GUARANTEE_IDS->value,
            422,
            ProductExceptionsEnum::INVALID_GUARANTEE_IDS_CODE->value
        );
    }

    public static function InvalidInsuranceIds(): self
    {
        throw new self(
            ProductExceptionsEnum::INVALID_INSURANCE_IDS->value,
            422,
            ProductExceptionsEnum::INVALID_INSURANCE_IDS_CODE->value
        );
    }

    public static function NegativeQuantity(): self
    {
        throw new self(
            ProductExceptionsEnum::NEGATIVE_QUANTITY->value,
            422,
            ProductExceptionsEnum::NEGATIVE_QUANTITY_CODE->value
        );
    }

    public static function ImageIdsInvalid(): self
    {
        throw new self(
            ProductExceptionsEnum::IMAGE_IDS_INVALID->value,
            422,
            ProductExceptionsEnum::IMAGE_IDS_INVALID_CODE->value
        );
    }

    public static function MissingRequiredField(): self
    {
        throw new self(
            ProductExceptionsEnum::MISSING_REQUIRED_FIELD->value,
            422,
            ProductExceptionsEnum::MISSING_REQUIRED_FIELD_CODE->value
        );
    }

    public static function ProductNotFound(): self
    {
        throw new self(
            ProductExceptionsEnum::NOT_FOUND->value,
            404,
            ProductExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function VariantNotFound(): self
    {
        throw new self(
            ProductExceptionsEnum::VARIANT_NOT_FOUND->value,
            404,
            ProductExceptionsEnum::VARIANT_NOT_FOUND_CODE->value
        );
    }
}
