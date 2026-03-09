@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Image Manager</h1>
                <p class="text-sm text-gray-600 mt-1">Upload/replace site images by key (no code edits).</p>
            </div>
            <a href="{{ route('admin.media-assets.create') }}"
               class="rounded-full bg-black px-5 py-2 text-white hover:bg-rosegold-600 transition">
                Add Image
            </a>
        </div>

        <div class="mt-6">
            <form method="GET" class="flex gap-3">
                <input name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-gray-300"
                       placeholder="Search by key/title (e.g. home_hero)">
                <button class="rounded-xl border border-gray-300 px-4 py-2">Search</button>
            </form>
        </div>

        <div class="mt-6 bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="text-left p-3">Preview</th>
                        <th class="text-left p-3">Key</th>
                        <th class="text-left p-3">Title</th>
                        <th class="text-left p-3">Active</th>
                        <th class="text-right p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($assets as $a)
                    <tr class="border-t">
                        <td class="p-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk($a->disk)->url($a->path) }}"
                                 class="h-12 w-20 object-cover rounded-lg border" alt="">
                        </td>
                        <td class="p-3 font-mono">{{ $a->key }}</td>
                        <td class="p-3">{{ $a->title ?? '—' }}</td>
                        <td class="p-3">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs {{ $a->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $a->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a href="{{ route('admin.media-assets.edit', $a) }}"
                               class="inline-flex rounded-xl border border-gray-300 px-3 py-2 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-6 text-gray-600" colspan="5">No images yet. Click “Add Image”.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $assets->links() }}</div>

        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-5 text-sm text-gray-700">
            <div class="font-semibold mb-2">Recommended keys (copy/paste):</div>
            <div class="grid md:grid-cols-2 gap-2 font-mono text-xs">
                <div>home_hero</div>
                <div>services_banner</div>
                <div>about_portrait</div>
                <div>about_studio</div>
                <div>picture_perfect_hero</div>
                <div>picture_perfect_set</div>
                <div>portfolio_banner</div>
                <div>policies_banner</div>
                <div>faq_banner</div>
                <div>contact_hero</div>
                <div>contact_1</div>
                <div>contact_2</div>
            </div>
        </div>
    </div>
</div>
@endsection