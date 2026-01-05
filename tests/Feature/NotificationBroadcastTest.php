<?php

namespace Tests\Feature;

use App\Events\NotificationSent;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NotificationBroadcastTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test bahwa NotificationSent event di-broadcast dengan benar
     */
    public function test_notification_sent_event_is_broadcasted(): void
    {
        Event::fake([NotificationSent::class]);

        $user = User::factory()->create();

        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Test Notifikasi',
            'pesan' => 'Ini adalah test notifikasi real-time',
            'tipe' => 'info',
        ]);

        // Trigger event
        event(new NotificationSent($notifikasi));

        // Assert event di-dispatch
        Event::assertDispatched(NotificationSent::class, function ($event) use ($notifikasi) {
            return $event->notification->id === $notifikasi->id;
        });
    }

    /**
     * Test channel name untuk NotificationSent event
     */
    public function test_notification_sent_broadcasts_on_correct_channel(): void
    {
        $user = User::factory()->create();

        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Test Channel',
            'pesan' => 'Test channel notifikasi',
            'tipe' => 'success',
        ]);

        $event = new NotificationSent($notifikasi);
        $channel = $event->broadcastOn();

        $this->assertEquals('private-user.' . $user->id, $channel->name);
    }

    /**
     * Test data yang di-broadcast
     */
    public function test_notification_sent_broadcasts_correct_data(): void
    {
        $user = User::factory()->create();

        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Test Data',
            'pesan' => 'Test data broadcast',
            'tipe' => 'warning',
        ]);

        $event = new NotificationSent($notifikasi);
        $broadcastData = $event->broadcastWith();

        $this->assertArrayHasKey('id', $broadcastData);
        $this->assertArrayHasKey('user_id', $broadcastData);
        $this->assertArrayHasKey('judul', $broadcastData);
        $this->assertArrayHasKey('pesan', $broadcastData);
        $this->assertArrayHasKey('tipe', $broadcastData);
        $this->assertEquals($notifikasi->id, $broadcastData['id']);
        $this->assertEquals($user->id, $broadcastData['user_id']);
    }

    /**
     * Test broadcast event name
     */
    public function test_notification_sent_has_correct_broadcast_name(): void
    {
        $user = User::factory()->create();

        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Test Event Name',
            'pesan' => 'Test nama event',
            'tipe' => 'error',
        ]);

        $event = new NotificationSent($notifikasi);

        $this->assertEquals('notification.sent', $event->broadcastAs());
    }

    /**
     * Test authorization untuk user channel
     */
    public function test_user_can_only_access_their_own_notification_channel(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User1 bisa akses channel sendiri
        $this->actingAs($user1);
        $response = $this->postJson("/broadcasting/auth", [
            'channel_name' => 'private-user.' . $user1->id,
        ]);
        $response->assertSuccessful();

        // User1 tidak bisa akses channel user2
        $response = $this->postJson("/broadcasting/auth", [
            'channel_name' => 'private-user.' . $user2->id,
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test multiple notifications untuk user yang sama
     */
    public function test_multiple_notifications_for_same_user(): void
    {
        Event::fake([NotificationSent::class]);

        $user = User::factory()->create();

        $notif1 = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Notifikasi 1',
            'pesan' => 'Pesan 1',
            'tipe' => 'info',
        ]);

        $notif2 = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Notifikasi 2',
            'pesan' => 'Pesan 2',
            'tipe' => 'success',
        ]);

        event(new NotificationSent($notif1));
        event(new NotificationSent($notif2));

        Event::assertDispatched(NotificationSent::class, 2);
    }
}
