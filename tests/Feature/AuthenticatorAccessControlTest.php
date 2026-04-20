<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PragmaRX\Google2FALaravel\Google2FA;
use Tests\TestCase;

class AuthenticatorAccessControlTest extends TestCase
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

    public function test_super_admin_authenticator_page_requires_authenticated_super_admin(): void
    {
        $response = $this->get(route('super-admin.authenticator.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_super_admin_cannot_access_super_admin_authenticator_page(): void
    {
        $user = $this->makeUser([
            'email' => 'admin@example.com',
            'role' => 'admin',
            'is_authorized' => true,
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $user->id,
        ])->get(route('super-admin.authenticator.index'));

        $response->assertRedirect(route('super_admin.superAdmin.login'));
    }

    public function test_revoked_user_cannot_start_google_authenticator_login(): void
    {
        $superAdmin = $this->makeUser([
            'email' => 'super-admin@example.com',
            'role' => 'hr-super-admin',
            'is_authorized' => true,
        ]);

        $secret = app(Google2FA::class)->generateSecretKey();
        $targetUser = $this->makeUser([
            'email' => 'revoked-staff@example.com',
            'role' => 'staff',
            'is_authorized' => true,
            'google2fa_enabled' => true,
            'google2fa_secret' => Crypt::encryptString($secret),
        ]);

        $this->withSession([
            'authenticated_user_id' => $superAdmin->id,
        ])->post(route('super-admin.authenticator.revoke', $targetUser))
            ->assertRedirect();

        $targetUser->refresh();

        $this->assertFalse((bool) $targetUser->is_authorized);

        $loginResponse = $this->post(route('auth.send-otp'), [
            'email' => $targetUser->email,
        ]);

        $loginResponse->assertRedirect(route('login'));
        $loginResponse->assertSessionHas('error', 'This account is not authorized for login yet. Please contact the super admin.');
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
