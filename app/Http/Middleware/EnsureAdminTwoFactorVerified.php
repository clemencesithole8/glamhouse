<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->hasAdminTwoFactorEnabled() || $request->routeIs('admin.two-factor.*')) {
            return $next($request);
        }

        if ($request->session()->has('admin_two_factor_verified_at')) {
            return $next($request);
        }

        $request->session()->put('url.intended', $request->fullUrl());

        return redirect()->route('admin.two-factor.challenge');
    }
}
