<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailabilityBlock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminAvailabilityBlockController extends Controller
{
    public function index(Request $request)
    {
        $query = AvailabilityBlock::query()->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        return view('admin.availability-blocks.index', [
            'blocks' => $query->paginate(20)->withQueryString(),
            'types' => AvailabilityBlock::TYPES,
        ]);
    }

    public function create()
    {
        return view('admin.availability-blocks.create', [
            'block' => new AvailabilityBlock([
                'type' => AvailabilityBlock::TYPE_UNAVAILABLE_DATE,
                'is_active' => true,
            ]),
            'types' => AvailabilityBlock::TYPES,
            'weekdays' => AvailabilityBlock::WEEKDAYS,
        ]);
    }

    public function store(Request $request)
    {
        $block = AvailabilityBlock::create($this->validatedData($request));

        return redirect()
            ->route('admin.availability-blocks.edit', $block)
            ->with('success', 'Availability block created.');
    }

    public function edit(AvailabilityBlock $availabilityBlock)
    {
        return view('admin.availability-blocks.edit', [
            'block' => $availabilityBlock,
            'types' => AvailabilityBlock::TYPES,
            'weekdays' => AvailabilityBlock::WEEKDAYS,
        ]);
    }

    public function update(Request $request, AvailabilityBlock $availabilityBlock)
    {
        $availabilityBlock->update($this->validatedData($request));

        return back()->with('success', 'Availability block updated.');
    }

    public function destroy(AvailabilityBlock $availabilityBlock)
    {
        $availabilityBlock->delete();

        return redirect()
            ->route('admin.availability-blocks.index')
            ->with('success', 'Availability block deleted.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(AvailabilityBlock::TYPES))],
            'name' => ['required','string','max:255'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'day_of_week' => ['nullable','integer','min:0','max:6'],
            'notes' => ['nullable','string','max:4000'],
            'is_active' => ['required','boolean'],
        ]);

        if ($data['type'] === AvailabilityBlock::TYPE_BLOCKED_DAY) {
            $request->validate([
                'day_of_week' => ['required','integer','min:0','max:6'],
            ]);

            $data['start_date'] = null;
            $data['end_date'] = null;
        } else {
            $request->validate([
                'start_date' => ['required','date'],
            ]);

            $data['day_of_week'] = null;
        }

        return $data;
    }
}
