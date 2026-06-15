@extends('layouts.admin')

@section('title', 'Availability Blocks - Glamhouse Admin')
@section('page_title', 'Availability Blocks')

@section('page_actions')
    <a href="{{ route('admin.availability-blocks.create') }}" class="btn-primary text-xs">Add Block</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
            <select name="type" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                <option value="">All block types</option>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn-outline text-sm">Filter</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Schedule</th>
                        <th class="px-4 py-3">Active</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blocks as $block)
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-4">
                                <div class="font-semibold">{{ $block->name }}</div>
                                @if($block->notes)
                                    <div class="mt-1 text-xs text-black/60">{{ \Illuminate\Support\Str::limit($block->notes, 120) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4">{{ $block->type_label }}</td>
                            <td class="px-4 py-4">{{ $block->schedule_label }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $block->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $block->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.availability-blocks.edit', $block) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-black/60">No availability blocks yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($blocks->hasPages())
            <div class="border-t border-black/10 px-4 py-4">{{ $blocks->links() }}</div>
        @endif
    </section>
</div>
@endsection
