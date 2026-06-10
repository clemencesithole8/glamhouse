<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BookingPdfAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_pdf_is_not_publicly_accessible_by_id(): void
    {
        $booking = $this->createBooking('client@example.com');

        $this->get(route('booking.pdf', $booking))->assertForbidden();
    }

    public function test_booking_pdf_can_be_downloaded_by_owner_admin_or_signed_link(): void
    {
        $booking = $this->createBooking('client@example.com');
        $customer = User::factory()->create(['email' => 'client@example.com']);
        $otherCustomer = User::factory()->create(['email' => 'other@example.com']);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->fakePdfDownload();

        $this->actingAs($otherCustomer)
            ->get(route('booking.pdf', $booking))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('booking.pdf', $booking))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('booking.pdf', $booking))
            ->assertOk();

        $this->get(URL::temporarySignedRoute('booking.pdf', now()->addMinutes(10), ['bookingId' => $booking->id]))
            ->assertOk();
    }

    private function fakePdfDownload(): void
    {
        Pdf::shouldReceive('loadView')
            ->andReturnSelf();
        Pdf::shouldReceive('setPaper')
            ->andReturnSelf();
        Pdf::shouldReceive('download')
            ->andReturn(response('pdf'));
    }

    private function createBooking(string $email): Booking
    {
        $service = Service::create([
            'name' => 'Soft Glam',
            'price' => 100,
            'description' => 'Soft glam makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        return Booking::create([
            'full_name' => 'Client One',
            'phone' => '0770000000',
            'email' => $email,
            'location_area' => 'Harare',
            'appointment_date' => now()->addDay()->toDateString(),
            'service_id' => $service->id,
            'event_type' => 'Photoshoot',
            'is_outcall' => false,
            'deposit_ack' => true,
            'lateness_ack' => true,
            'info_confirmed' => true,
            'status' => 'confirmed',
        ]);
    }
}
