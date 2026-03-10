<?php

return [
    'site_name' => env('SEO_SITE_NAME', "Esther's Secrets - Glamhouse"),

    'default' => [
        'title' => "Esther's Secrets - Glamhouse | Professional Makeup Artist in Harare",
        'description' => 'Skincare-first makeup artistry in Harare for bridal, events, corporate, and camera-ready looks.',
        'keywords' => 'makeup artist Harare, glam makeup Zimbabwe, bridal makeup Harare, professional makeup services',
        'image' => env('SEO_DEFAULT_IMAGE', '/images/home-hero-fallback.jpg'),
        'type' => 'website',
        'robots' => env('SEO_DEFAULT_ROBOTS', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'),
    ],

    'pages' => [
        'home' => [
            'title' => "Esther's Secrets - Glamhouse | Luxury Makeup in Harare",
            'description' => 'Professional makeup artist in Harare offering soft glam, natural glam, full glam, and picture-perfect looks.',
            'keywords' => 'Harare makeup artist, soft glam Harare, full glam Zimbabwe, event makeup',
        ],
        'about' => [
            'title' => 'About Sibonginkosi Dube | Esther\'s Secrets - Glamhouse',
            'description' => 'Meet Sibonginkosi Dube, a skincare-focused professional makeup artist based in Harare, Zimbabwe.',
            'keywords' => 'Sibonginkosi Dube, about Esther Secrets Glamhouse, professional makeup artist bio',
        ],
        'services' => [
            'title' => 'Makeup Services and Packages | Esther\'s Secrets - Glamhouse',
            'description' => 'Explore professional makeup packages for events, media, and custom consultations in Harare.',
            'keywords' => 'makeup packages Harare, makeup consultation Zimbabwe, professional makeup prices',
        ],
        'picturePerfect' => [
            'title' => 'Picture Perfect Package | Esther\'s Secrets - Glamhouse',
            'description' => 'Camera-ready consultation-based makeup for media, TV, film, editorial, and brand productions.',
            'keywords' => 'camera ready makeup, editorial makeup Harare, production makeup Zimbabwe',
        ],
        'portfolio' => [
            'title' => 'Portfolio | Esther\'s Secrets - Glamhouse',
            'description' => 'View curated soft glam, natural glam, and full glam makeup transformations.',
            'keywords' => 'makeup portfolio Harare, glam transformations, makeup gallery',
        ],
        'contact' => [
            'title' => 'Contact | Esther\'s Secrets - Glamhouse',
            'description' => 'Contact Esther\'s Secrets - Glamhouse for bookings, enquiries, and collaboration requests.',
            'keywords' => 'contact makeup artist Harare, book makeup appointment Zimbabwe',
        ],
        'booking.create' => [
            'title' => 'Book Appointment | Esther\'s Secrets - Glamhouse',
            'description' => 'Book your professional makeup appointment with date, service, and session preferences.',
            'keywords' => 'book makeup appointment Harare, schedule glam session',
            'robots' => 'noindex,follow',
        ],
        'faq' => [
            'title' => 'FAQ | Esther\'s Secrets - Glamhouse',
            'description' => 'Frequently asked questions about bookings, deposits, outcalls, and makeup preparation.',
            'keywords' => 'makeup FAQ Harare, booking questions, glamhouse policies',
        ],
        'policies' => [
            'title' => 'Policies | Esther\'s Secrets - Glamhouse',
            'description' => 'Read booking, payment, lateness, and cancellation policies for Esther\'s Secrets - Glamhouse.',
            'keywords' => 'makeup booking policies, glamhouse terms, deposit policy',
        ],
    ],

    'social' => [
        'twitter_card' => env('SEO_TWITTER_CARD', 'summary_large_image'),
        'twitter_site' => env('SEO_TWITTER_SITE', ''),
        'facebook_app_id' => env('SEO_FACEBOOK_APP_ID', ''),
    ],

    'google' => [
        'site_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    ],

    'business' => [
        'name' => env('BUSINESS_NAME', "Esther's Secrets - Glamhouse"),
        'description' => env('BUSINESS_DESCRIPTION', 'Skincare-first professional makeup artistry for events, media, and corporate clients in Harare.'),
        'phone' => env('BUSINESS_PHONE', '+263784721479'),
        'email' => env('BUSINESS_EMAIL', ''),
        'street_address' => env('BUSINESS_STREET_ADDRESS', ''),
        'locality' => env('BUSINESS_CITY', 'Harare'),
        'region' => env('BUSINESS_REGION', 'Harare'),
        'postal_code' => env('BUSINESS_POSTAL_CODE', ''),
        'country' => env('BUSINESS_COUNTRY', 'ZW'),
    ],
];
