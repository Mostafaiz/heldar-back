<?php

namespace App\Enums\Product;

enum ProductExceptionsEnum: string
{
    case SLUG_ALREADY_EXISTS = 'Slug already exists';
    case SLUG_ALREADY_EXISTS_CODE = '409|1';
    case NAME_ALREADY_EXISTS = 'Name already exists';
    case NAME_ALREADY_EXISTS_CODE = '409|2';
    case INVALID_SIZE = 'Invalid size';
    case INVALID_SIZE_CODE = '422|2';
    case INVALID_PRICE = 'Invalid price';
    case INVALID_PRICE_CODE = '422|3';
    case DUPLICATE_SKU = 'Duplicate sku';
    case DUPLICATE_SKU_CODE = '422|4';
    case INVALID_COLOR_IDS = 'Invalid color ids';
    case INVALID_COLOR_IDS_CODE = '422|5';
    case INVALID_SIZE_IDS = 'Invalid size ids';
    case INVALID_SIZE_IDS_CODE = '422|6';
    case INVALID_GUARANTEE_IDS = 'Invalid guarantee ids';
    case INVALID_GUARANTEE_IDS_CODE = '422|7';
    case INVALID_INSURANCE_IDS = 'Invalid insurance ids';
    case INVALID_INSURANCE_IDS_CODE = '422|8';
    case NEGATIVE_QUANTITY = 'Negative quantity';
    case NEGATIVE_QUANTITY_CODE = '422|9';
    case IMAGE_IDS_INVALID = 'Image ids invalid';
    case IMAGE_IDS_INVALID_CODE = '422|10';
    case MISSING_REQUIRED_FIELD = 'Missing required field';
    case MISSING_REQUIRED_FIELD_CODE = '422|11';
    case GENERAL_FAILURE = 'General failure';
    case NOT_FOUND = 'Product not found';
    case NOT_FOUND_CODE = '404|1';
    case VARIANT_NOT_FOUND = 'Product variant not found';
    case VARIANT_NOT_FOUND_CODE = '404|2';
}
