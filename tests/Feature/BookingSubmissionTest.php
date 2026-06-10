<?php

namespace Tests\Feature;

use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $service = Service::create([
            'name' => 'Soft Glam',
            'price' => 100,
            'description' => 'Soft glam makeup.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        $response = $this->from('/booking')->post('/booking', [
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
        ]);

        $response->assertRedirect();

        $location = $response->headers->get('Location');

        $this->assertStringContainsString('/booking/1/pdf', $location);
        $this->assertStringContainsString('signature=', $location);
        $this->get($location)->assertOk();
    }
}
