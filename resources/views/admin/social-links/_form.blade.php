@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Platform</label>
        <input name="platform" value="{{ old('platform', $socialLink->platform) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="instagram, tiktok, facebook">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Label</label>
        <input name="label" value="{{ old('label', $socialLink->label) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Instagram">
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">URL</label>
        <input type="url" name="url" value="{{ old('url', $socialLink->url) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Display Order</label>
        <input type="number" name="display_order" min="0" value="{{ old('display_order', $socialLink->display_order ?? 0) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Active</label>
        <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_active', (int) $socialLink->is_active) == 1)>Yes</option>
            <option value="0" @selected(old('is_active', (int) $socialLink->is_active) == 0)>No</option>
        </select>
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.social-links.index') }}" class="btn-outline text-sm">Back</a>
</div>
