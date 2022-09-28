<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BetTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_projects_can_be_created()
    {
        $attributes = [
            ''
        ];
    }
}
