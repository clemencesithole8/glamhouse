@extends('layouts.admin')

@section('title', 'Edit Portfolio Item - Glamhouse Admin')
@section('page_title', 'Edit Portfolio Item')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.portfolio-items.update', $item) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.portfolio-items._form', ['submitLabel' => 'Save Portfolio Item'])
    </form>

    <form method="POST" action="{{ route('admin.portfolio-items.destroy', $item) }}" class="mt-6 border-t border-black/10 pt-5" onsubmit="return confirm('Delete this portfolio item?');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">Delete Portfolio Item</button>
    </form>
</section>
@endsection
