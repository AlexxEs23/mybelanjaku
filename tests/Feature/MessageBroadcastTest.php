<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\PesanChat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MessageBroadcastTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test bahwa MessageSent event di-broadcast dengan benar
     */
    public function test_message_sent_event_is_broadcasted(): void
    {
        Event::fake([MessageSent::class]);

        $admin = User::factory()->admin()->create();
        $penjual = User::factory()->penjual()->create();

        $chat = Chat::create([
            'admin_id' => $admin->id,
            'penjual_id' => $penjual->id,
        ]);

        $pesan = PesanChat::create([
            'chat_id' => $chat->id,
            'pengirim_id' => $admin->id,
            'pesan' => 'Test pesan real-time',
        ]);

        // Trigger event
        event(new MessageSent($pesan));

        // Assert event di-dispatch
        Event::assertDispatched(MessageSent::class, function ($event) use ($pesan) {
            return $event->message->id === $pesan->id;
        });
    }

    /**
     * Test channel name untuk MessageSent event
     */
    public function test_message_sent_broadcasts_on_correct_channel(): void
    {
        $admin = User::factory()->admin()->create();
        $penjual = User::factory()->penjual()->create();

        $chat = Chat::create([
            'admin_id' => $admin->id,
            'penjual_id' => $penjual->id,
        ]);

        $pesan = PesanChat::create([
            'chat_id' => $chat->id,
            'pengirim_id' => $admin->id,
            'pesan' => 'Test channel',
        ]);

        $event = new MessageSent($pesan);
        $channel = $event->broadcastOn();

        $this->assertEquals('private-chat.' . $chat->id, $channel->name);
    }

    /**
     * Test data yang di-broadcast
     */
    public function test_message_sent_broadcasts_correct_data(): void
    {
        $admin = User::factory()->admin()->create();
        $penjual = User::factory()->penjual()->create();

        $chat = Chat::create([
            'admin_id' => $admin->id,
            'penjual_id' => $penjual->id,
        ]);

        $pesan = PesanChat::create([
            'chat_id' => $chat->id,
            'pengirim_id' => $admin->id,
            'pesan' => 'Test data broadcast',
        ]);

        $event = new MessageSent($pesan);
        $broadcastData = $event->broadcastWith();

        $this->assertArrayHasKey('id', $broadcastData);
        $this->assertArrayHasKey('chat_id', $broadcastData);
        $this->assertArrayHasKey('pengirim_id', $broadcastData);
        $this->assertArrayHasKey('pesan', $broadcastData);
        $this->assertEquals($pesan->id, $broadcastData['id']);
        $this->assertEquals($chat->id, $broadcastData['chat_id']);
    }

    /**
     * Test broadcast event name
     */
    public function test_message_sent_has_correct_broadcast_name(): void
    {
        $admin = User::factory()->admin()->create();
        $penjual = User::factory()->penjual()->create();

        $chat = Chat::create([
            'admin_id' => $admin->id,
            'penjual_id' => $penjual->id,
        ]);

        $pesan = PesanChat::create([
            'chat_id' => $chat->id,
            'pengirim_id' => $admin->id,
            'pesan' => 'Test event name',
        ]);

        $event = new MessageSent($pesan);

        $this->assertEquals('message.sent', $event->broadcastAs());
    }

    /**
     * Test authorization untuk chat channel
     */
    public function test_user_can_access_their_chat_channel(): void
    {
        $admin = User::factory()->admin()->create();
        $penjual = User::factory()->penjual()->create();
        $otherUser = User::factory()->penjual()->create();

        $chat = Chat::create([
            'admin_id' => $admin->id,
            'penjual_id' => $penjual->id,
        ]);

        // Admin bisa akses
        $this->actingAs($admin);
        $response = $this->postJson("/broadcasting/auth", [
            'channel_name' => 'private-chat.' . $chat->id,
        ]);
        $response->assertSuccessful();

        // Penjual bisa akses
        $this->actingAs($penjual);
        $response = $this->postJson("/broadcasting/auth", [
            'channel_name' => 'private-chat.' . $chat->id,
        ]);
        $response->assertSuccessful();

        // User lain tidak bisa akses
        $this->actingAs($otherUser);
        $response = $this->postJson("/broadcasting/auth", [
            'channel_name' => 'private-chat.' . $chat->id,
        ]);
        $response->assertStatus(403);
    }
}
