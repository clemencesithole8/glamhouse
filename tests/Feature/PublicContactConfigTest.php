<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContactConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_contact_surfaces_follow_the_shared_business_profile(): void
    {
        config([
            'seo.site_name' => 'Future Face Studio',
            'seo.default' => [
                'title' => 'Future Face Studio',
                'description' => 'Config driven public contact profile.',
                'keywords' => 'makeup',
                'image' => '/images/home-hero-fallback.jpg',
                'type' => 'website',
                'robots' => 'index,follow',
            ],
            'seo.pages.home' => [
                'title' => 'Future Face Studio',
                'description' => 'Config driven public contact profile.',
                'keywords' => 'makeup',
            ],
            'seo.pages.contact' => [
                'title' => 'Contact Future Face Studio',
                'description' => 'Config driven public contact profile.',
                'keywords' => 'contact',
            ],
            'seo.business' => [
                'name' => 'Future Face Studio',
                'description' => 'Config driven public contact profile.',
                'phone' => '+263 77 123 4567',
                'email' => 'hello@future.test',
                'street_address' => '12 Example Road',
                'locality' => 'Bulawayo',
                'region' => 'Bulawayo',
                'postal_code' => '',
                'country' => 'ZW',
                'country_name' => 'Zimbabwe',
                'service_mode' => 'Studio Visits + Travel',
                'service_location_label' => 'Bulawayo suite + travel',
                'maps_query' => 'Future Face Studio, 12 Example Road, Bulawayo',
                'whatsapp_prefill' => 'Hello Future Face Studio, I found you on Google.',
            ],
        ]);

        foreach (['/', '/contact', '/booking'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('Bulawayo, Zimbabwe')
                ->assertSee('Studio Visits + Travel')
                ->assertSee('https://wa.me/263771234567', false)
                ->assertDontSee('+263784721479', false)
                ->assertDontSee('esther2026@gmail.com', false);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('A Bulawayo makeup experience')
            ->assertSee('Bulawayo Bookings Open')
            ->assertDontSee('A Harare makeup experience')
            ->assertDontSee('Harare Bookings Open');

        $this->get('/contact')
            ->assertOk()
            ->assertSee('Plan Your Bulawayo Glam')
            ->assertSee('hello@future.test')
            ->assertSee(rawurlencode('Future Face Studio, 12 Example Road, Bulawayo'), false)
            ->assertDontSee('Plan Your Harare Glam')
            ->assertDontSee('Esther%27s%20Secrets%20Glamhouse%20Harare%20Zimbabwe', false);

        $this->get('/booking')
            ->assertOk()
            ->assertSee('Bulawayo suite + travel')
            ->assertSee('hello@future.test')
            ->assertDontSee('Harare studio + outcall');
    }
}
