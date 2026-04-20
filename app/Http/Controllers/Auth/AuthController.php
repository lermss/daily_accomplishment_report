<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use App\Services\SuperAdminNotificationService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Google2FA;

class AuthController extends Controller
{
    private const TWO_FACTOR_PENDING_KEY = '2fa:user:id';

    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
        private readonly SuperAdminNotificationService $superAdminNotificationService,
    ) {
    }

    public function showLogin(): View
    {
        return view('auth.signin');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $user = $this->authFlowService->findManagedActiveUserByEmail($validated['email']);
        } catch (QueryException) {
            return redirect()
                ->route('login')
                ->with('error', 'Database connection failed. Update your MySQL credentials in the .env file and try again.');
        }

        if (! $user || ! $user->is_authorized) {
            return redirect()
                ->route('login')
                ->with('error', 'This account is not authorized for login yet. Please contact the super admin.');
        }

        if (! $this->hasGoogle2faEnabled($user)) {
            return redirect()
                ->route('login')
                ->with('error', 'Google Authenticator is not ready for this account yet. Please ask the super admin to send your Google Authenticator access email again.');
        }

        $request->session()->put(self::TWO_FACTOR_PENDING_KEY, $user->id);

        return redirect()
            ->route('auth.2fa.verify.form')
            ->with('status', 'Enter the 6-digit code from your Google Authenticator app.');
    }

    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get(self::TWO_FACTOR_PENDING_KEY);

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user || ! $this->hasGoogle2faEnabled($user)) {
            $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

            return redirect()->route('login')->with('error', 'Two-factor authentication is not available for this account.');
        }

        return view('auth.verify-2fa', [
            'userEmail' => $user->email,
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        return redirect()
            ->route('login')
            ->with('error', 'Email OTP login is no longer active. Use the Google Authenticator flow instead.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        return redirect()
            ->route('login')
            ->with('error', 'Email OTP login is no longer active. Use the Google Authenticator flow instead.');
    }

    public function verify2fa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get(self::TWO_FACTOR_PENDING_KEY);

        if (! $userId) {
            return redirect()->route('login')->with('error', 'Your two-factor session has expired. Please sign in again.');
        }

        $user = User::find($userId);

        if (! $user || ! $this->hasGoogle2faEnabled($user)) {
            $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

            return redirect()->route('login')->with('error', 'Two-factor authentication is not available for this account.');
        }

        $secret = $this->google2faSecret($user);
        $valid = $secret !== null && app(Google2FA::class)->verifyKey($secret, trim($validated['code']), 1);

        if (! $valid) {
            return back()->withErrors([
                'code' => 'Invalid authentication code. Check your device time and try again.',
            ])->withInput();
        }

        if (! $user->two_factor_confirmed_at) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'two_factor_confirmed_at' => now(),
                    'google2fa_authorization_code_hash' => null,
                    'google2fa_authorization_code_expires_at' => null,
                ]);

            $user = $user->fresh();
        }

        $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

        return $this->completeLogin($request, $user);
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $this->authFlowService->authenticatedUser($request);
        $this->adminPortalService->logActivity($user, 'logout', 'User signed out.');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function disable2fa(Request $request): RedirectResponse
    {
        $user = $this->authFlowService->authenticatedUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'google2fa_secret' => null,
                'google2fa_enabled' => 0,
                'two_factor_confirmed_at' => null,
                'google2fa_authorization_code_hash' => null,
                'google2fa_authorization_code_expires_at' => null,
                'google2fa_authorization_sent_at' => null,
            ]);

        return redirect()->route('dashboard')->with('status', 'Google Authenticator has been disabled.');
    }

    private function completeLogin(Request $request, User $user): RedirectResponse
    {
        $request->session()->regenerate();
        $request->session()->put('authenticated_user_id', $user->id);
        $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

        $this->adminPortalService->logActivity($user, 'login', 'User signed in successfully.');

        return redirect()->route(match ($user->role) {
            'staff',
            'admin',
            'ph-admin',
            'hr-super-admin' => 'home_page',
            'interns' => $this->authFlowService->staffPortalRoute($user->role, 'home'),
            default => $this->authFlowService->dashboardRoute($user->role),
        });
    }

    private function hasGoogle2faEnabled(User $user): bool
    {
        return (bool) $user->is_authorized
            && (bool) $user->google2fa_enabled
            && $this->google2faSecret($user) !== null;
    }

    private function google2faSecret(User $user): ?string
    {
        $storedSecret = trim((string) ($user->google2fa_secret ?? ''));

        if ($storedSecret === '') {
            return null;
        }

        try {
            return Crypt::decryptString($storedSecret);
        } catch (DecryptException) {
            return $storedSecret;
        }
    }
}
