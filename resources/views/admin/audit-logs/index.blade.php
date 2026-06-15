@extends('layouts.admin')

@section('title', 'Audit Logs - Glamhouse Admin')
@section('page_title', 'Audit Logs')

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_220px_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Search description, action, or model" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <input name="action" value="{{ request('action') }}" placeholder="Action filter" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            <button class="btn-outline text-sm">Filter</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">When</th>
                        <th class="px-4 py-3">Admin</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Target</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-t border-black/10 align-top">
                            <td class="px-4 py-3 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $log->user?->email ?? 'System' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $log->action }}</td>
                            <td class="px-4 py-3 text-black/70">{{ $log->description ?: '-' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">
                                {{ class_basename($log->auditable_type) ?: 'N/A' }}{{ $log->auditable_id ? '#'.$log->auditable_id : '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-black/60">No audit events yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="border-t border-black/10 px-4 py-4">{{ $logs->links() }}</div>
        @endif
    </section>
</div>
@endsection
