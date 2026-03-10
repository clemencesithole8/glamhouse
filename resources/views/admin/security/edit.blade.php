@extends('layouts.admin')

@section('title', 'Admin Security - Glamhouse')
@section('page_title', 'Admin Security')

@section('content')
<div class="space-y-6">
    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <h2 class="font-display text-3xl text-[#2a1c19]">Admin Credential Details</h2>
        <p class="mt-2 text-sm text-black/65">Update the admin login name and email used to access the secure dashboard.</p>

        <form method="POST" action="{{ route('admin.security.profile.update') }}" class="mt-6 grid gap-4 sm:max-w-xl">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">Admin Name</label>
                <input id="name" name="name" type="text" required value="{{ old('name', $admin->name) }}"
                       class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
            </div>

            <div>
                <label for="email" class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">Admin Email</label>
                <input id="email" name="email" type="email" required value="{{ old('email', $admin->email) }}"
                       class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
            </div>

            <div>
                <button type="submit" class="btn-primary">Save Credential Details</button>
            </div>
        </form>
    </section>

    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <h2 class="font-display text-3xl text-[#2a1c19]">Change Admin Password</h2>
        <p class="mt-2 text-sm text-black/65">Use a strong password and keep it private. You must confirm current password before changes are applied.</p>

        <form method="POST" action="{{ route('admin.security.password.update') }}" class="mt-6 grid gap-4 sm:max-w-xl">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">Current Password</label>
                <input id="current_password" name="current_password" type="password" required
                       class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
            </div>

            <div>
                <label for="password" class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">New Password</label>
                <input id="password" name="password" type="password" required
                       class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
            </div>

            <div>
                <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
            </div>

            <div>
                <button type="submit" class="btn-primary">Update Password</button>
            </div>
        </form>
    </section>

    <section class="rounded-3xl border border-black/10 bg-[#fbf7f3] p-6">
        <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-black/60">Security Note</h3>
        <p class="mt-2 text-sm text-black/70">
            Public registration is disabled by default. Only designated admin users with <code>is_admin=true</code> can access the admin dashboard.
        </p>
    </section>
</div>
@endsection
