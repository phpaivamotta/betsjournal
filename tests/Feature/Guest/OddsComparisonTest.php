<?php

namespace Tests\Feature\Guest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValueBetsTest extends TestCase
{
    public function test_value_bets_screen_can_be_rendered()
    {
        $response = $this->get('/odds-comparison');

        $response->assertStatus(200);
    }
}
