<?php

namespace Tests\Feature;

use App\Http\Controllers\Staff\DashboardController;
use App\Models\Report;
use App\Models\ReportEntry;
use App\Models\User;
use App\Services\AdminPortalService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StaffFeatureRegressionTest extends TestCase
{
    private string $compiledPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setCompiledViewPath('initial');

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
            $table->string('position')->nullable();
            $table->string('project')->nullable();
            $table->string('bureau')->nullable();
            $table->string('office')->nullable();
            $table->string('department')->nullable();
            $table->string('division')->nullable();
            $table->string('institution')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_authorized')->default(true);
            $table->text('google2fa_secret')->nullable();
            $table->boolean('google2fa_enabled')->default(false);
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamp('notifications_read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('assigned_provincial_head_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('review_comment')->nullable();
            $table->boolean('is_hidden_from_staff_dashboard')->default(false);
            $table->boolean('is_hidden_from_staff_index')->default(false);
            $table->boolean('is_hidden_from_admin_dashboard')->default(false);
            $table->timestamps();
        });

        Schema::create('report_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('activity')->nullable();
            $table->text('details')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->compiledPath);

        parent::tearDown();
    }

    public function test_staff_can_create_report_with_entries(): void
    {
        $user = $this->makeUser([
            'email' => 'reporter@example.com',
            'role' => 'staff',
            'office' => 'Regional Office',
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $user->id,
        ])->post(route('staff.reports.store'), [
            'file_name' => 'Weekly Report',
            'start_date' => ['2026-04-20'],
            'end_date' => ['2026-04-20'],
            'activity' => ['Prepared updates'],
            'details' => ['Compiled accomplishments'],
            'remarks' => ['Ready for review'],
        ]);

        $response->assertRedirect(route('staff.reports'));

        $report = Report::query()->first();

        $this->assertNotNull($report);
        $this->assertSame($user->id, $report->user_id);
        $this->assertSame('Weekly Report', $report->file_name);
        $this->assertSame(Report::STATUS_DRAFT, $report->status);

        $entry = ReportEntry::query()->first();

        $this->assertNotNull($entry);
        $this->assertSame($report->id, $entry->report_id);
        $this->assertSame('Prepared updates', $entry->activity);
        $this->assertSame('Compiled accomplishments', $entry->details);
        $this->assertSame('Ready for review', $entry->remarks);
    }

    public function test_created_draft_report_appears_on_staff_dashboard(): void
    {
        $user = $this->makeUser([
            'email' => 'dashboard@example.com',
            'role' => 'staff',
            'office' => 'La Union',
        ]);

        $this->withSession([
            'authenticated_user_id' => $user->id,
        ])->post(route('staff.reports.store'), [
            'file_name' => 'Dashboard Visible Report',
            'start_date' => ['2026-04-20'],
            'end_date' => ['2026-04-20'],
            'activity' => ['Prepared dashboard update'],
            'details' => ['Verified report visibility'],
            'remarks' => ['Draft should be visible'],
        ]);

        $request = Request::create(route('staff.dashboard'), 'GET');
        $request->setLaravelSession(app('session')->driver());
        $request->session()->put('authenticated_user_id', $user->id);

        $response = app(DashboardController::class)->staff($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        $reports = $response->getData()['reports'];
        $this->assertCount(1, $reports);
        $this->assertSame('Dashboard Visible Report', $reports->first()->file_name);
        $this->assertSame(Report::STATUS_DRAFT, $reports->first()->status);
    }

    public function test_staff_can_update_existing_report_entry(): void
    {
        $user = $this->makeUser([
            'email' => 'editor@example.com',
            'role' => 'staff',
            'office' => 'Regional Office',
        ]);

        $report = Report::query()->create([
            'user_id' => $user->id,
            'file_name' => 'Editable Report',
            'status' => Report::STATUS_DRAFT,
        ]);

        $entry = ReportEntry::query()->create([
            'report_id' => $report->id,
            'start_date' => '2026-04-20',
            'end_date' => '2026-04-20',
            'activity' => 'Old activity',
            'details' => 'Old details',
            'remarks' => 'Old remarks',
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $user->id,
        ])->put(route('staff.reports.update', $report->id), [
            'entry_id' => [$entry->id],
            'start_date' => ['2026-04-21'],
            'end_date' => ['2026-04-21'],
            'activity' => ['Updated activity'],
            'details' => ['Updated details'],
            'remarks' => ['Updated remarks'],
        ]);

        $response->assertRedirect();

        $entry->refresh();

        $this->assertSame('2026-04-21', $entry->start_date);
        $this->assertSame('2026-04-21', $entry->end_date);
        $this->assertSame('Updated activity', $entry->activity);
        $this->assertSame('Updated details', $entry->details);
        $this->assertSame('Updated remarks', $entry->remarks);
    }

    public function test_staff_profile_update_persists_changes(): void
    {
        $user = $this->makeUser([
            'email' => 'profile@example.com',
            'role' => 'staff',
            'office' => 'Old Office',
            'position' => 'Old Position',
            'project' => 'Old Project',
            'bureau' => 'Old Bureau',
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $user->id,
        ])->put(route('staff.profile.update'), [
            'first_name' => 'Grant',
            'middle_name' => 'A.',
            'last_name' => 'Arachea',
            'position' => 'Developer',
            'project' => 'DigiGov',
            'bureau' => 'Regional Office',
            'office' => 'La Union',
        ]);

        $response->assertRedirect(route('staff.profile'));
        $response->assertSessionHas('profile_status', 'Profile updated successfully.');

        $user->refresh();

        $this->assertSame('Grant Arachea', $user->name);
        $this->assertSame('Grant', $user->first_name);
        $this->assertSame('A.', $user->middle_name);
        $this->assertSame('Arachea', $user->last_name);
        $this->assertSame('Developer', $user->position);
        $this->assertSame('DigiGov', $user->project);
        $this->assertSame('Regional Office', $user->bureau);
        $this->assertSame('La Union', $user->office);
    }

    public function test_staff_profile_update_allows_missing_optional_position_field(): void
    {
        $user = $this->makeUser([
            'email' => 'optional-position@example.com',
            'role' => 'staff',
            'office' => 'La Union',
            'position' => 'Existing Position',
            'project' => 'Old Project',
            'bureau' => 'Old Bureau',
        ]);

        $response = $this->withSession([
            'authenticated_user_id' => $user->id,
        ])->put(route('staff.profile.update'), [
            'first_name' => 'Grant',
            'middle_name' => null,
            'last_name' => 'Tester',
            'project' => 'DigiGov',
            'bureau' => 'Regional Office',
            'office' => 'Ilocos Norte',
        ]);

        $response->assertRedirect(route('staff.profile'));
        $response->assertSessionHas('profile_status', 'Profile updated successfully.');

        $user->refresh();

        $this->assertSame('Existing Position', $user->position);
        $this->assertSame('DigiGov', $user->project);
        $this->assertSame('Regional Office', $user->bureau);
        $this->assertSame('Ilocos Norte', $user->office);
    }

    public function test_profile_data_includes_fallback_position_options_when_database_has_none(): void
    {
        $user = $this->makeUser([
            'email' => 'no-position-options@example.com',
            'role' => 'staff',
            'position' => null,
        ]);

        $profileData = app(AdminPortalService::class)->buildProfileData($user);

        $this->assertNotEmpty($profileData['positionOptions']);
        $this->assertContains('Staff', $profileData['positionOptions']);
        $this->assertContains('Administrative Assistant', $profileData['positionOptions']);
    }

    private function makeUser(array $overrides = []): User
    {
        return User::query()->create(array_merge([
            'name' => 'Test User',
            'first_name' => 'Test',
            'middle_name' => null,
            'last_name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'position' => null,
            'project' => null,
            'bureau' => null,
            'office' => 'Regional Office',
            'department' => null,
            'division' => null,
            'institution' => null,
            'status' => 'active',
            'is_authorized' => true,
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
            'two_factor_confirmed_at' => null,
            'notifications_read_at' => null,
        ], $overrides));
    }

    private function setCompiledViewPath(string $suffix): void
    {
        $this->compiledPath = base_path('tests/.compiled-views/' . str_replace('\\', '-', static::class) . '-' . $this->name() . '-' . $suffix);
        File::deleteDirectory($this->compiledPath);
        File::ensureDirectoryExists($this->compiledPath);

        config()->set('view.compiled', $this->compiledPath);
    }
}
