<?php
namespace App\Http\Controllers;

use App\Services\BookingAvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request, BookingAvailabilityService $availability)
    {
        $data = $request->validate([
            'date' => ['required','date','after_or_equal:today'],
            'is_outcall' => ['nullable','boolean'],
        ]);

        $date = $data['date'];

        return response()->json([
            'date' => $date,
            'slots' => $availability->slotsForDate($date, $request->boolean('is_outcall')),
        ]);
    }
}
