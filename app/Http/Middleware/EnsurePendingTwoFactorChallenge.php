<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePendingTwoFactorChallenge
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('2fa:user:id')) {
            return redirect()->route('login')->with('error', 'Your two-factor session has expired. Please sign in again.');
        }

        return $next($request);
    }
}
