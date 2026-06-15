@extends('layouts.admin')

@section('title', 'Social Links - Glamhouse Admin')
@section('page_title', 'Social Links')

@section('page_actions')
    <a href="{{ route('admin.social-links.create') }}" class="btn-primary text-xs">Add Social Link</a>
@endsection

@section('content')
<section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Platform</th>
                    <th class="px-4 py-3">URL</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($socialLinks as $link)
                    <tr class="border-t border-black/10">
                        <td class="px-4 py-4">{{ $link->display_order }}</td>
                        <td class="px-4 py-4">
                            <div class="font-semibold">{{ $link->label ?: ucfirst($link->platform) }}</div>
                            <div class="text-xs text-black/55">{{ $link->platform }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="site-link break-all">{{ $link->url }}</a>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $link->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                {{ $link->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.social-links.edit', $link) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-black/60">No social links yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($socialLinks->hasPages())
        <div class="border-t border-black/10 px-4 py-4">{{ $socialLinks->links() }}</div>
    @endif
</section>
@endsection
