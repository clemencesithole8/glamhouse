<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Notifications\BookingSubmittedAdminNotification;
use App\Notifications\BookingSubmittedClientNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BookingSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_submission_redirects_to_a_signed_pdf_summary_link(): void
    {
        Notification::fake();
        Pdf::shouldReceive('loadView')->andReturnSelf();
        Pdf::shouldReceive('setPaper')->andReturnSelf();
        Pdf::shouldReceive('download')->andReturn(response('pdf'));

        $service = $this->createService();

        $response = $this->from('/booking')->post('/booking', $this->validPayload($service));

        $response->assertRedirect();

        $location = $response->headers->get('Location');

        $this->assertStringContainsString('/booking/1/pdf', $location);
        $this->assertStringContainsString('signature=', $location);
        $this->get($location)->assertOk();
    }

    public function test_booking_submission_sends_client_and_admin_email_notifications(): void
    {
        config()->set('glamhouse.admin_email', 'admin@example.com');
        config()->set('glamhouse.whatsapp_notifications.enabled', false);

        Notification::fake();

        $service = $this->createService();

        $this->from('/booking')->post('/booking', $this->validPayload($service))
            ->assertRedirect();

        $booking = Booking::query()->firstOrFail();

        Notification::assertSentTo($booking, BookingSubmittedClientNotification::class);
        Notification::assertSentOnDemand(BookingSubmittedAdminNotification::class, function (
            BookingSubmittedAdminNotification $notification,
            array $channels,
            object $notifiable
        ) use ($booking) {
            return $notification->booking->is($booking)
                && $channels === ['mail']
                && ($notifiable->routes['mail'] ?? null) === 'admin@example.com';
        });

        $this->assertStringNotContainsString(
            'No automated admin alert was delivered',
            (string) $booking->fresh()->admin_notes
        );
    }

    public function test_booking_submission_records_visible_alert_when_admin_channels_are_not_configured(): void
    {
        config()->set('glamhouse.admin_email', null);
        config()->set('glamhouse.whatsapp_notifications.enabled', false);

        Notification::fake();

        $service = $this->createService();

        $this->from('/booking')->post('/booking', $this->validPayload($service))
            ->assertRedirect();

        $booking = Booking::query()->firstOrFail();

        $this->assertStringContainsString('No automated admin alert was delivered', $booking->admin_notes);
        $this->assertStringContainsString('Admin email alert is not configured', $booking->admin_notes);
        $this->assertStringContainsString('WhatsApp admin alert was not delivered', $booking->admin_notes);
        Notification::assertSentOnDemandTimes(BookingSubmittedAdminNotification::class, 0);

        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee('Alert issue');

        $this->actingAs($admin)
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee('Notification Alert')
            ->assertSee('No automated admin alert was delivered');
    }

    public function test_booking_submission_records_whatsapp_failure_without_blocking_admin_email(): void
    {
        config()->set('glamhouse.admin_email', 'admin@example.com');
        config()->set('glamhouse.whatsapp_notifications.enabled', true);
        config()->set('glamhouse.whatsapp_notifications.provider', 'meta');
        config()->set('glamhouse.whatsapp_notifications.admin_number', '+263 784 721 479');
        config()->set('glamhouse.whatsapp_notifications.meta.access_token', 'test-token');
        config()->set('glamhouse.whatsapp_notifications.meta.phone_number_id', 'phone-number-id');

        Http::fake([
            'graph.facebook.com/*' => Http::response(['error' => 'provider unavailable'], 500),
        ]);
        Notification::fake();

        $service = $this->createService();

        $this->from('/booking')->post('/booking', $this->validPayload($service))
            ->assertRedirect();

        $booking = Booking::query()->firstOrFail();

        Notification::assertSentOnDemand(BookingSubmittedAdminNotification::class);
        Http::assertSentCount(1);

        $this->assertStringContainsString('WhatsApp admin alert was not delivered', $booking->admin_notes);
        $this->assertStringNotContainsString('No automated admin alert was delivered', $booking->admin_notes);
    }

    private function createService(): Service
    {
        return Service::create([
            'name' => 'Soft Glam',
            'price' => 100,
            'description' => 'Soft glam makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);
    }

    private function validPayload(Service $service, array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Client One',
            'phone' => '0770000000',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => now()->addDay()->toDateString(),
            'service_id' => $service->id,
            'event_type' => 'Photoshoot',
            'is_outcall' => '0',
            'has_done_pro_makeup' => '1',
            'deposit_ack' => '1',
            'lateness_ack' => '1',
            'info_confirmed' => '1',
        ], $overrides);
    }
}
