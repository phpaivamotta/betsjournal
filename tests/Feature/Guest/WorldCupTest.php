<?php

namespace Tests\Feature\Guest;

use Tests\TestCase;

class WorldCupTest extends TestCase
{
    public function test_world_cup_screen_can_be_rendered()
    {
        $response = $this->get('/world-cup');

        $response->assertStatus(200);
    }
}
