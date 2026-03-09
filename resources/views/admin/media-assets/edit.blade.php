@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold">Edit Image Asset</h1>
        <p class="text-sm text-gray-600 mt-1">Key: <span class="font-mono">{{ $asset->key }}</span></p>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6">
            <div class="mb-4">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk($asset->disk)->url($asset->path) }}"
                     class="w-full max-h-[360px] object-cover rounded-2xl border" alt="">
            </div>

            <form method="POST" action="{{ route('admin.media-assets.update', $asset) }}" enctype="multipart/form-data"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-medium">Key</label>
                    <input name="key" value="{{ old('key', $asset->key) }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                    @error('key')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Title</label>
                        <input name="title" value="{{ old('title', $asset->title) }}" class="mt-1 w-full rounded-xl border-gray-300">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Alt text</label>
                        <input name="alt" value="{{ old('alt', $asset->alt) }}" class="mt-1 w-full rounded-xl border-gray-300">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium">Active</label>
                    <select name="is_active" class="mt-1 w-full rounded-xl border-gray-300">
                        <option value="1" @selected(old('is_active', $asset->is_active) == 1)>Yes</option>
                        <option value="0" @selected(old('is_active', $asset->is_active) == 0)>No</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Replace image (optional)</label>
                    <input type="file" name="image" class="mt-2 w-full">
                    @error('image')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="flex items-center justify-between gap-3">
                    <div class="flex gap-3">
                        <button class="rounded-full bg-black px-6 py-2 text-white hover:bg-rosegold-600 transition">
                            Update
                        </button>
                        <a href="{{ route('admin.media-assets.index') }}"
                           class="rounded-full border border-gray-300 px-6 py-2 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                            Back
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.media-assets.destroy', $asset) }}"
                          onsubmit="return confirm('Delete this image asset? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-full border border-red-300 px-6 py-2 text-red-700 hover:bg-red-50 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection