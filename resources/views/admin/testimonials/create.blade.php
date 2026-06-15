@extends('layouts.admin')

@section('title', 'Add Testimonial - Glamhouse Admin')
@section('page_title', 'Add Testimonial')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.testimonials.store') }}">
        @include('admin.testimonials._form', ['submitLabel' => 'Create Testimonial'])
    </form>
</section>
@endsection
