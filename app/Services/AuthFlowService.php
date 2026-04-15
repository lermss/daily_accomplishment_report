<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

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

        if (!$user || $user->status !== 'active') {
            $request->session()->forget([
                'authenticated_user_id',
                'otp_login_email',
                'otp_expires_at',
                'otp_requested_at',
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

    public function dashboardRoute(?string $role): string
    {
        return match ($role) {
            'super_admin', 'hr-super-admin' => 'dashboard.super-admin',
            'admin', 'ph-admin' => 'dashboard.admin',
            'staff', 'interns' => 'staff.home',
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

    public function dispatchOtp(User $user, Request $request): string
    {
        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        $updates = ['otp_hash' => Hash::make($otp)];

        if ($this->safeHasColumn('users', 'otp_expiration')) {
            $updates['otp_expiration'] = $expiresAt;
        }

        if ($this->safeHasColumn('users', 'otp_code')) {
            $updates['otp_code'] = null;
        }

        $user->forceFill($updates)->save();

        $request->session()->put('otp_login_email', $user->email);
        $request->session()->put('otp_expires_at', $expiresAt->toIso8601String());
        $request->session()->put('otp_requested_at', now()->toIso8601String());

        Mail::raw(
            "Your login code is {$otp}\nThis code expires in 5 minutes.",
            static function ($message) use ($user): void {
                $message->to($user->email)->subject('Your DICT login code');
            }
        );

        return $otp;
    }

    public function clearOtp(User $user, bool $includeCode = true): void
    {
        $updates = ['otp_hash' => null];

        if ($this->safeHasColumn('users', 'otp_expiration')) {
            $updates['otp_expiration'] = null;
        }

        if ($includeCode && $this->safeHasColumn('users', 'otp_code')) {
            $updates['otp_code'] = null;
        }

        $user->forceFill($updates)->save();
    }

    public function otpCooldownRemaining(Request $request): int
    {
        $requestedAt = $request->session()->get('otp_requested_at');

        if (!$requestedAt) {
            return 0;
        }

        $availableAt = Carbon::parse($requestedAt)->addSeconds(90);

        return now()->lt($availableAt) ? now()->diffInSeconds($availableAt) : 0;
    }

    public function otpExpiration(User $user, Request $request): ?Carbon
    {
        if ($this->safeHasColumn('users', 'otp_expiration') && $user->otp_expiration) {
            return $user->otp_expiration;
        }

        if ($request->session()->has('otp_expires_at')) {
            return Carbon::parse($request->session()->get('otp_expires_at'));
        }

        return null;
    }

    public function verifyOtp(User $user, string $otp): bool
    {
        return (bool) $user->otp_hash && Hash::check($otp, $user->otp_hash);
    }

    public function forgetOtpSession(Request $request): void
    {
        $request->session()->forget(['otp_login_email', 'otp_expires_at', 'otp_requested_at']);
    }

    private function safeHasColumn(string $table, string $column): bool
    {
        static $cache = [];
        $key = $table . ':' . $column;

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        try {
            return $cache[$key] = Schema::hasColumn($table, $column);
        } catch (\Throwable) {
            return $cache[$key] = false;
        }
    }
}
