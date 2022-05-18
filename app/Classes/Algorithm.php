<?php

namespace App\Classes;

class Algorithm
{
    public static function factorial(int $number): int
    {
        if ($number === 1) {
            return 1;
        }

        return $number * self::factorial($number - 1);
    }
}
