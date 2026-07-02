<?php

namespace Tests\Feature;

use App\Models\AvailabilityBlock;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Models\Testimonial;
use App\Models\TimeSlot;
use App\Models\User;
use App\Notifications\BookingRescheduledClientNotification;
use App\Notifications\BookingStatusUpdatedClientNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminControlBookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_core_content_records(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.services.store'), [
                'name' => 'Bridal Glam',
                'price' => 120,
                'description' => 'Bridal service.',
                'is_consultation_based' => '0',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('admin.time-slots.store'), [
                'start_time' => '09:00',
                'end_time' => '10:00',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('admin.portfolio-items.store'), [
                'title' => 'Soft bridal look',
                'category' => 'Bridal',
                'image' => UploadedFile::fake()->image('look.jpg', 300, 300),
                'is_featured' => '1',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('admin.testimonials.store'), [
                'client_name' => 'Client One',
                'content' => 'Beautiful work.',
                'rating' => '5',
                'is_featured' => '1',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('admin.social-links.store'), [
                'platform' => 'instagram',
                'label' => 'Instagram',
                'url' => 'https://instagram.com/glamhouse',
                'display_order' => '1',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post(route('admin.availability-blocks.store'), [
                'type' => AvailabilityBlock::TYPE_HOLIDAY,
                'name' => 'Holiday',
                'start_date' => now()->addDays(5)->toDateString(),
                'end_date' => now()->addDays(6)->toDateString(),
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->patch(route('admin.settings.update'), [
                'business_name' => "Esther's Secrets - Glamhouse",
                'business_description' => 'Skincare-first professional makeup artistry in Harare.',
                'business_phone' => '+263700000000',
                'business_email' => 'hello@example.com',
                'business_location' => 'Borrowdale, Harare',
                'business_street_address' => '',
                'business_city' => 'Harare',
                'business_region' => 'Harare',
                'business_postal_code' => '',
                'business_country' => 'ZW',
                'business_country_name' => 'Zimbabwe',
                'business_maps_url' => '',
                'business_latitude' => '',
                'business_longitude' => '',
                'business_opening_hours' => 'Mo-Sa 09:00-18:00',
                'business_price_range' => '$$',
                'business_whatsapp_message' => 'Hello Glamhouse',
                'seo_default_title' => 'Glamhouse Harare',
                'seo_default_description' => 'Professional makeup.',
                'seo_default_keywords' => 'makeup harare',
                'seo_default_image' => '/images/home-hero-fallback.jpg',
                'seo_default_robots' => 'index,follow',
                'social_instagram_url' => 'https://instagram.com/glamhouse',
                'social_facebook_url' => '',
                'social_tiktok_url' => '',
                'social_youtube_url' => '',
                'social_x_url' => '',
                'outcall_travel_buffer_minutes' => '45',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('services', ['name' => 'Bridal Glam', 'price' => 120]);
        $this->assertDatabaseHas('time_slots', ['start_time' => '09:00']);
        $this->assertDatabaseHas('portfolio_items', ['title' => 'Soft bridal look', 'is_featured' => true]);
        $this->assertDatabaseHas('testimonials', ['client_name' => 'Client One']);
        $this->assertDatabaseHas('social_links', ['platform' => 'instagram']);
        $this->assertDatabaseHas('availability_blocks', ['name' => 'Holiday']);
        $this->assertSame('45', Setting::valueFor('outcall_travel_buffer_minutes'));
        $this->assertSame('https://instagram.com/glamhouse', SocialLink::first()->url);
        $this->assertSame('Beautiful work.', Testimonial::first()->content);
    }

    public function test_blocked_dates_disable_availability_and_reject_booking_submission(): void
    {
        Notification::fake();
        $service = $this->service();
        $slot = $this->slot('09:00', '10:00');
        $date = now()->addDays(3)->toDateString();

        AvailabilityBlock::create([
            'type' => AvailabilityBlock::TYPE_UNAVAILABLE_DATE,
            'name' => 'Fully booked',
            'start_date' => $date,
            'is_active' => true,
        ]);

        $this->getJson('/availability?date='.$date)
            ->assertOk()
            ->assertJsonPath('slots.0.id', $slot->id)
            ->assertJsonPath('slots.0.is_available', false)
            ->assertJsonPath('slots.0.reason', 'Fully booked');

        $this->from('/booking')->post('/booking', $this->bookingPayload($service, $slot, $date))
            ->assertRedirect('/booking')
            ->assertSessionHasErrors('appointment_date');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_outcall_travel_buffer_blocks_adjacent_slots(): void
    {
        $service = $this->service();
        $early = $this->slot('09:00', '10:00');
        $outcallSlot = $this->slot('10:00', '11:00');
        $late = $this->slot('11:00', '12:00');
        $date = now()->addDays(4)->toDateString();

        Setting::setMany(['outcall_travel_buffer_minutes' => '60']);

        Booking::create($this->bookingAttributes($service, $outcallSlot, $date, [
            'email' => 'buffer@example.com',
            'status' => 'confirmed',
            'is_outcall' => true,
        ]));

        $this->getJson('/availability?date='.$date)
            ->assertOk()
            ->assertJsonPath('slots.0.id', $early->id)
            ->assertJsonPath('slots.0.is_available', false)
            ->assertJsonPath('slots.0.reason', 'Travel buffer')
            ->assertJsonPath('slots.1.id', $outcallSlot->id)
            ->assertJsonPath('slots.1.reason', 'Booked')
            ->assertJsonPath('slots.2.id', $late->id)
            ->assertJsonPath('slots.2.reason', 'Travel buffer');
    }

    public function test_admin_status_updates_notify_client_and_cancel_frees_slot(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['is_admin' => true]);
        $service = $this->service();
        $slot = $this->slot('09:00', '10:00');
        $booking = Booking::create($this->bookingAttributes($service, $slot, now()->addDays(2)->toDateString()));

        $this->actingAs($admin)
            ->post(route('admin.bookings.status', $booking), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
            'time_slot_id' => $slot->id,
        ]);

        Notification::assertSentTo(
            Booking::find($booking->id),
            BookingStatusUpdatedClientNotification::class
        );

        $this->actingAs($admin)
            ->post(route('admin.bookings.cancel', $booking))
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
            'time_slot_id' => null,
        ]);
    }

    public function test_admin_reschedule_checks_conflicts_and_notifies_client(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['is_admin' => true]);
        $service = $this->service();
        $first = $this->slot('09:00', '10:00');
        $second = $this->slot('10:00', '11:00');
        $date = now()->addDays(2)->toDateString();
        $newDate = now()->addDays(3)->toDateString();

        $booking = Booking::create($this->bookingAttributes($service, $first, $date));
        Booking::create($this->bookingAttributes($service, $second, $date, [
            'email' => 'other@example.com',
            'status' => 'confirmed',
        ]));

        $this->actingAs($admin)
            ->from(route('admin.bookings.show', $booking))
            ->post(route('admin.bookings.reschedule', $booking), [
                'appointment_date' => $date,
                'time_slot_id' => $second->id,
            ])
            ->assertRedirect(route('admin.bookings.show', $booking))
            ->assertSessionHasErrors('time_slot_id');

        $this->actingAs($admin)
            ->post(route('admin.bookings.reschedule', $booking), [
                'appointment_date' => $newDate,
                'time_slot_id' => $second->id,
            ])
            ->assertRedirect();

        $updated = $booking->fresh();

        $this->assertSame($newDate, $updated->appointment_date->toDateString());
        $this->assertSame($second->id, $updated->time_slot_id);

        Notification::assertSentTo(
            Booking::find($booking->id),
            BookingRescheduledClientNotification::class
        );
    }

    private function service(): Service
    {
        return Service::create([
            'name' => 'Soft Glam',
            'price' => 100,
            'description' => 'Soft glam makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);
    }

    private function slot(string $start, string $end): TimeSlot
    {
        return TimeSlot::create([
            'start_time' => $start,
            'end_time' => $end,
            'is_active' => true,
        ]);
    }

    private function bookingPayload(Service $service, TimeSlot $slot, string $date): array
    {
        return [
            'full_name' => 'Client One',
            'phone' => '0770000000',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => $date,
            'time_slot_id' => $slot->id,
            'service_id' => $service->id,
            'event_type' => 'Photoshoot',
            'is_outcall' => '0',
            'has_done_pro_makeup' => '1',
            'deposit_ack' => '1',
            'lateness_ack' => '1',
            'info_confirmed' => '1',
        ];
    }

    private function bookingAttributes(Service $service, TimeSlot $slot, string $date, array $overrides = []): array
    {
        return array_replace([
            'full_name' => 'Client One',
            'phone' => '0770000000',
            'email' => 'client@example.com',
            'location_area' => 'Harare',
            'appointment_date' => $date,
            'time_slot_id' => $slot->id,
            'service_id' => $service->id,
            'event_type' => 'Photoshoot',
            'is_outcall' => false,
            'has_done_pro_makeup' => true,
            'deposit_ack' => true,
            'lateness_ack' => true,
            'info_confirmed' => true,
            'status' => 'pending',
        ], $overrides);
    }
}
