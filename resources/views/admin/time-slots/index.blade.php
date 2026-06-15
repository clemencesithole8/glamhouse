@extends('layouts.admin')

@section('title', 'Time Slots - Glamhouse Admin')
@section('page_title', 'Time Slots')

@section('page_actions')
    <a href="{{ route('admin.time-slots.create') }}" class="btn-primary text-xs">Add Slot</a>
@endsection

@section('content')
<section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                <tr>
                    <th class="px-4 py-3">Start</th>
                    <th class="px-4 py-3">End</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($timeSlots as $slot)
                    <tr class="border-t border-black/10">
                        <td class="px-4 py-4 font-semibold">{{ \Illuminate\Support\Str::of($slot->start_time)->substr(0, 5) }}</td>
                        <td class="px-4 py-4">{{ \Illuminate\Support\Str::of($slot->end_time)->substr(0, 5) }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $slot->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                {{ $slot->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.time-slots.edit', $slot) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-sm text-black/60">No time slots yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($timeSlots->hasPages())
        <div class="border-t border-black/10 px-4 py-4">{{ $timeSlots->links() }}</div>
    @endif
</section>
@endsection
