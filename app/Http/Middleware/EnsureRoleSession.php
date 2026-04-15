<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleSession
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userId = $request->session()->get('authenticated_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        // Keep route access tied to the authenticated session and the allowed role list.
        if (! $user || ! in_array((string) $user->role, $roles, true)) {
            $request->session()->flush();

            return redirect()->route($this->loginRoute($roles))->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }

    private function loginRoute(array $roles): string
    {
        return collect($roles)->contains(fn (string $role) => in_array($role, ['super_admin', 'hr-super-admin'], true))
            ? 'super_admin.superAdmin.login'
            : 'admin.login';
    }
}
