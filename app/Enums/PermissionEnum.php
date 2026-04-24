<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case MANAGE_USERS = 'manage users';
    case MANAGE_PRODUCTS = 'manage products';
    case MANAGE_CATEGORIES = 'manage categories';
    case MANAGE_COLORS = 'manage colors';
    case MANAGE_ATTRIBUTES = 'manage attributes';
    case MANAGE_SIZES = 'manage sizes';
    case MANAGE_GUARANTEES = 'manage guarantees';
    case MANAGE_INSURANCES = 'manage insurances';
    case MANAGE_GALLERY = 'manage gallery';
}