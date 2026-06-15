@extends('layouts.admin')

@section('title', 'Add Service - Glamhouse Admin')
@section('page_title', 'Add Service')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.services.store') }}">
        @include('admin.services._form', ['submitLabel' => 'Create Service'])
    </form>
</section>
@endsection
