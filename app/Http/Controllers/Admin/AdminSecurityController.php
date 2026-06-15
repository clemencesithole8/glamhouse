<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\TwoFactorAuthenticator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminSecurityController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.security.edit', [
            'admin' => $request->user(),
            'twoFactorSetupSecret' => $request->session()->get('two_factor_setup_secret'),
            'twoFactorSetupUri' => $request->session()->has('two_factor_setup_secret')
                ? app(TwoFactorAuthenticator::class)->otpauthUri($request->user(), $request->session()->get('two_factor_setup_secret'))
                : null,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($request->user()->id),
            ],
        ]);

        $admin = $request->user();
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->save();

        AuditLog::record('security.profile_updated', $admin, [], $admin->only(['id', 'name', 'email']), 'Admin profile updated.');

        return back()->with('success', 'Admin profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = $request->user();
        $admin->password = Hash::make($validated['password']);
        $admin->save();

        AuditLog::record('security.password_updated', $admin, [], ['password_updated' => true], 'Admin password updated.');

        return back()->with('success', 'Admin password updated successfully.');
    }

    public function prepareTwoFactor(Request $request, TwoFactorAuthenticator $authenticator): RedirectResponse
    {
        $request->session()->put('two_factor_setup_secret', $authenticator->generateSecret());

        return back()->with('success', 'Two-factor setup started. Add the setup key to your authenticator app, then verify the code.');
    }

    public function enableTwoFactor(Request $request, TwoFactorAuthenticator $authenticator): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'code' => ['required', 'string', 'max:10'],
        ]);

        $secret = $request->session()->get('two_factor_setup_secret');

        if (! $secret) {
            return back()->withErrors(['code' => 'Start two-factor setup before verifying a code.']);
        }

        if (! $authenticator->verify($secret, $validated['code'])) {
            return back()->withErrors(['code' => 'The two-factor code is invalid.']);
        }

        $recoveryCodes = $authenticator->generateRecoveryCodes();
        $admin = $request->user();
        $admin->two_factor_secret = $secret;
        $admin->two_factor_recovery_codes = array_map(fn (string $code): string => Hash::make($code), $recoveryCodes);
        $admin->two_factor_enabled_at = now();
        $admin->save();

        $request->session()->forget('two_factor_setup_secret');
        $request->session()->put('admin_two_factor_verified_at', now()->timestamp);
        $request->session()->flash('two_factor_recovery_codes', $recoveryCodes);

        AuditLog::record('security.two_factor_enabled', $admin, [], ['two_factor_enabled_at' => $admin->two_factor_enabled_at?->toIso8601String()], 'Admin two-factor authentication enabled.');

        return back()->with('success', 'Two-factor authentication enabled. Save the recovery codes shown below.');
    }

    public function regenerateRecoveryCodes(Request $request, TwoFactorAuthenticator $authenticator): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $admin = $request->user();

        if (! $admin->hasAdminTwoFactorEnabled()) {
            return back()->withErrors(['current_password' => 'Enable two-factor authentication before generating recovery codes.']);
        }

        $recoveryCodes = $authenticator->generateRecoveryCodes();
        $admin->two_factor_recovery_codes = array_map(fn (string $code): string => Hash::make($code), $recoveryCodes);
        $admin->save();

        $request->session()->flash('two_factor_recovery_codes', $recoveryCodes);

        AuditLog::record('security.recovery_codes_regenerated', $admin, [], ['recovery_codes_regenerated' => true], 'Admin recovery codes regenerated.');

        return back()->with('success', 'Recovery codes regenerated. Save the new codes shown below.');
    }

    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $admin = $request->user();
        $before = [
            'two_factor_enabled_at' => $admin->two_factor_enabled_at?->toIso8601String(),
        ];
        $admin->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_enabled_at' => null,
        ])->save();

        $request->session()->forget('admin_two_factor_verified_at');

        AuditLog::record('security.two_factor_disabled', $admin, $before, ['two_factor_enabled_at' => null], 'Admin two-factor authentication disabled.');

        return back()->with('success', 'Two-factor authentication disabled.');
    }
}
