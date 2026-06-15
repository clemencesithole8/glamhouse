@extends('layouts.admin')

@section('title', 'Edit Social Link - Glamhouse Admin')
@section('page_title', 'Edit Social Link')

@section('content')
<section class="rounded-3xl border border-black/10 bg-white p-6">
    <form method="POST" action="{{ route('admin.social-links.update', $socialLink) }}">
        @method('PUT')
        @include('admin.social-links._form', ['submitLabel' => 'Save Social Link'])
    </form>

    <form method="POST" action="{{ route('admin.social-links.destroy', $socialLink) }}" class="mt-6 border-t border-black/10 pt-5" onsubmit="return confirm('Delete this social link?');">
        @csrf
        @method('DELETE')
        <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">Delete Social Link</button>
    </form>
</section>
@endsection
