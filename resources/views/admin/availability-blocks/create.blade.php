@extends('layouts.admin')

@section('title', 'Add Availability Block - Glamhouse Admin')
@section('page_title', 'Add Availability Block')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.availability-blocks.store') }}">
        @include('admin.availability-blocks._form', ['submitLabel' => 'Create Block'])
    </form>
</section>
@endsection
