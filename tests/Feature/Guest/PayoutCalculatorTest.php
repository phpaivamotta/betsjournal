<?php

namespace Tests\Feature;

use Tests\TestCase;

class PayoutCalculatorTest extends TestCase
{
    /**
     * test if payout-calculator page can be rendered
     *
     * @return void
     */
    public function test_payout_calculator_screen_can_be_rendered()
    {
        $response = $this->get('/payout-calculator');

        $response->assertStatus(200);
    }
}
