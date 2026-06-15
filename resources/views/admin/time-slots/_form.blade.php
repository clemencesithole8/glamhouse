@csrf

<div class="grid gap-4 md:grid-cols-3">
    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Start Time</label>
        <input type="time" name="start_time" value="{{ old('start_time', $timeSlot->start_time ? \Illuminate\Support\Str::of($timeSlot->start_time)->substr(0, 5) : '') }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">End Time</label>
        <input type="time" name="end_time" value="{{ old('end_time', $timeSlot->end_time ? \Illuminate\Support\Str::of($timeSlot->end_time)->substr(0, 5) : '') }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Active</label>
        <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_active', (int) $timeSlot->is_active) == 1)>Yes</option>
            <option value="0" @selected(old('is_active', (int) $timeSlot->is_active) == 0)>No</option>
        </select>
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.time-slots.index') }}" class="btn-outline text-sm">Back</a>
</div>
