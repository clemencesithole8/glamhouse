<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthenticator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminTwoFactorChallengeController extends Controller
{
    public function show(): View
    {
        return view('admin.security.two-factor-challenge');
    }

    public function verify(Request $request, TwoFactorAuthenticator $authenticator): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30'],
        ]);

        $user = $request->user();
        $code = trim((string) $validated['code']);

        if ($user->two_factor_secret && $authenticator->verify($user->two_factor_secret, $code)) {
            $request->session()->put('admin_two_factor_verified_at', now()->timestamp);

            return redirect()->intended(route('admin.dashboard'));
        }

        $codes = $user->two_factor_recovery_codes ?? [];

        foreach ($codes as $index => $hashedCode) {
            if (Hash::check(strtoupper($code), $hashedCode)) {
                unset($codes[$index]);
                $user->two_factor_recovery_codes = array_values($codes);
                $user->save();

                $request->session()->put('admin_two_factor_verified_at', now()->timestamp);

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Recovery code accepted. That code has been consumed.');
            }
        }

        return back()
            ->withInput()
            ->withErrors(['code' => 'The two-factor code is invalid.']);
    }
}
