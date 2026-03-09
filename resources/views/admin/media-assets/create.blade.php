@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Add Image Asset</h1>

        <form method="POST" action="{{ route('admin.media-assets.store') }}" enctype="multipart/form-data"
              class="mt-6 bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            @csrf

            <div>
                <label class="text-sm font-medium">Key (required)</label>
                <input name="key" value="{{ old('key') }}" class="mt-1 w-full rounded-xl border-gray-300"
                       placeholder="e.g. home_hero" required>
                @error('key')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Title</label>
                    <input name="title" value="{{ old('title') }}" class="mt-1 w-full rounded-xl border-gray-300">
                </div>
                <div>
                    <label class="text-sm font-medium">Alt text</label>
                    <input name="alt" value="{{ old('alt') }}" class="mt-1 w-full rounded-xl border-gray-300">
                </div>
            </div>

            <div>
                <label class="text-sm font-medium">Active</label>
                <select name="is_active" class="mt-1 w-full rounded-xl border-gray-300">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Image (required)</label>
                <input type="file" name="image" class="mt-2 w-full" required>
                @error('image')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="flex gap-3">
                <button class="rounded-full bg-black px-6 py-2 text-white hover:bg-rosegold-600 transition">
                    Save
                </button>
                <a href="{{ route('admin.media-assets.index') }}"
                   class="rounded-full border border-gray-300 px-6 py-2 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection