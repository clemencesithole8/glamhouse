@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Type</label>
        <select name="type" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            @foreach($types as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $block->type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Name</label>
        <input name="name" value="{{ old('name', $block->name) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Holiday, fully booked, Monday closure">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $block->start_date?->toDateString()) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', $block->end_date?->toDateString()) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Blocked Weekday</label>
        <select name="day_of_week" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="">Only for blocked weekday</option>
            @foreach($weekdays as $value => $label)
                <option value="{{ $value }}" @selected((string) old('day_of_week', $block->day_of_week) === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Active</label>
        <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_active', (int) $block->is_active) == 1)>Yes</option>
            <option value="0" @selected(old('is_active', (int) $block->is_active) == 0)>No</option>
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Notes</label>
        <textarea name="notes" rows="4" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">{{ old('notes', $block->notes) }}</textarea>
    </div>
</div>

<div class="mt-5 rounded-2xl border border-black/10 bg-[#fbf7f3] px-4 py-3 text-xs text-black/60">
    Date and holiday blocks use Start Date and optional End Date. Blocked weekdays use Blocked Weekday and ignore date fields.
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.availability-blocks.index') }}" class="btn-outline text-sm">Back</a>
</div>
