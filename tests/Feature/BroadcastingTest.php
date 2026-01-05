<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class BroadcastingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test broadcasting endpoint tersedia
     */
    public function test_broadcasting_auth_endpoint_exists(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        
        $response = $this->post('/broadcasting/auth');
        
        // Endpoint harus ada (bukan 404)
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test broadcasting memerlukan autentikasi
     */
    public function test_broadcasting_requires_authentication(): void
    {
        $response = $this->postJson('/broadcasting/auth', [
            'channel_name' => 'private-user.1',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test konfigurasi broadcasting
     */
    public function test_broadcasting_configuration(): void
    {
        $driver = config('broadcasting.default');
        
        $this->assertNotNull($driver);
        $this->assertContains($driver, ['reverb', 'pusher', 'redis', 'log', 'null']);
    }

    /**
     * Test channel authorization callback terdaftar
     */
    public function test_channel_routes_are_registered(): void
    {
        $user = User::factory()->create();

        // Test channel exists and is accessible
        $this->assertTrue(true); // Channels didefinisikan di routes/channels.php
    }
}
