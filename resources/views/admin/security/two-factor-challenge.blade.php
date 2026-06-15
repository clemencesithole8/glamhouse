<x-guest-layout>
<div class="mb-6 text-center">
    <h1 class="font-display text-4xl text-[#2a1c19]">Two-Factor Verification</h1>
    <p class="mt-2 text-sm text-black/65">Enter your authenticator code or one recovery code to continue to admin.</p>
</div>

<form method="POST" action="{{ route('admin.two-factor.verify') }}" class="space-y-4">
    @csrf

    <div>
        <x-input-label for="code" value="Authentication Code" />
        <x-text-input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" class="mt-1 block w-full" required autofocus />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <button class="btn-primary w-full justify-center">Verify</button>
</form>
</x-guest-layout>
