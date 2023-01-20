<?php

namespace Tests\Feature\Api\ValueBets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValueBetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_value_bets()
    {
        $this->signIn();

        $this->getJson('/api/v1/value-bets?sport=mma_mixed_martial_arts&regions=us,uk&overValue=5')
            ->assertStatus(200);
    }

    public function test_guest_cannot_get_value_bets()
    {
        $this->getJson('/api/v1/value-bets')
            ->assertStatus(401);
    }
}
