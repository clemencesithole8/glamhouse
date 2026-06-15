@extends('layouts.admin')

@section('title', 'Add Social Link - Glamhouse Admin')
@section('page_title', 'Add Social Link')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.social-links.store') }}">
        @include('admin.social-links._form', ['submitLabel' => 'Create Social Link'])
    </form>
</section>
@endsection
