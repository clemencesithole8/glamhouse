@extends('layouts.admin')

@section('title', 'Edit Testimonial - Glamhouse Admin')
@section('page_title', 'Edit Testimonial')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
        @method('PUT')
        @include('admin.testimonials._form', ['submitLabel' => 'Save Testimonial'])
    </form>

    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="mt-6 border-t border-black/10 pt-5" onsubmit="return confirm('Delete this testimonial?');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">Delete Testimonial</button>
    </form>
</section>
@endsection
