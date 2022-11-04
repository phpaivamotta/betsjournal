<?php

namespace Tests\Feature\Guest;

use Tests\TestCase;

class ValueBetsTest extends TestCase
{
    public function test_value_bets_screen_can_be_rendered()
    {
        $response = $this->get('/odds-comparison');

        $response->assertStatus(200);
    }
}
