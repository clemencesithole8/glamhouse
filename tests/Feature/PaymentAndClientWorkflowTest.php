<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Notifications\BookingRescheduleRequestedAdminNotification;
use App\Notifications\PaymentRecordedClientNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PaymentAndClientWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_payment_recording_sends_client_receipt_notification(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $booking = $this->createBooking([
            'email' => 'client@example.com',
            'deposit_amount' => 50,
            'total_amount' => 200,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.bookings.show', $booking))
            ->post(route('admin.payments.store', $booking), [
                'type' => 'deposit',
                'amount' => 50,
                'method' => 'cash',
                'reference' => 'DEP-50',
            ])
            ->assertRedirect(route('admin.bookings.show', $booking))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'amount' => 50,
            'method' => 'cash',
            'reference' => 'DEP-50',
        ]);

        Notification::assertSentTo(
            $booking,
            PaymentRecordedClientNotification::class,
            fn (PaymentRecordedClientNotification $notification) => $notification->payment->amount === 50,
        );

        $this->assertSame('balance_due', $booking->fresh('payments')->payment_status);
    }

    public function test_customer_can_request_reschedule_from_dashboard(): void
    {
        Notification::fake();
        config(['glamhouse.admin_email' => 'studio@example.com']);

        $user = User::factory()->create(['email' => 'client@example.com']);
        $booking = $this->createBooking(['email' => 'client@example.com']);

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('dashboard.bookings.reschedule', $booking), [
                'reschedule_note' => 'Could we move this to the afternoon?',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $booking->refresh();

        $this->assertNotNull($booking->reschedule_requested_at);
        $this->assertSame('Could we move this to the afternoon?', $booking->reschedule_note);

        Notification::assertSentOnDemand(BookingRescheduleRequestedAdminNotification::class);
    }

    private function createBooking(array $overrides = []): Booking
    {
        $service = Service::create([
            'name' => 'Soft Glam',
            'price' => 100,
            'description' => 'Soft glam makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        return Booking::create(array_merge([
            'full_name' => 'Client One',
            'phone' => '0770000000',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => now()->addDay()->toDateString(),
            'service_id' => $service->id,
            'event_type' => 'Photoshoot',
            'is_outcall' => false,
            'deposit_ack' => true,
            'lateness_ack' => true,
            'info_confirmed' => true,
            'status' => 'confirmed',
        ], $overrides));
    }
}
