@extends('layouts.admin')

@section('title', 'Portfolio - Glamhouse Admin')
@section('page_title', 'Portfolio')

@section('page_actions')
    <a href="{{ route('portfolio') }}" class="btn-outline text-xs" target="_blank" rel="noopener">View Public Portfolio</a>
    <a href="{{ route('admin.portfolio-items.create') }}" class="btn-primary text-xs">Add Portfolio Item</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Search title or category" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <button class="btn-outline text-sm">Search</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Featured</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $isLive = $item->is_active && (! $item->published_at || $item->published_at->lte(now()));
                            $isScheduled = $item->is_active && $item->published_at && $item->published_at->isFuture();
                        @endphp
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-3">
                                <img src="{{ $item->imageUrl('thumbnail') }}" alt="" class="h-16 w-24 rounded-xl border border-black/10 object-cover">
                            </td>
                            <td class="px-4 py-3 font-semibold">{{ $item->title ?: 'Untitled look' }}</td>
                            <td class="px-4 py-3">{{ $item->category ?: 'Uncategorized' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_featured ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $item->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $isLive ? 'bg-emerald-100 text-emerald-900' : ($isScheduled ? 'bg-amber-100 text-amber-900' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $isLive ? 'Live' : ($isScheduled ? 'Scheduled' : 'Hidden') }}
                                </span>
                                @if($item->published_at)
                                    <div class="mt-1 text-xs text-black/50">{{ $item->published_at->format('M j, Y g:i A') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.portfolio-items.edit', $item) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-black/60">No portfolio items yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="border-t border-black/10 px-4 py-4">{{ $items->links() }}</div>
        @endif
    </section>
</div>
@endsection
