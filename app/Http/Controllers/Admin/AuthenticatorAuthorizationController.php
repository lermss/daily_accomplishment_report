<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GoogleAuthenticatorProvisioningMail;
use App\Models\User;
use App\Services\AdminPortalService;
use App\Services\AuthFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Google2FA;

class AuthenticatorAuthorizationController extends Controller
{
    public function __construct(
        private readonly AuthFlowService $authFlowService,
        private readonly AdminPortalService $adminPortalService,
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $actor = $this->superAdminUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        $search = trim((string) $request->query('search', ''));
        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';

                $query->where(function ($subQuery) use ($like, $search) {
                    $subQuery
                        ->where('name', 'like', $like)
                        ->orWhere('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhereRaw("CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,'')) LIKE ?", [$like])
                        ->orWhere('email', 'like', $like)
                        ->orWhere('role', 'like', $like)
                        ->orWhere('office', 'like', $like);
                });
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'first_name',
                'last_name',
                'email',
                'role',
                'status',
                'office',
                'is_authorized',
                'google2fa_enabled',
                'two_factor_confirmed_at',
                'google2fa_authorization_sent_at',
                'google2fa_authorization_code_expires_at',
            ]);

        return view('super_admin.authenticator-authorizations', [
            'title' => 'Authenticator Authorizations',
            'user' => $actor,
            'search' => $search,
            'users' => $users,
            'canAccessAudit' => $this->authFlowService->canAccessAudit($actor->role),
        ]);
    }

    public function authorize(Request $request, User $targetUser): RedirectResponse
    {
        $actor = $this->superAdminUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        if ($targetUser->status !== 'active') {
            return back()->with('authenticator_error', 'Only active accounts can be authorized for login.');
        }

        if ((string) $targetUser->role === 'super_admin') {
            return back()->with('authenticator_error', 'Use the seed or admin workflow for super admin access management.');
        }

        $existingSecret = $this->existingSecret($targetUser);
        $manualSetupKey = $existingSecret ?? app(Google2FA::class)->generateSecretKey();
        $qrImage = $this->buildQrImage($targetUser->email, $manualSetupKey);
        $isExistingProvisioning = $existingSecret !== null && (bool) $targetUser->google2fa_enabled;

        $targetUser->forceFill([
            'is_authorized' => true,
            'google2fa_secret' => Crypt::encryptString($manualSetupKey),
            'google2fa_enabled' => true,
            'two_factor_confirmed_at' => $isExistingProvisioning ? $targetUser->two_factor_confirmed_at : null,
            'google2fa_authorization_code_hash' => null,
            'google2fa_authorization_code_expires_at' => null,
            'google2fa_authorization_sent_at' => now(),
            'google2fa_authorized_by' => $actor->id,
            'google2fa_authorized_at' => now(),
        ])->save();

        Mail::to($targetUser->email)->send(new GoogleAuthenticatorProvisioningMail(
            $targetUser->email,
            $manualSetupKey,
            $qrImage,
        ));

        $this->adminPortalService->logActivity($actor, 'user_updated', 'Provisioned Google Authenticator access for ' . $targetUser->email . '.');

        return back()->with('authenticator_status', 'Google Authenticator access email sent to ' . $targetUser->email . '.');
    }

    public function revoke(Request $request, User $targetUser): RedirectResponse
    {
        $actor = $this->superAdminUser($request);

        if ($actor instanceof RedirectResponse) {
            return $actor;
        }

        $targetUser->forceFill([
            'is_authorized' => false,
            'google2fa_authorization_code_hash' => null,
            'google2fa_authorization_code_expires_at' => null,
            'google2fa_authorization_sent_at' => null,
        ])->save();

        $this->adminPortalService->logActivity($actor, 'user_updated', 'Revoked login authorization for ' . $targetUser->email . '.');

        return back()->with('authenticator_status', 'Login authorization revoked for ' . $targetUser->email . '.');
    }

    private function superAdminUser(Request $request): User|RedirectResponse
    {
        return $this->authFlowService->requireAuthenticated(
            $request,
            fn (User $user) => $this->authFlowService->isSuperAdminRole($user->role)
        );
    }

    private function buildQrImage(string $email, string $secret): ?string
    {
        try {
            return app(Google2FA::class)->getQRCodeInline(config('app.name', 'Laravel'), $email, $secret);
        } catch (\Throwable) {
            return null;
        }
    }

    private function existingSecret(User $user): ?string
    {
        $storedSecret = trim((string) ($user->google2fa_secret ?? ''));

        if ($storedSecret === '') {
            return null;
        }

        try {
            return Crypt::decryptString($storedSecret);
        } catch (\Throwable) {
            return $storedSecret;
        }
    }
}
