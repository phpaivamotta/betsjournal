<?php

namespace Tests\Feature\Guest;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    public function test_home_screen_can_be_rendered()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
