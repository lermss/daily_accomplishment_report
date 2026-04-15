<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('authenticated_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user || !in_array((string) $user->role, ['staff', 'interns'], true)) {
            $request->session()->flush();

            return redirect()->route('login')->with('error', 'Unauthorized staff access.');
        }

        return $next($request);
    }
}
