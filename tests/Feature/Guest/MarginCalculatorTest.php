<?php

namespace Tests\Feature\Guest;

use Tests\TestCase;

class MarginCalculatorTest extends TestCase
{
    public function test_margin_calculator_screen_can_be_rendered()
    {
        $response = $this->get('/margin-calculator');

        $response->assertStatus(200);
    }
}
