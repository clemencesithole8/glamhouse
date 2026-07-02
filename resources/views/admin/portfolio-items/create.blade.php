@extends('layouts.admin')

@section('title', 'Add Portfolio Item - Glamhouse Admin')
@section('page_title', 'Add Portfolio Item')

@section('page_actions')
    <a href="{{ route('portfolio') }}" class="btn-outline text-xs" target="_blank" rel="noopener">View Public Portfolio</a>
@endsection

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.portfolio-items.store') }}" enctype="multipart/form-data">
        @include('admin.portfolio-items._form', ['submitLabel' => 'Publish Portfolio Item'])
    </form>
</section>
@endsection
