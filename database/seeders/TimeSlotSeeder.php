<?php
namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            ['09:00:00','10:30:00'],
            ['11:00:00','12:30:00'],
            ['13:00:00','14:30:00'],
            ['15:00:00','16:30:00'],
        ];

        foreach ($slots as [$start,$end]) {
            TimeSlot::updateOrCreate(
                ['start_time' => $start, 'end_time' => $end],
                ['is_active' => true]
            );
        }
    }
}