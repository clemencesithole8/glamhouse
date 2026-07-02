@csrf
@php
    $publishAtValue = old(
        'published_at',
        optional($item->published_at)->format('Y-m-d\TH:i') ?? (! $item->exists ? now()->format('Y-m-d\TH:i') : null)
    );
@endphp

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
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-image-preview="#portfolio-main-preview" {{ ! $item->exists ? 'required' : '' }} class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
        @if($item->image_path)
            <img id="portfolio-main-preview" src="{{ $item->imageUrl('thumbnail') }}" alt="Main image preview" class="mt-3 h-32 w-48 rounded-xl border border-black/10 object-cover">
        @else
            <img id="portfolio-main-preview" src="" alt="Main image preview" class="mt-3 hidden h-32 w-48 rounded-xl border border-black/10 object-cover">
        @endif
        <p class="mt-2 text-xs text-black/55">Accepted: JPG, PNG, WEBP. Portrait crops work best for the public gallery.</p>
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
        <input type="datetime-local" name="published_at" value="{{ $publishAtValue }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
        <p class="mt-2 text-xs text-black/55">New items publish immediately by default. Use a future time only when scheduling.</p>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Before Image</label>
        <input type="file" name="before_image" accept="image/jpeg,image/png,image/webp" data-image-preview="#portfolio-before-preview" class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
        @if($item->before_image_path)
            <img id="portfolio-before-preview" src="{{ asset('storage/'.$item->before_image_path) }}" alt="Before image preview" class="mt-3 h-28 w-40 rounded-xl border border-black/10 object-cover">
        @else
            <img id="portfolio-before-preview" src="" alt="Before image preview" class="mt-3 hidden h-28 w-40 rounded-xl border border-black/10 object-cover">
        @endif
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">After Image</label>
        <input type="file" name="after_image" accept="image/jpeg,image/png,image/webp" data-image-preview="#portfolio-after-preview" class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
        @if($item->after_image_path)
            <img id="portfolio-after-preview" src="{{ asset('storage/'.$item->after_image_path) }}" alt="After image preview" class="mt-3 h-28 w-40 rounded-xl border border-black/10 object-cover">
        @else
            <img id="portfolio-after-preview" src="" alt="After image preview" class="mt-3 hidden h-28 w-40 rounded-xl border border-black/10 object-cover">
        @endif
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-3">
    <button class="btn-primary text-sm">{{ $submitLabel }}</button>
    <a href="{{ route('admin.portfolio-items.index') }}" class="btn-outline text-sm">Back</a>
</div>
