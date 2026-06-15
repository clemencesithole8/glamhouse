@extends('layouts.admin')

@section('title', 'Edit Time Slot - Glamhouse Admin')
@section('page_title', 'Edit Time Slot')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.time-slots.update', $timeSlot) }}">
        @method('PUT')
        @include('admin.time-slots._form', ['submitLabel' => 'Save Slot'])
    </form>

    <form method="POST" action="{{ route('admin.time-slots.destroy', $timeSlot) }}" class="mt-6 border-t border-black/10 pt-5" onsubmit="return confirm('Delete this time slot?');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">Delete Slot</button>
    </form>
</section>
@endsection
