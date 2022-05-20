<?php

namespace App\Classes;

class Product
{
    const LIST_OF_EXISTING_PRODUCTS = [1, 2, 3, 4, 5];

    public static function exists(int $id): bool
    {
        return in_array($id, self::LIST_OF_EXISTING_PRODUCTS);
    }
}
