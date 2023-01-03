<?php

namespace App\Services;

class ConvertOddsService
{

    /**
     * Converts odds in decimal format into american format
     * 
     * @param int|float $odd
     * 
     * @return int|float
     */
    public static function decimalToAmerican($odd)
    {
        if ($odd >= 2) {
            return ($odd - 1) * 100;
        } else {
            return -100 / ($odd - 1);
        }
    }

    /**
     * Converts odds in american format into decimal format
     * 
     * @param int|float $odd
     * 
     * @return int|float
     */
    public static function americanToDecimal($odd)
    {
        if ($odd > 0) {
            return ($odd / 100) + 1;
        } else {
            return (100 / abs($odd)) + 1;
        }
    }
}