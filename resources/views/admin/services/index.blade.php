@extends('layouts.admin')

@section('title', 'Services & Prices - Glamhouse Admin')
@section('page_title', 'Services & Prices')

@section('page_actions')
    <a href="{{ route('admin.services.create') }}" class="btn-primary text-xs">Add Service</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Search service or description" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <button class="btn-outline text-sm">Search</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Service</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Active</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-4">
                                <div class="font-semibold">{{ $service->name }}</div>
                                <div class="mt-1 max-w-xl text-xs text-black/60">{{ $service->description ?: 'No description set.' }}</div>
                            </td>
                            <td class="px-4 py-4 font-semibold">{{ $service->price !== null ? '$'.number_format($service->price, 0) : 'Consult' }}</td>
                            <td class="px-4 py-4">{{ $service->is_consultation_based ? 'Consultation' : 'Fixed price' }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $service->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $service->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-black/60">No services yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="border-t border-black/10 px-4 py-4">{{ $services->links() }}</div>
        @endif
    </section>
</div>
@endsection
