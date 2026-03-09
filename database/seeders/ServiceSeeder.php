<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::updateOrCreate(['name' => 'Soft Glam'], [
            'price' => 45,
            'description' => 'Skincare-focused soft glam tailored to your features.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        Service::updateOrCreate(['name' => 'Natural Glam'], [
            'price' => 45,
            'description' => 'Natural, polished glam that enhances your features.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        Service::updateOrCreate(['name' => 'Full Glam'], [
            'price' => 50,
            'description' => 'Full glam with long-lasting performance under real conditions.',
            'is_consultation_based' => false,
            'is_active' => true,
        ]);

        Service::updateOrCreate(['name' => 'Picture Perfect Package'], [
            'price' => null,
            'description' => 'Consultation-based camera-ready makeup for media, film, fashion, ads, and corporate shoots.',
            'is_consultation_based' => true,
            'is_active' => true,
        ]);
    }
}