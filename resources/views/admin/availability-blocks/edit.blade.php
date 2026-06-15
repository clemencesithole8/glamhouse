@extends('layouts.admin')

@section('title', 'Edit Availability Block - Glamhouse Admin')
@section('page_title', 'Edit Availability Block')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.availability-blocks.update', $block) }}">
        @method('PUT')
        @include('admin.availability-blocks._form', ['submitLabel' => 'Save Block'])
    </form>

    <form method="POST" action="{{ route('admin.availability-blocks.destroy', $block) }}" class="mt-6 border-t border-black/10 pt-5" onsubmit="return confirm('Delete this availability block?');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">Delete Block</button>
    </form>
</section>
@endsection
