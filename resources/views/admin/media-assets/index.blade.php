@extends('layouts.admin')

@section('title', 'Media Assets - Glamhouse Admin')
@section('page_title', 'Media Assets')

@section('page_actions')
    <a href="{{ route('admin.media-assets.create') }}" class="btn-primary text-xs">Add Image</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
            <input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by key or title (e.g. home_hero)"
                class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500"
            >
            <button class="btn-outline text-sm">Search</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Preview</th>
                        <th class="px-4 py-3">Key</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Active</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($assets as $a)
                    <tr class="border-t border-black/10">
                        <td class="px-4 py-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk($a->disk)->url($a->path) }}" class="h-14 w-24 rounded-xl border border-black/10 object-cover" alt="">
                        </td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $a->key }}</td>
                        <td class="px-4 py-3">{{ $a->title ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $a->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                {{ $a->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.media-assets.edit', $a) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-8 text-center text-sm text-black/60" colspan="5">No images yet. Click Add Image to start.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($assets->hasPages())
            <div class="border-t border-black/10 px-4 py-4">{{ $assets->links() }}</div>
        @endif
    </section>

    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <div class="mb-3 text-sm font-semibold">Suggested keys</div>
        <div class="grid gap-2 text-xs font-mono text-black/75 md:grid-cols-2 lg:grid-cols-3">
            <div>home_hero</div>
            <div>home_hero_slide_1</div>
            <div>home_hero_slide_2</div>
            <div>home_hero_slide_3</div>
            <div>home_feature_1</div>
            <div>home_feature_2</div>
            <div>home_feature_3</div>
            <div>home_about_image</div>
            <div>home_cta_image</div>
            <div>about_portrait</div>
            <div>about_studio</div>
            <div>services_banner</div>
            <div>portfolio_banner</div>
            <div>contact_hero</div>
            <div>faq_banner</div>
        </div>
    </section>
</div>
@endsection
