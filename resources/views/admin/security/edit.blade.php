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

    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl text-[#2a1c19]">Two-Factor Authentication</h2>
                <p class="mt-2 text-sm text-black/65">Require a six-digit authenticator code before admin screens can be opened.</p>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $admin->hasAdminTwoFactorEnabled() ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                {{ $admin->hasAdminTwoFactorEnabled() ? 'Enabled' : 'Not enabled' }}
            </span>
        </div>

        @if(session('two_factor_recovery_codes'))
            <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <div class="font-semibold text-amber-950">Save these recovery codes now.</div>
                <div class="mt-3 grid gap-2 font-mono text-sm sm:grid-cols-2">
                    @foreach(session('two_factor_recovery_codes') as $code)
                        <div class="rounded-lg bg-white px-3 py-2">{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(! $admin->hasAdminTwoFactorEnabled())
            @if(! $twoFactorSetupSecret)
                <form method="POST" action="{{ route('admin.security.two-factor.prepare') }}" class="mt-5">
                    @csrf
                    <button class="btn-primary text-sm">Start 2FA Setup</button>
                </form>
            @else
                <div class="mt-5 rounded-2xl border border-black/10 bg-[#fbf7f3] p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.14em] text-black/55">Setup Key</div>
                    <div class="mt-2 break-all rounded-xl bg-white px-3 py-2 font-mono text-sm">{{ $twoFactorSetupSecret }}</div>
                    <p class="mt-3 text-sm text-black/65">Add this key to your authenticator app, or paste the setup URI below if your app supports it.</p>
                    <div class="mt-2 break-all rounded-xl bg-white px-3 py-2 font-mono text-xs">{{ $twoFactorSetupUri }}</div>
                </div>

                <form method="POST" action="{{ route('admin.security.two-factor.enable') }}" class="mt-5 grid gap-4 sm:max-w-xl">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">Current Password</label>
                        <input name="current_password" type="password" required class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">Authenticator Code</label>
                        <input name="code" inputmode="numeric" autocomplete="one-time-code" required class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
                    </div>
                    <div>
                        <button class="btn-primary text-sm">Verify and Enable 2FA</button>
                    </div>
                </form>
            @endif
        @else
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                <form method="POST" action="{{ route('admin.security.two-factor.recovery-codes') }}" class="rounded-2xl border border-black/10 bg-[#fbf7f3] p-4">
                    @csrf
                    <h3 class="font-semibold">Regenerate Recovery Codes</h3>
                    <p class="mt-1 text-sm text-black/65">Old recovery codes stop working once new ones are generated.</p>
                    <input name="current_password" type="password" required placeholder="Current password" class="mt-4 w-full rounded-xl border border-black/15 bg-white px-4 py-2.5 text-sm focus:border-rosegold-500 focus:outline-none focus:ring-2 focus:ring-rosegold-200">
                    <button class="btn-outline mt-4 text-sm">Generate New Codes</button>
                </form>

                <form method="POST" action="{{ route('admin.security.two-factor.disable') }}" class="rounded-2xl border border-rose-200 bg-rose-50 p-4" onsubmit="return confirm('Disable two-factor authentication for this admin account?');">
                    @csrf
                    @method('DELETE')
                    <h3 class="font-semibold text-rose-950">Disable Two-Factor</h3>
                    <p class="mt-1 text-sm text-rose-800">Admin access will only require email and password after this.</p>
                    <input name="current_password" type="password" required placeholder="Current password" class="mt-4 w-full rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                    <button class="mt-4 rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-100">Disable 2FA</button>
                </form>
            </div>
        @endif
    </section>

    <section class="rounded-3xl border border-black/10 bg-[#fbf7f3] p-6">
        <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-black/60">Security Note</h3>
        <p class="mt-2 text-sm text-black/70">
            Public registration is disabled by default. Only designated admin users with <code>is_admin=true</code> can access the admin dashboard.
        </p>
    </section>
</div>
@endsection
