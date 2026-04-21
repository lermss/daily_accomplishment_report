<?php

namespace Tests\Feature;

use App\Mail\GoogleAuthenticatorProvisioningMail;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthenticatorSuperAdminAuthorizationTest extends TestCase
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

    public function test_hr_super_admin_account_can_be_authorized_from_authenticator_access(): void
    {
        Mail::fake();

        $actor = $this->makeUser([
            'email' => 'authorizer@example.com',
            'role' => 'hr-super-admin',
            'is_authorized' => true,
        ]);

        $targetUser = $this->makeUser([
            'email' => 'lermamagno12@gmail.com',
            'role' => 'hr-super-admin',
            'is_authorized' => false,
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $actor->id,
        ])->post(route('super-admin.authenticator.authorize', $targetUser));

        $response->assertRedirect();
        $response->assertSessionHas('authenticator_status', 'Google Authenticator access email sent to ' . $targetUser->email . '.');
        $response->assertSessionMissing('authenticator_error');

        $targetUser->refresh();

        $this->assertTrue((bool) $targetUser->is_authorized);
        $this->assertTrue((bool) $targetUser->google2fa_enabled);
        $this->assertNotNull($targetUser->google2fa_secret);
        $this->assertSame($actor->id, $targetUser->google2fa_authorized_by);
        $this->assertNotNull($targetUser->google2fa_authorized_at);

        Mail::assertSent(GoogleAuthenticatorProvisioningMail::class, function (GoogleAuthenticatorProvisioningMail $mail) use ($targetUser) {
            return $mail->hasTo($targetUser->email);
        });
    }

    public function test_seed_only_super_admin_account_stays_blocked_from_authenticator_access_authorization(): void
    {
        Mail::fake();

        $actor = $this->makeUser([
            'email' => 'authorizer@example.com',
            'role' => 'hr-super-admin',
            'is_authorized' => true,
        ]);

        $targetUser = $this->makeUser([
            'email' => 'seed-super-admin@example.com',
            'role' => 'super_admin',
            'is_authorized' => false,
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $actor->id,
        ])->post(route('super-admin.authenticator.authorize', $targetUser));

        $response->assertRedirect();
        $response->assertSessionHas('authenticator_error', 'Use the seed or admin workflow for super admin access management.');

        $targetUser->refresh();

        $this->assertFalse((bool) $targetUser->is_authorized);
        $this->assertFalse((bool) $targetUser->google2fa_enabled);
        $this->assertNull($targetUser->google2fa_secret);

        Mail::assertNothingSent();
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
}
