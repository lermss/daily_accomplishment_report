<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\AuthController;
use App\Mail\GoogleAuthenticatorProvisioningMail;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use PragmaRX\Google2FALaravel\Google2FA;
use Tests\TestCase;

class AuthenticatorFlowTest extends TestCase
{
    private string $compiledPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compiledPath = base_path('tests/.compiled-views/' . str_replace('\\', '-', static::class) . '-' . $this->name());
        File::ensureDirectoryExists($this->compiledPath);

        config()->set('view.compiled', $this->compiledPath);

        Schema::dropAllTables();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('staff');
            $table->string('department')->nullable();
            $table->string('bureau')->nullable();
            $table->string('office')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_authorized')->default(false);
            $table->string('otp_hash')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expiration')->nullable();
            $table->text('google2fa_secret')->nullable();
            $table->boolean('google2fa_enabled')->default(false);
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->text('google2fa_authorization_code_hash')->nullable();
            $table->timestamp('google2fa_authorization_code_expires_at')->nullable();
            $table->timestamp('google2fa_authorization_sent_at')->nullable();
            $table->unsignedBigInteger('google2fa_authorized_by')->nullable();
            $table->timestamp('google2fa_authorized_at')->nullable();
            $table->timestamp('notifications_read_at')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->compiledPath);

        parent::tearDown();
    }

    public function test_authorized_user_with_provisioned_google_authenticator_is_sent_to_verify_screen(): void
    {
        $secret = app(Google2FA::class)->generateSecretKey();
        $user = $this->makeUser([
            'email' => 'staff@example.com',
            'role' => 'staff',
            'is_authorized' => true,
            'google2fa_enabled' => true,
            'google2fa_secret' => Crypt::encryptString($secret),
        ]);

        $response = $this->post(route('auth.send-otp'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('auth.2fa.verify.form'));
        $response->assertSessionHas('status', 'Enter the 6-digit code from your Google Authenticator app.');
        $this->assertSame($user->id, session('2fa:user:id'));
    }

    public function test_verify_screen_route_loads_for_pending_two_factor_session(): void
    {
        $secret = app(Google2FA::class)->generateSecretKey();
        $user = $this->makeUser([
            'email' => 'verify-screen@example.com',
            'role' => 'staff',
            'is_authorized' => true,
            'google2fa_enabled' => true,
            'google2fa_secret' => Crypt::encryptString($secret),
        ]);

        $response = app(AuthController::class)->showVerifyForm($this->pendingTwoFactorRequestFor($user));

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertSame('auth.verify-2fa', $response->name());
        $this->assertSame($user->email, $response->getData()['userEmail']);
    }

    public function test_authorized_user_without_provisioned_google_authenticator_gets_resend_message(): void
    {
        $user = $this->makeUser([
            'email' => 'staff-no-secret@example.com',
            'role' => 'staff',
            'is_authorized' => true,
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
        ]);

        $response = $this->post(route('auth.send-otp'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Google Authenticator is not ready for this account yet. Please ask the super admin to send your Google Authenticator access email again.');
    }

    public function test_super_admin_authorization_provisions_secret_and_sends_access_email(): void
    {
        Mail::fake();

        $actor = $this->makeUser([
            'email' => 'super-admin@example.com',
            'role' => 'hr-super-admin',
            'is_authorized' => true,
        ]);

        $targetUser = $this->makeUser([
            'email' => 'new-staff@example.com',
            'role' => 'staff',
            'is_authorized' => false,
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $actor->id,
        ])->post(route('super-admin.authenticator.authorize', $targetUser));

        $response->assertRedirect();

        $targetUser->refresh();

        $this->assertTrue((bool) $targetUser->is_authorized);
        $this->assertTrue((bool) $targetUser->google2fa_enabled);
        $this->assertNotNull($targetUser->google2fa_secret);
        $this->assertNull($targetUser->two_factor_confirmed_at);

        Mail::assertSent(GoogleAuthenticatorProvisioningMail::class, function (GoogleAuthenticatorProvisioningMail $mail) use ($targetUser) {
            return $mail->hasTo($targetUser->email)
                && $mail->manualSetupKey !== ''
                && $mail->qrImage !== null;
        });
    }

    public function test_resending_access_email_preserves_confirmed_state_and_existing_secret(): void
    {
        Mail::fake();

        $actor = $this->makeUser([
            'email' => 'super-admin-resend@example.com',
            'role' => 'hr-super-admin',
            'is_authorized' => true,
        ]);

        $existingSecret = app(Google2FA::class)->generateSecretKey();
        $confirmedAt = now()->subDay();
        $targetUser = $this->makeUser([
            'email' => 'existing-staff@example.com',
            'role' => 'staff',
            'is_authorized' => true,
            'google2fa_enabled' => true,
            'google2fa_secret' => Crypt::encryptString($existingSecret),
            'two_factor_confirmed_at' => $confirmedAt,
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $actor->id,
        ])->post(route('super-admin.authenticator.authorize', $targetUser));

        $response->assertRedirect();

        $targetUser->refresh();

        $this->assertTrue((bool) $targetUser->google2fa_enabled);
        $this->assertNotNull($targetUser->two_factor_confirmed_at);
        $this->assertSame($confirmedAt->toDateTimeString(), $targetUser->two_factor_confirmed_at->toDateTimeString());
        $this->assertSame($existingSecret, Crypt::decryptString($targetUser->google2fa_secret));

        Mail::assertSent(GoogleAuthenticatorProvisioningMail::class, function (GoogleAuthenticatorProvisioningMail $mail) use ($targetUser, $existingSecret) {
            return $mail->hasTo($targetUser->email)
                && $mail->manualSetupKey === $existingSecret;
        });
    }

    public function test_successful_google_authenticator_login_marks_first_confirmation(): void
    {
        $secret = app(Google2FA::class)->generateSecretKey();
        $currentCode = app(Google2FA::class)->getCurrentOtp($secret);

        $user = $this->makeUser([
            'email' => 'verified-staff@example.com',
            'role' => 'staff',
            'is_authorized' => true,
            'google2fa_enabled' => true,
            'google2fa_secret' => Crypt::encryptString($secret),
            'two_factor_confirmed_at' => null,
        ]);

        $response = $this->withSession([
            '2fa:user:id' => $user->id,
        ])->post(route('auth.2fa.verify'), [
            'code' => $currentCode,
        ]);

        $response->assertRedirect(route('home_page'));
        $this->assertNotNull($user->fresh()->two_factor_confirmed_at);
    }

    private function makeUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Test User',
            'first_name' => 'Test',
            'middle_name' => null,
            'last_name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'department' => null,
            'status' => 'active',
            'is_authorized' => false,
            'otp_hash' => null,
            'otp_code' => null,
            'otp_expiration' => null,
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
            'two_factor_confirmed_at' => null,
            'google2fa_authorization_code_hash' => null,
            'google2fa_authorization_code_expires_at' => null,
            'google2fa_authorization_sent_at' => null,
            'google2fa_authorized_by' => null,
            'google2fa_authorized_at' => null,
        ], $overrides));
    }

    private function pendingTwoFactorRequestFor(User $user): Request
    {
        $request = Request::create(route('auth.2fa.verify.form'), 'GET');
        $request->setLaravelSession(app('session')->driver());
        $request->session()->put('2fa:user:id', $user->id);

        return $request;
    }
}
