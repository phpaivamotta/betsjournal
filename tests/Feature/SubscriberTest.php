<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_can_be_created()
    {
        $response = $this->post('/subscribers', [
            'subscriber-email' => 'test@email.com'
        ]);
        
        $response->assertStatus(302)->assertSessionHas('subscription-added');
        $this->assertDatabaseHas('subscribers', [
            'email' => 'test@email.com'
        ]);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/subscribers', [
            'subscriber-email' => 'The subscriber-email field is required.'
        ]);

        $response->assertSessionHasErrors('subscriber-email');
    }

    public function test_email_has_to_be_unique()
    {
        Subscriber::factory()->create([
            'email' => 'test@email.com'
        ]);

        $response = $this->post('/subscribers', [
            'subscriber-email' => 'test@email.com'
        ]);

        $response->assertSessionHasErrors([
            'subscriber-email' => 'The subscriber-email has already been taken.'
        ]);
    }
}
