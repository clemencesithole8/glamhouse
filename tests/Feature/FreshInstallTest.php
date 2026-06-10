<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class FreshInstallTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_paths_render_before_app_tables_exist(): void
    {
        Schema::shouldReceive('hasTable')
            ->withArgs(fn (string $table): bool => in_array($table, [
                'media_assets',
                'portfolio_items',
                'services',
                'testimonials',
                'time_slots',
            ], true))
            ->andReturn(false);

        foreach ([
            '/',
            '/about',
            '/services',
            '/picture-perfect',
            '/portfolio',
            '/policies',
            '/faq',
            '/contact',
            '/booking',
            '/robots.txt',
            '/sitemap.xml',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_availability_returns_empty_slots_before_booking_tables_exist(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('time_slots')
            ->andReturn(false);

        $this->getJson('/availability?date='.now()->addDay()->toDateString())
            ->assertOk()
            ->assertJson([
                'slots' => [],
            ]);
    }

    public function test_availability_still_lists_slots_when_bookings_table_is_missing(): void
    {
        $slot = TimeSlot::create([
            'start_time' => '09:00',
            'end_time' => '10:00',
            'is_active' => true,
        ]);

        Schema::shouldReceive('hasTable')
            ->once()
            ->with('time_slots')
            ->andReturn(true);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('bookings')
            ->andReturn(false);

        $this->getJson('/availability?date='.now()->addDay()->toDateString())
            ->assertOk()
            ->assertJsonPath('slots.0.id', $slot->id)
            ->assertJsonPath('slots.0.is_available', true);
    }

    public function test_booking_submit_fails_gracefully_before_booking_tables_exist(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('services')
            ->andReturn(false);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('time_slots')
            ->andReturn(false);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('bookings')
            ->andReturn(false);

        $this->from('/booking')->post('/booking', [
            'full_name' => 'Fresh Install Client',
            'phone' => '+263784721479',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => now()->addDay()->toDateString(),
            'service_id' => 1,
            'is_outcall' => '0',
            'has_done_pro_makeup' => '1',
            'deposit_ack' => '1',
            'lateness_ack' => '1',
            'info_confirmed' => '1',
        ])
            ->assertRedirect('/booking')
            ->assertSessionHasErrors('service_id');
    }

    public function test_booking_submit_fails_gracefully_when_services_table_is_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->twice()
            ->with('services')
            ->andReturn(false);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('time_slots')
            ->andReturn(false);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('bookings')
            ->andReturn(true);

        $this->from('/booking')->post('/booking', [
            'full_name' => 'Fresh Install Client',
            'phone' => '+263784721479',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => now()->addDay()->toDateString(),
            'service_id' => 1,
            'is_outcall' => '0',
            'has_done_pro_makeup' => '1',
            'deposit_ack' => '1',
            'lateness_ack' => '1',
            'info_confirmed' => '1',
        ])
            ->assertRedirect('/booking')
            ->assertSessionHasErrors('service_id');
    }

    public function test_booking_pdf_route_404s_before_bookings_table_exists(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('bookings')
            ->andReturn(false);

        $this->get('/booking/1/pdf')->assertNotFound();
    }

    public function test_booking_pdf_download_survives_missing_related_tables(): void
    {
        $booking = $this->createBooking();

        Schema::shouldReceive('hasTable')
            ->once()
            ->with('bookings')
            ->andReturn(true);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('services')
            ->andReturn(false);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('time_slots')
            ->andReturn(false);
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('payments')
            ->andReturn(false);

        Pdf::shouldReceive('loadView')
            ->once()
            ->andReturnSelf();
        Pdf::shouldReceive('setPaper')
            ->once()
            ->with('a4')
            ->andReturnSelf();
        Pdf::shouldReceive('download')
            ->once()
            ->andReturn(response('pdf'));

        $this->get(URL::temporarySignedRoute('booking.pdf', now()->addMinutes(10), ['bookingId' => $booking->id]))
            ->assertOk();
    }

    private function createBooking(): Booking
    {
        $service = Service::create([
            'name' => 'Soft Glam',
            'price' => 100,
            'description' => 'Soft glam makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        return Booking::create([
            'full_name' => 'Fresh Install Client',
            'phone' => '+263784721479',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => now()->addDay()->toDateString(),
            'service_id' => $service->id,
            'is_outcall' => false,
            'has_done_pro_makeup' => true,
            'deposit_ack' => true,
            'lateness_ack' => true,
            'info_confirmed' => true,
            'status' => 'pending',
        ]);
    }
}
