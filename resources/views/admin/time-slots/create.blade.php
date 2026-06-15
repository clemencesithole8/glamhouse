@extends('layouts.admin')

@section('title', 'Add Time Slot - Glamhouse Admin')
@section('page_title', 'Add Time Slot')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.time-slots.store') }}">
        @include('admin.time-slots._form', ['submitLabel' => 'Create Slot'])
    </form>
</section>
@endsection
