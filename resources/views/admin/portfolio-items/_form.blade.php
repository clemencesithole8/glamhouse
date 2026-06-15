@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Title</label>
        <input name="title" value="{{ old('title', $item->title) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Category</label>
        <input name="category" value="{{ old('category', $item->category) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Soft glam, bridal, editorial...">
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Alt Text</label>
        <input name="alt_text" value="{{ old('alt_text', $item->alt_text) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Describe the look for accessibility and SEO">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Main Image</label>
        <input type="file" name="image" @required(! $item->exists) class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
        @if($item->image_path)
            <img src="{{ $item->imageUrl('thumbnail') }}" alt="" class="mt-3 h-32 w-48 rounded-xl border border-black/10 object-cover">
        @endif
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Featured</label>
        <select name="is_featured" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="0" @selected(old('is_featured', (int) $item->is_featured) == 0)>No</option>
            <option value="1" @selected(old('is_featured', (int) $item->is_featured) == 1)>Yes</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Active</label>
        <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <option value="1" @selected(old('is_active', $item->exists ? (int) $item->is_active : 1) == 1)>Yes</option>
            <option value="0" @selected(old('is_active', $item->exists ? (int) $item->is_active : 1) == 0)>No</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Display Order</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Publish At</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($item->published_at)->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Before Image</label>
        <input type="file" name="before_image" class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
        @if($item->before_image_path)
            <img src="{{ asset('storage/'.$item->before_image_path) }}" alt="" class="mt-3 h-28 w-40 rounded-xl border border-black/10 object-cover">
        @endif
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">After Image</label>
        <input type="file" name="after_image" class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
        @if($item->after_image_path)
            <img src="{{ asset('storage/'.$item->after_image_path) }}" alt="" class="mt-3 h-28 w-40 rounded-xl border border-black/10 object-cover">
        @endif
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.portfolio-items.index') }}" class="btn-outline text-sm">Back</a>
</div>
