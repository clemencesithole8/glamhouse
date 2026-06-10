<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_customer_dashboard_shows_bookings_for_the_signed_in_email(): void
    {
        $user = User::factory()->create([
            'email' => 'client@example.com',
        ]);
        $service = Service::create([
            'name' => 'Bridal Glam',
            'price' => 200,
            'description' => 'Full bridal makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);
        $timeSlot = TimeSlot::create([
            'start_time' => '09:00',
            'end_time' => '10:30',
            'is_active' => true,
        ]);
        $appointmentDate = now()->addWeek()->toDateString();
        $booking = Booking::create([
            'full_name' => 'Client One',
            'phone' => '0770000000',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => $appointmentDate,
            'time_slot_id' => $timeSlot->id,
            'service_id' => $service->id,
            'event_type' => 'Wedding',
            'is_outcall' => false,
            'deposit_ack' => true,
            'lateness_ack' => true,
            'info_confirmed' => true,
            'status' => 'confirmed',
            'total_amount' => 200,
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'type' => 'deposit',
            'amount' => 50,
            'method' => 'cash',
            'reference' => 'DEP-50',
            'paid_at' => now(),
        ]);
        Booking::create([
            'full_name' => 'Other Client',
            'phone' => '0771111111',
            'email' => 'other@example.com',
            'location_area' => 'Harare',
            'appointment_date' => now()->addDays(8)->toDateString(),
            'service_id' => $service->id,
            'event_type' => 'Photoshoot',
            'is_outcall' => false,
            'deposit_ack' => true,
            'lateness_ack' => true,
            'info_confirmed' => true,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('My bookings');
        $response->assertSee('Bridal Glam');
        $response->assertSee('Confirmed');
        $response->assertSee('$150');
        $response->assertSee(route('booking.pdf', $booking), false);
        $response->assertDontSee('Other Client');
        $response->assertDontSee('Photoshoot');
    }

    public function test_customer_dashboard_has_an_empty_state(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Your booking list is ready.');
        $response->assertSee('Start a booking');
    }

    public function test_public_footer_shows_admin_panel_entry_point(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Admin Panel');
        $response->assertSee(route('admin.dashboard', absolute: false), false);
    }
}
