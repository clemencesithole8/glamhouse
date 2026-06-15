@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Name</label>
        <input name="name" value="{{ old('name', $service->name) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Price</label>
        <input type="number" name="price" min="0" value="{{ old('price', $service->price) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Leave empty for consult">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Consultation Based</label>
        <select name="is_consultation_based" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="0" @selected(old('is_consultation_based', (int) $service->is_consultation_based) == 0)>No</option>
            <option value="1" @selected(old('is_consultation_based', (int) $service->is_consultation_based) == 1)>Yes</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Active</label>
        <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_active', (int) $service->is_active) == 1)>Yes</option>
            <option value="0" @selected(old('is_active', (int) $service->is_active) == 0)>No</option>
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Description</label>
        <textarea name="description" rows="5" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">{{ old('description', $service->description) }}</textarea>
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.services.index') }}" class="btn-outline text-sm">Back</a>
</div>
