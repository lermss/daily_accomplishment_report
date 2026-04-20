<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ProvincialReminderController;
use App\Http\Controllers\Staff\StaffNotificationController;
use App\Models\User;
use App\Services\ProvincialReminderService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProvincialReminderFlowTest extends TestCase
{
    private string $compiledPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compiledPath = base_path('tests/.compiled-views/' . str_replace('\\', '-', static::class) . '-' . $this->name());
        File::deleteDirectory($this->compiledPath);
        File::ensureDirectoryExists($this->compiledPath);
        config()->set('view.compiled', $this->compiledPath);

        Schema::dropAllTables();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('staff');
            $table->string('office')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_authorized')->default(true);
            $table->timestamp('notifications_read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('assigned_provincial_head_id')->nullable();
            $table->string('file_name')->nullable();
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

        Schema::create('office_reminder_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('office');
            $table->text('message')->nullable();
            $table->time('send_time');
            $table->boolean('is_enabled')->default(true);
            $table->date('last_sent_on')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        Schema::create('office_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('office');
            $table->text('message');
            $table->string('type', 20);
            $table->timestamp('triggered_at');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('office_reminder_schedule_id')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->compiledPath);

        parent::tearDown();
    }

    public function test_only_provincial_head_can_access_reminder_page(): void
    {
        $staff = $this->makeUser('staff', 'La Union', 'staff@example.com');
        $request = Request::create(route('admin.dashboard.reminders.index'), 'GET');
        $request->setLaravelSession(app('session')->driver());
        $request->session()->put('authenticated_user_id', $staff->id);

        $response = app(ProvincialReminderController::class)->index($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    public function test_send_now_creates_office_scoped_reminder(): void
    {
        $provincialHead = $this->makeUser('ph-admin', 'Pangasinan', 'pangasinan-head@example.com');

        $response = $this->withSession([
            'authenticated_user_id' => $provincialHead->id,
        ])->post(route('admin.dashboard.reminders.send-now'), [
            'message' => 'Submit your reports before 5 PM.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('office_reminders', [
            'office' => 'Pangasinan',
            'message' => 'Submit your reports before 5 PM.',
            'type' => 'manual',
            'created_by' => $provincialHead->id,
        ]);
    }

    public function test_daily_schedule_dispatches_once_for_the_office(): void
    {
        $provincialHead = $this->makeUser('ph-admin', 'Pangasinan', 'pangasinan-scheduler@example.com');

        app(ProvincialReminderService::class)->saveDailySchedule($provincialHead, [
            'message' => 'Daily reminder for Pangasinan.',
            'send_time' => now()->subMinute()->format('H:i'),
            'is_enabled' => true,
        ]);

        $firstDispatch = app(ProvincialReminderService::class)->dispatchDueReminders('Pangasinan');
        $secondDispatch = app(ProvincialReminderService::class)->dispatchDueReminders('Pangasinan');

        $this->assertSame(1, $firstDispatch);
        $this->assertSame(0, $secondDispatch);
        $this->assertDatabaseCount('office_reminders', 1);
    }

    public function test_provincial_head_dashboard_view_receives_recent_reminder_data(): void
    {
        $provincialHead = $this->makeUser('ph-admin', 'Pangasinan', 'pangasinan-view@example.com');
        app(ProvincialReminderService::class)->sendReminderNow($provincialHead, 'Pangasinan reminder');

        $request = Request::create(route('admin.dashboard.reminders.index'), 'GET');
        $request->setLaravelSession(app('session')->driver());
        $request->session()->put('authenticated_user_id', $provincialHead->id);

        $response = app(ProvincialReminderController::class)->index($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertSame('Pangasinan', $response->getData()['user']->office);
        $this->assertCount(1, $response->getData()['recentReminders']);
        $this->assertSame('Pangasinan reminder', $response->getData()['recentReminders']->first()->message);
    }

    public function test_staff_notifications_include_only_matching_office_reminders(): void
    {
        $provincialHead = $this->makeUser('ph-admin', 'Pangasinan', 'pangasinan-reminder@example.com');
        $pangasinanStaff = $this->makeUser('staff', 'Pangasinan', 'pangasinan-staff@example.com');
        $laUnionStaff = $this->makeUser('staff', 'La Union', 'launion-staff@example.com');

        app(ProvincialReminderService::class)->sendReminderNow($provincialHead, 'Pangasinan reminder');

        $pangasinanRequest = Request::create(route('staff.notifications.index'), 'GET');
        $pangasinanRequest->setLaravelSession(app('session')->driver());
        $pangasinanRequest->session()->put('authenticated_user_id', $pangasinanStaff->id);
        $pangasinanPayload = app(StaffNotificationController::class)->index($pangasinanRequest)->getData(true);

        $laUnionRequest = Request::create(route('staff.notifications.index'), 'GET');
        $laUnionRequest->setLaravelSession(app('session')->driver());
        $laUnionRequest->session()->put('authenticated_user_id', $laUnionStaff->id);
        $laUnionPayload = app(StaffNotificationController::class)->index($laUnionRequest)->getData(true);

        $this->assertCount(1, $pangasinanPayload['notifications']);
        $this->assertSame('office_reminder', $pangasinanPayload['notifications'][0]['type']);
        $this->assertSame('Pangasinan reminder', $pangasinanPayload['notifications'][0]['comment']);
        $this->assertCount(0, $laUnionPayload['notifications']);
    }

    private function makeUser(string $role, string $office, string $email): User
    {
        return User::query()->create([
            'name' => ucfirst($role) . ' User',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => $role,
            'office' => $office,
            'status' => 'active',
            'is_authorized' => true,
            'notifications_read_at' => null,
        ]);
    }
}
