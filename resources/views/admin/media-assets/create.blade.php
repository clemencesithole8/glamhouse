@extends('layouts.admin')

@section('title', 'Add Media Asset - Glamhouse Admin')
@section('page_title', 'Add Media Asset')

@section('page_actions')
    <a href="{{ route('admin.media-assets.index') }}" class="btn-outline text-xs">Back to Library</a>
@endsection

@section('content')
<section class="mx-auto max-w-3xl rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.media-assets.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Key</label>
            <input name="key" value="{{ old('key') }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="e.g. home_hero" required>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Title</label>
                <input name="title" value="{{ old('title') }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Alt Text</label>
                <input name="alt" value="{{ old('alt') }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
        </div>

        <div>
            <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Active</label>
            <select name="is_active" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <label class="mb-1 block text-xs uppercase tracking-[0.14em] text-black/55">Image</label>
            <input type="file" name="image" class="w-full rounded-xl border border-black/15 px-3 py-2 text-sm" required>
            <p class="mt-2 text-xs text-black/55">Accepted: JPG, PNG, WEBP. Maximum file size: 8MB.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <button class="btn-primary text-sm">Save Asset</button>
            <a href="{{ route('admin.media-assets.index') }}" class="btn-outline text-sm">Cancel</a>
        </div>
    </form>
</section>
@endsection
