<?php

namespace Tests\Unit;

use App\Services\ConvertOddsService;
use PHPUnit\Framework\TestCase;

class ConvertOddsTest extends TestCase
{
    public function test_can_convert_american_odd_to_decimal_odd()
    {
        $americanOdd = 500;

        $decimalOdd = ConvertOddsService::americanToDecimal($americanOdd);
    
        $this->assertEquals(6, $decimalOdd);
    }

    public function test_can_convert_decimal_odd_to_american_odd()
    {
        $decimalOdd = 6;

        $americanOdd = ConvertOddsService::decimalToAmerican($decimalOdd);
    
        $this->assertEquals(500, $americanOdd);
    }
}
