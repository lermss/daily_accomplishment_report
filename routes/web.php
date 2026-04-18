<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\Shared\HomeController;
use App\Http\Controllers\Shared\MediaController;
use App\Http\Controllers\Shared\ProfileController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\ReportController;
use App\Http\Controllers\Staff\StaffNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
|
| These routes are used for monitoring application and database health
|
*/

Route::controller(HealthCheckController::class)->group(function () {
    Route::get('/health', 'status')->name('health.status');
    Route::get('/health/database', 'database')->name('health.database');
    Route::get('/health/reconnect', 'reconnect')->name('health.reconnect');
});

/*
|--------------------------------------------------------------------------
| Authentication And Shared Entry Points
|--------------------------------------------------------------------------
|
| These routes handle the OTP sign-in flow and shared redirects used by the
| current UI. Route names stay the same so existing links continue to work.
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'showLogin')->name('login');
    Route::post('/', 'sendOtp')->name('auth.send-otp');
    Route::get('/verify-otp', 'showVerifyForm')->name('auth.verify-form');
    Route::post('/verify-otp/resend', 'resendOtp')->name('auth.resend-otp');
    Route::post('/verify-otp', 'verifyOtp')->name('auth.verify');
    Route::match(['get', 'post'], '/logout', 'logout')->name('logout');
});

Route::get('/media/public/{path}', [MediaController::class, 'showPublic'])
    ->where('path', '.*')
    ->name('media.public');

/*
|--------------------------------------------------------------------------
| General Navigation
|--------------------------------------------------------------------------
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard/home', 'homepage')->name('dashboard.home');
    Route::get('/home-page', 'homepage')->name('home_page');
});

Route::redirect('/home/staff', '/staff/home');
Route::redirect('/dashboard/staff', '/staff/dashboard')
    ->middleware('staff.session')
    ->name('dashboard.staff');

/*
|--------------------------------------------------------------------------
| Super Admin And Admin Dashboards
|--------------------------------------------------------------------------
*/

Route::controller(AdminDashboardController::class)->group(function () {
    Route::middleware('role.session:super_admin,hr-super-admin')->group(function () {
        // Super admin monitoring screens.
        Route::get('/dashboard/super-admin', 'superAdminDashboard')->name('dashboard.super-admin');
        Route::prefix('dashboard/super-admin/reports')
            ->name('reports.')
            ->group(function () {
                Route::get('/', 'reportsIndex')->name('index');
                Route::get('/employees', 'reportsEmployees')->name('employees');
                Route::get('/approved', 'reportsApproved')->name('approved');
                Route::get('/pending', 'reportsPending')->name('pending');
                Route::get('/revisions', 'reportsRevisions')->name('revisions');
            });

    });

    Route::middleware('role.session:admin,ph-admin')->group(function () {
        // Admin-only report review screens.
        Route::get('/dashboard/admin', 'adminDashboard')->name('dashboard.admin');

        Route::prefix('dashboard/admin')
            ->name('admin.dashboard.')
            ->group(function () {
                Route::get('/employees', 'adminEmployees')->name('employees');
                Route::get('/approved', 'adminApproved')->name('approved');
                Route::get('/pending', 'adminPending')->name('pending');
                Route::get('/revisions', 'adminRevisions')->name('revisions');
                Route::post('/reports/{report}/status', 'updateReportStatus')->name('reports.status');
                Route::get('/reports/{id}/export-pdf', 'exportReportPDF')->whereNumber('id')->name('reports.export-pdf');
            });
    });
});

/*
|--------------------------------------------------------------------------
| User Management, Audit, And Profile
|--------------------------------------------------------------------------
*/

Route::controller(UserManagementController::class)->group(function () {
    Route::middleware('role.session:admin,ph-admin,super_admin,hr-super-admin')->group(function () {
        Route::get('/dashboard/users', 'users')->name('dashboard.users');
        Route::get('/dashboard/archive', 'archive')->name('dashboard.archive');
        Route::get('/dashboard/active', 'active')->name('dashboard.active');

        Route::prefix('dashboard/users')
            ->name('dashboard.users.')
            ->group(function () {
                // Managed account actions for admin and super admin roles.
                Route::post('/', 'store')->name('store');
                Route::put('/{targetUser}', 'update')->name('update');
                Route::post('/{targetUser}/archive', 'archiveUser')->name('archive');
                Route::post('/{targetUser}/restore', 'restoreUser')->name('restore');
            });
    });
});

Route::get('/audit-log', [AuditController::class, 'index'])
    ->middleware('role.session:admin,ph-admin,super_admin,hr-super-admin')
    ->name('audit.index');

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->middleware('role.session:admin,ph-admin,super_admin,hr-super-admin')
    ->group(function () {
        Route::get('/edit', 'edit')->name('profile.edit');
        Route::post('/edit', 'update')->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Staff Area
|--------------------------------------------------------------------------
*/

Route::get('/legacy/dashboard', [DashboardController::class, 'index'])->name('legacy.dashboard');

Route::middleware('staff.session')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/staff/home', 'staffHome')->name('staff.home');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/staff/dashboard', 'staff')->name('staff.dashboard');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/staff/profile', 'staffProfile')->name('staff.profile');
        Route::put('/staff/profile', 'update')->name('staff.profile.update');
    });

    Route::controller(StaffNotificationController::class)->group(function () {
        // Staff notification routes power the navbar bell modal.
        Route::get('/staff/notifications', 'index')->name('staff.notifications.index');
        Route::post('/staff/notifications/read', 'markAsRead')->name('staff.notifications.read');
    });

Route::controller(ReportController::class)->group(function () {
    Route::get('/staff/reports', 'index')->name('staff.reports');
});

Route::redirect('/staff/reports/index', '/staff/reports')->name('staff.reports.index');

Route::controller(ReportController::class)->prefix('staff/reports')->name('staff.reports.')->group(function () {
    Route::get('/create', 'createReport')->name('create');
    Route::post('/', 'storeReport')->name('store');
    Route::get('/{id}', 'show')->whereNumber('id')->name('show');
    Route::put('/{id}', 'update')->whereNumber('id')->name('update');
    Route::put('/{id}/file-name', 'updateFile')->whereNumber('id')->name('updateFile');
    Route::delete('/{id}', 'destroy')->whereNumber('id')->name('destroy');
    Route::get('/{id}/pdf', 'exportPDF')->whereNumber('id')->name('pdf');
    Route::post('/{id}/submit', 'submit')->whereNumber('id')->name('submit');
});

    });

    Route::redirect('/staff/reports/index', '/staff/reports')->name('staff.reports.index');


/*
|--------------------------------------------------------------------------
| Legacy Route Aliases
|--------------------------------------------------------------------------
*/

Route::redirect('/login', '/');
Route::redirect('/signin', '/')->name('signin');
Route::redirect('/home', '/dashboard/home');
Route::redirect('/admin/login', '/')->name('admin.login');
Route::redirect('/super-admin/login', '/')->name('super_admin.superAdmin.login');
Route::redirect('/admin/verify-otp', '/verify-otp')->name('admin.verify-otp');
Route::redirect('/super-admin/verify-otp', '/verify-otp')->name('super_admin.superAdmin.verify-otp');
Route::redirect('/admin/dashboard', '/dashboard/admin')->name('admin.dashboard');
Route::redirect('/super-admin/dashboard', '/dashboard/super-admin')->name('super_admin.superAdmin.dashboard');
Route::middleware('2fa.pending')->group(function () {
    Route::get('/2fa/verify', [AuthController::class, 'show2faForm'])->name('auth.2fa.verify.form');
    Route::post('/2fa/verify', [AuthController::class, 'verify2fa'])->name('auth.2fa.verify');
});

Route::get('/2fa/setup', [AuthController::class, 'setup2fa'])->name('auth.2fa.setup');
Route::post('/2fa/enable', [AuthController::class, 'enable2fa'])->name('auth.2fa.enable');
Route::post('/2fa/disable', [AuthController::class, 'disable2fa'])->name('auth.2fa.disable');
