<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class AdminTimeSlotController extends Controller
{
    public function index()
    {
        return view('admin.time-slots.index', [
            'timeSlots' => TimeSlot::query()->orderBy('start_time')->paginate(30),
        ]);
    }

    public function create()
    {
        return view('admin.time-slots.create', [
            'timeSlot' => new TimeSlot(['is_active' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $slot = TimeSlot::create($this->validatedData($request));

        return redirect()
            ->route('admin.time-slots.edit', $slot)
            ->with('success', 'Time slot created.');
    }

    public function edit(TimeSlot $timeSlot)
    {
        return view('admin.time-slots.edit', [
            'timeSlot' => $timeSlot,
        ]);
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $timeSlot->update($this->validatedData($request));

        return back()->with('success', 'Time slot updated.');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();

        return redirect()
            ->route('admin.time-slots.index')
            ->with('success', 'Time slot deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'is_active' => ['required','boolean'],
        ]);
    }
}
