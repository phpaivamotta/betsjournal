<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ValueBets;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ValueBetsTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(ValueBets::class);

        $component->assertStatus(200);
    }
}
