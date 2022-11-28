<?php

namespace Tests\Feature\Guest;

use Tests\TestCase;

class OddsComparisonTest extends TestCase
{
    public function test_odds_comparison_screen_can_be_rendered()
    {
        $response = $this->get('/odds-comparison');

        $response->assertStatus(200);
    }
}
