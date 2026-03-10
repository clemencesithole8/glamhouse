@extends('layouts.admin')

@section('title', 'Edit Media Asset - Glamhouse Admin')
@section('page_title', 'Edit Media Asset')

@section('page_actions')
    <a href="{{ route('admin.media-assets.index') }}" class="btn-outline text-xs">Back to Library</a>
@endsection

@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <div class="mb-4">
            <div class="text-xs uppercase tracking-[0.14em] text-black/55">Asset Key</div>
            <div class="mt-1 font-mono text-sm">{{ $asset->key }}</div>
        </div>

        <img src="{{ \Illuminate\Support\Facades\Storage::disk($asset->disk)->url($asset->path) }}" class="max-h-[420px] w-full rounded-2xl border border-black/10 object-cover" alt="Current asset image">
    </section>

    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <form method="POST" action="{{ route('admin.media-assets.update', $asset) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Key</label>
                <input name="key" value="{{ old('key', $asset->key) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" required>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Title</label>
                    <input name="title" value="{{ old('title', $asset->title) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                </div>
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Alt Text</label>
                    <input name="alt" value="{{ old('alt', $asset->alt) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Active</label>
                <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                    <option value="1" @selected(old('is_active', $asset->is_active) == 1)>Yes</option>
                    <option value="0" @selected(old('is_active', $asset->is_active) == 0)>No</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Replace Image (Optional)</label>
                <input type="file" name="image" class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm">
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button class="btn-primary text-sm">Update Asset</button>
                <a href="{{ route('admin.media-assets.index') }}" class="btn-outline text-sm">Cancel</a>
            </div>
        </form>
    </section>

    <section class="rounded-3xl border border-rose-200 bg-rose-50 p-6">
        <h3 class="font-semibold text-rose-900">Danger Zone</h3>
        <p class="mt-1 text-sm text-rose-800">Deleting this image removes it from storage and any section using this key will fall back to default image.</p>

        <form method="POST" action="{{ route('admin.media-assets.destroy', $asset) }}" class="mt-4" onsubmit="return confirm('Delete this image asset? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button class="rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-100">
                Delete Asset
            </button>
        </form>
    </section>
</div>
@endsection
