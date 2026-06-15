@extends('layouts.admin')

@section('title', 'Testimonials - Glamhouse Admin')
@section('page_title', 'Testimonials')

@section('page_actions')
    <a href="{{ route('admin.testimonials.create') }}" class="btn-primary text-xs">Add Testimonial</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Search client or content" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <button class="btn-outline text-sm">Search</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Content</th>
                        <th class="px-4 py-3">Rating</th>
                        <th class="px-4 py-3">Featured</th>
                        <th class="px-4 py-3">Active</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-4 font-semibold">{{ $testimonial->client_name }}</td>
                            <td class="px-4 py-4 max-w-xl text-black/70">{{ \Illuminate\Support\Str::limit($testimonial->content, 140) }}</td>
                            <td class="px-4 py-4">{{ $testimonial->rating ?: 'N/A' }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $testimonial->is_featured ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $testimonial->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $testimonial->is_active ? 'bg-sky-100 text-sky-900' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $testimonial->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-black/60">No testimonials yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
            <div class="border-t border-black/10 px-4 py-4">{{ $testimonials->links() }}</div>
        @endif
    </section>
</div>
@endsection
