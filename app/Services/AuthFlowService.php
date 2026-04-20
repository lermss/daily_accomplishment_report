<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthFlowService
{
    public function authenticatedUser(Request $request): ?User
    {
        $userId = $request->session()->get('authenticated_user_id');

        if (!$userId) {
            return null;
        }

        try {
            return User::find($userId);
        } catch (QueryException) {
            return null;
        }
    }

    public function requireAuthenticated(Request $request, ?callable $guard = null): User|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        if (!$user || $user->status !== 'active' || ! $user->is_authorized) {
            $request->session()->forget([
                'authenticated_user_id',
            ]);

            return redirect()->route('login')->with('error', 'Please sign in to continue.');
        }

        if ($guard !== null && !$guard($user)) {
            return redirect()->route($this->dashboardRoute($user->role));
        }

        return $user;
    }

    public function managedRoles(): array
    {
        return ['super_admin', 'hr-super-admin', 'admin', 'ph-admin', 'staff', 'interns'];
    }

    // ADD THIS CODE
    public function isStaffRole(?string $role): bool
    {
        return User::isStaffRole($role);
    }

    // ADD THIS CODE
    public function staffPortalPrefix(?string $role): string
    {
        return (string) $role === 'interns' ? 'intern' : 'staff';
    }

    // ADD THIS CODE
    public function staffPortalRoute(?string $role, string $suffix): string
    {
        return $this->staffPortalPrefix($role) . '.' . ltrim($suffix, '.');
    }

    public function dashboardRoute(?string $role): string
    {
        return match ($role) {
            'super_admin', 'hr-super-admin' => 'dashboard.super-admin',
            'admin', 'ph-admin' => 'dashboard.admin',
            'staff', 'interns' => $this->staffPortalRoute($role, 'home'),
            default => 'login',
        };
    }

    public function canManageUsers(?string $role): bool
    {
        return in_array((string) $role, ['super_admin', 'hr-super-admin', 'admin', 'ph-admin'], true);
    }

    public function canAccessAudit(?string $role): bool
    {
        return in_array((string) $role, ['super_admin', 'hr-super-admin', 'admin', 'ph-admin'], true);
    }

    public function isAdminRole(?string $role): bool
    {
        return in_array((string) $role, ['admin', 'ph-admin'], true);
    }

    public function isSuperAdminRole(?string $role): bool
    {
        return in_array((string) $role, ['super_admin', 'hr-super-admin'], true);
    }

    public function findManagedActiveUserByEmail(string $email): ?User
    {
        return User::query()
            ->whereRaw('LOWER(email) = ?', [strtolower(trim($email))])
            ->where('status', 'active')
            ->whereIn('role', $this->managedRoles())
            ->first();
    }
}
