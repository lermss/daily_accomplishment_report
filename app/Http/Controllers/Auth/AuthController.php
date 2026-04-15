<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;


use App\Models\User;
use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    private const TWO_FACTOR_PENDING_KEY = '2fa:user:id';
    private const TWO_FACTOR_SETUP_SECRET_KEY = '2fa_setup_secret';

    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
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

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('status', 'If the email is registered and active, a login code has been sent.');
        }

        if ($this->hasGoogle2faEnabled($user)) {
            $request->session()->put(self::TWO_FACTOR_PENDING_KEY, $user->id);

            return redirect()
                ->route('auth.2fa.verify.form')
                ->with('status', 'Enter the 6-digit code from your Google Authenticator app.');
        }

        try {
            $this->authFlowService->dispatchOtp($user, $request);
            $this->adminPortalService->logActivity($user, 'otp_requested', 'OTP requested for sign in.');
        } catch (\Throwable) {
            $this->authFlowService->clearOtp($user);
            $this->authFlowService->forgetOtpSession($request);

            return redirect()
                ->route('login')
                ->with('error', 'The login code could not be sent right now. Please try again.');
        }

        return redirect()
            ->route('auth.verify-form', ['email' => $user->email])
            ->with('status', 'If the email is registered and active, a login code has been sent.');
    }

    public function showVerifyForm(Request $request): View
    {
        $requestedAt = $request->session()->get('otp_requested_at');
        $resendAvailableAt = $requestedAt ? Carbon::parse($requestedAt)->addSeconds(90) : now();

        return view('auth.verify-otp', [
            'email' => old('email', $request->query('email', $request->session()->get('otp_login_email'))),
            'resendAvailableAt' => $resendAvailableAt->toIso8601String(),
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_login_email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Please enter your email to request a new OTP.');
        }

        $remaining = $this->authFlowService->otpCooldownRemaining($request);

        if ($remaining > 0) {
            return back()->with('error', 'Please wait ' . $remaining . ' seconds before resending the OTP.');
        }

        try {
            $user = $this->authFlowService->findManagedActiveUserByEmail($email);
        } catch (QueryException) {
            return redirect()
                ->route('login')
                ->with('error', 'Database connection failed. Update your MySQL credentials in the .env file and try again.');
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'The requested account could not be found.');
        }

        try {
            $this->authFlowService->dispatchOtp($user, $request);
            $this->adminPortalService->logActivity($user, 'otp_resent', 'OTP resent for sign in.');
        } catch (\Throwable) {
            return back()->with('error', 'The login code could not be resent right now. Please try again.');
        }

        return redirect()
            ->route('auth.verify-form', ['email' => $user->email])
            ->with('status', 'A new OTP code has been sent to your registered email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        try {
            $user = $this->authFlowService->findManagedActiveUserByEmail($validated['email']);
        } catch (QueryException) {
            return redirect()
                ->route('login')
                ->with('error', 'Database connection failed. Update your MySQL credentials in the .env file and try again.');
        }

        if (!$user || !$this->authFlowService->verifyOtp($user, $validated['otp'])) {
            return back()->withErrors([
                'otp' => 'The OTP is invalid or has already been used.',
            ])->withInput();
        }

        $expiration = $this->authFlowService->otpExpiration($user, $request);

        if ($expiration && now()->greaterThan($expiration)) {
            $this->authFlowService->clearOtp($user, false);

            return redirect()
                ->route('login')
                ->with('error', 'The OTP has expired. Please request a new code.');
        }

        $this->authFlowService->clearOtp($user);
        $this->authFlowService->forgetOtpSession($request);

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

    public function show2faForm(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get(self::TWO_FACTOR_PENDING_KEY);

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user || !$this->hasGoogle2faEnabled($user)) {
            $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

            return redirect()->route('login')->with('error', 'Two-factor authentication is not available for this account.');
        }

        return view('auth.verify-2fa', [
            'userEmail' => $user->email,
        ]);
    }

    public function verify2fa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get(self::TWO_FACTOR_PENDING_KEY);

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Your two-factor session has expired. Please sign in again.');
        }

        $user = User::find($userId);

        if (!$user || !$this->hasGoogle2faEnabled($user)) {
            $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

            return redirect()->route('login')->with('error', 'Two-factor authentication is not available for this account.');
        }

        $secret = $this->google2faSecret($user);
        $valid = $secret !== null && (new Google2FA())->verifyKey($secret, trim($validated['code']), 1);

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Invalid authentication code. Check your device time and try again.',
            ])->withInput();
        }

        $request->session()->forget(self::TWO_FACTOR_PENDING_KEY);

        return $this->completeLogin($request, $user);
    }

    // public function setup2fa(Request $request): View|RedirectResponse
    // {
    //     $user = $this->authFlowService->authenticatedUser($request);

    //     if (!$user) {
    //         return redirect()->route('login');
    //     }

    //     $google2fa = new Google2FA();
    //     $secret = $google2fa->generateSecretKey();
    //     $qrImage = $google2fa->getQRCodeInline(config('app.name', 'Laravel'), $user->email, $secret);

    //     $request->session()->put(self::TWO_FACTOR_SETUP_SECRET_KEY, $secret);

    //     return view('auth.setup-2fa', [
    //         'QR_Image' => $qrImage,
    //         'secret' => $secret,
    //         'isEnabled' => (bool) $user->google2fa_enabled,
    //     ]);
    // }

    public function enable2fa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $this->authFlowService->authenticatedUser($request);

        if (!$user) {
            return redirect()->route('login');
        }

        $secret = trim((string) $request->session()->get(self::TWO_FACTOR_SETUP_SECRET_KEY, ''));

        if ($secret === '') {
            return redirect()->route('auth.2fa.setup')->with('error', 'Generate a new QR code before enabling Google Authenticator.');
        }

        $valid = (new Google2FA())->verifyKey($secret, trim($validated['code']), 1);

        if (!$valid) {
            return back()->withErrors([
                'code' => 'The authentication code is invalid. Scan the QR code again and try the current 6-digit code.',
            ])->withInput();
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'google2fa_secret' => Crypt::encryptString($secret),
                'google2fa_enabled' => 1,
                'two_factor_confirmed_at' => now(),
            ]);

        $request->session()->forget(self::TWO_FACTOR_SETUP_SECRET_KEY);

        return redirect()->route('dashboard')->with('status', 'Google Authenticator enabled successfully.');
    }

    public function disable2fa(Request $request): RedirectResponse
    {
        $user = $this->authFlowService->authenticatedUser($request);

        if (!$user) {
            return redirect()->route('login');
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'google2fa_secret' => null,
                'google2fa_enabled' => 0,
                'two_factor_confirmed_at' => null,
            ]);

        $request->session()->forget(self::TWO_FACTOR_SETUP_SECRET_KEY);

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
            'interns',
            'admin',
            'ph-admin',
            'hr-super-admin' => 'home_page',
            default => $this->authFlowService->dashboardRoute($user->role),
        });
    }

    private function hasGoogle2faEnabled(User $user): bool
    {
        return (bool) $user->google2fa_enabled && $this->google2faSecret($user) !== null;
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



