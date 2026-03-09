<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->validate([
            'date' => ['required','date','after_or_equal:today'],
        ])['date'];

        $slots = TimeSlot::where('is_active', true)->orderBy('start_time')->get();

        $reservedSlotIds = Booking::query()
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending','confirmed'])
            ->whereNotNull('time_slot_id')
            ->pluck('time_slot_id')
            ->all();

        $data = $slots->map(function ($slot) use ($reservedSlotIds) {
            return [
                'id' => $slot->id,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'is_available' => !in_array($slot->id, $reservedSlotIds, true),
            ];
        });

        return response()->json([
            'date' => $date,
            'slots' => $data,
        ]);
    }
}
