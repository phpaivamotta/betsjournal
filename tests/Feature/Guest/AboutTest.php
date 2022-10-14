<?php

namespace Tests\Feature\Guest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AboutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_about_screen_can_be_rendered()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }
}
