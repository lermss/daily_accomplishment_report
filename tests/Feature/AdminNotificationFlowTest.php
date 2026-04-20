<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\View\Components\Topbar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminNotificationFlowTest extends TestCase
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

        Schema::create('report_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->date('start_date')->nullable();
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

    public function test_provincial_head_topbar_lists_all_pending_notifications_with_modal_links(): void
    {
        $provincialHead = \App\Models\User::query()->create([
            'name' => 'Provincial Head',
            'email' => 'ph@example.com',
            'password' => bcrypt('password'),
            'role' => 'ph-admin',
            'office' => 'La Union',
            'status' => 'active',
            'is_authorized' => true,
        ]);

        $staff = \App\Models\User::query()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'office' => 'La Union',
            'status' => 'active',
            'is_authorized' => true,
        ]);

        $first = \App\Models\Report::query()->create([
            'user_id' => $staff->id,
            'assigned_provincial_head_id' => $provincialHead->id,
            'file_name' => 'Report One',
            'status' => 'pending',
            'submitted_at' => now()->subHour(),
        ]);

        $second = \App\Models\Report::query()->create([
            'user_id' => $staff->id,
            'assigned_provincial_head_id' => $provincialHead->id,
            'file_name' => 'Report Two',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        $topbar = new Topbar(active: 'dashboard', canAccessAudit: true, user: $provincialHead);

        $this->assertSame(route('dashboard.admin'), $topbar->notificationRoute);
        $this->assertCount(2, $topbar->submissionNotifications);
        $this->assertSame(route('dashboard.admin') . '?open_report=' . $second->id, $topbar->submissionNotifications->first()->route);
        $this->assertEqualsCanonicalizing(
            [
                route('dashboard.admin') . '?open_report=' . $first->id,
                route('dashboard.admin') . '?open_report=' . $second->id,
            ],
            $topbar->submissionNotifications->pluck('route')->all()
        );
    }

    public function test_admin_dashboard_carries_auto_open_report_id_for_notification_redirect(): void
    {
        $provincialHead = \App\Models\User::query()->create([
            'name' => 'Provincial Head',
            'email' => 'ph2@example.com',
            'password' => bcrypt('password'),
            'role' => 'ph-admin',
            'office' => 'La Union',
            'status' => 'active',
            'is_authorized' => true,
        ]);

        $staff = \App\Models\User::query()->create([
            'name' => 'Staff User',
            'email' => 'staff2@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'office' => 'La Union',
            'status' => 'active',
            'is_authorized' => true,
        ]);

        $report = \App\Models\Report::query()->create([
            'user_id' => $staff->id,
            'assigned_provincial_head_id' => $provincialHead->id,
            'file_name' => 'Open Me',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        \App\Models\ReportEntry::query()->create([
            'report_id' => $report->id,
            'start_date' => '2026-04-20',
            'end_date' => '2026-04-20',
            'activity' => 'Review',
            'details' => 'Inspect this report',
            'remarks' => 'Pending',
        ]);

        $request = Request::create(route('dashboard.admin', ['open_report' => $report->id]), 'GET', [
            'open_report' => $report->id,
        ]);
        $request->setLaravelSession(app('session')->driver());
        $request->session()->put('authenticated_user_id', $provincialHead->id);

        $response = app(AdminDashboardController::class)->adminDashboard($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertSame((string) $report->id, $response->getData()['autoOpenReportId']);
        $this->assertNotEmpty($response->getData()['reports']);
        $this->assertSame('Open Me', $response->getData()['reports']->first()->file_name);
    }
}
