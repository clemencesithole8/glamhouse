@extends('layouts.admin')

@section('title', 'Edit Service - Glamhouse Admin')
@section('page_title', 'Edit Service')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.services.update', $service) }}">
        @method('PUT')
        @include('admin.services._form', ['submitLabel' => 'Save Service'])
    </form>

    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="mt-6 border-t border-black/10 pt-5" onsubmit="return confirm('Delete this service? Existing bookings may depend on it.');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">Delete Service</button>
    </form>
</section>
@endsection
