@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Client Name</label>
        <input name="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Rating</label>
        <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Optional 1-5">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Client Role / Context</label>
        <input name="client_role" value="{{ old('client_role', $testimonial->client_role) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Bride, photoshoot client, corporate client">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Source</label>
        <input name="source" value="{{ old('source', $testimonial->source) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Google, WhatsApp, Instagram">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Featured</label>
        <select name="is_featured" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_featured', (int) $testimonial->is_featured) == 1)>Yes</option>
            <option value="0" @selected(old('is_featured', (int) $testimonial->is_featured) == 0)>No</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Active</label>
        <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_active', $testimonial->exists ? (int) $testimonial->is_active : 1) == 1)>Yes</option>
            <option value="0" @selected(old('is_active', $testimonial->exists ? (int) $testimonial->is_active : 1) == 0)>No</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Display Order</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Publish At</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($testimonial->published_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Content</label>
        <textarea name="content" rows="6" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">{{ old('content', $testimonial->content) }}</textarea>
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.testimonials.index') }}" class="btn-outline text-sm">Back</a>
</div>
