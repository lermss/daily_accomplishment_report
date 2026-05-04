# Backend Code Area: Controllers

## Purpose

Controllers receive web requests, validate simple inputs, call services/models, choose views or redirects, and return JSON/PDF/file responses.

## Base Controller

### `App\Http\Controllers\Controller`

Base Laravel controller class. It currently has no custom methods.

## Health Controllers

### `HealthCheckController`

| Function | Details |
| --- | --- |
| `status()` | Calls `DatabaseErrorService::getConnectionStatus()` and returns overall app/database health JSON. Returns HTTP 200 if database is connected, 503 otherwise. |
| `database()` | Returns database-only connection JSON: connected, host, port, database, and optional error. |
| `reconnect()` | Calls `DatabaseErrorService::attemptReconnect()` and returns success/message JSON with 200 or 503. |

## Auth Controllers

### `AuthController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, AdminPortalService)` | Injects auth/session role helper and activity/profile service. |
| `showLogin()` | Returns `auth.signin`. |
| `sendOtp(Request)` | Despite the name, starts Google Authenticator login. Validates email, finds active managed user, blocks unauthorized or non-2FA-ready accounts, stores `2fa:user:id`, redirects to `auth.2fa.verify.form`. |
| `showVerifyForm(Request)` | Requires pending `2fa:user:id`, validates user still exists and has 2FA enabled, then returns `auth.verify-2fa` with email. |
| `resendOtp(Request)` | Legacy email OTP endpoint. Redirects to login with message that email OTP is inactive. |
| `verifyOtp(Request)` | Legacy email OTP endpoint. Redirects to login with message that email OTP is inactive. |
| `verify2fa(Request)` | Validates 6-digit code, verifies it with Google2FA, sets first-confirmed timestamp when needed, clears pending key, and completes login. |
| `logout(Request)` | Logs activity, invalidates session, regenerates token, redirects to login. |
| `disable2fa(Request)` | Requires authenticated user, clears Google Authenticator fields for that user, redirects to dashboard. |
| `completeLogin(Request, User)` | Private. Regenerates session, stores `authenticated_user_id`, logs login, redirects by role. |
| `hasGoogle2faEnabled(User)` | Private. Checks authorization, `google2fa_enabled`, and readable secret. |
| `google2faSecret(User)` | Private. Reads `google2fa_secret`, decrypts when possible, falls back to stored value. |

### `AdminAuthController`

Legacy/restored placeholder admin auth controller.

| Function | Details |
| --- | --- |
| `showAdminLogin()` | Returns the shared login view through `loginView()`. |
| `showLogin()` | Returns the shared login view through `loginView()`. |
| `showRegister()` | Returns `admin.placeholder` for unavailable admin registration UI. |
| `register(Request)` | Redirects back with error that admin registration is not available. |
| `sendOtp(Request)` | Validates email, finds user, requires `User::isAdminRole()`, stores `otp_email`, redirects to placeholder verify route. |
| `showVerifyOtp(Request)` | Returns `admin.placeholder` with pending `otp_email`. |
| `verifyOtp(Request)` | Redirects to login route with error that OTP verification is unavailable. |
| `loginView()` | Private. Returns `auth.signin`. |
| `loginRoute(Request)` | Private. Chooses `admin.login` for admin URLs, otherwise `super_admin.superAdmin.login`. |
| `verifyOtpRoute(Request)` | Private. Chooses admin or super-admin verify route by current route/path. |

## Shared Controllers

### `HomeController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService)` | Injects auth helper. |
| `dashboard(Request)` | Requires authenticated active/authorized user, then redirects to the role-specific dashboard route. |
| `homepage(Request)` | Requires authenticated user and returns `home_page` with user and audit-access flag. |
| `staffHome(Request)` | Same view/data as `homepage()`, used by staff/intern home routes. |

### `MediaController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService)` | Injects auth helper. |
| `showPublic(Request, string $path)` | Requires authentication, normalizes the storage path, blocks empty/path traversal/missing files, and returns the file from public disk. |

### `ProfileController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, AdminPortalService)` | Injects auth and profile service. |
| `edit(Request)` | Requires authenticated user and returns admin profile edit view with profile options/data. |
| `staffProfile(Request)` | Requires authenticated user and returns staff profile view with same profile data builder. |
| `update(Request)` | Validates profile fields and optional image uploads, delegates update to `AdminPortalService`, redirects to role-aware profile route. |
| `authenticatedUser(Request, ?callable)` | Private wrapper around `AuthFlowService::requireAuthenticated()`. |

## Admin Controllers

### `AdminController`

Legacy placeholder controller.

| Function | Details |
| --- | --- |
| `home(Request)` | Redirects admin URLs to `admin.dashboard`, otherwise to super admin dashboard. |
| `dashboard()` | Returns placeholder Dashboard view. |
| `users()` | Returns placeholder Users view. |
| `storeUser(Request)` | Redirects back with unavailable error. |
| `updateUser(Request, mixed $user)` | Redirects back with unavailable error. |
| `resetUser(Request, mixed $user)` | Redirects back with unavailable password reset error. |
| `reports()` | Returns placeholder Reports view. |
| `updateReportStatus(Request, mixed $report)` | Redirects back with unavailable report review error. |
| `archiveReportUser(Request, mixed $report)` | Redirects back with unavailable archive error. |
| `downloadReport(mixed $report)` | Returns HTTP 501 plain response that download is unavailable. |
| `activityLogs()` | Returns placeholder Activity Logs view. |
| `settings()` | Returns placeholder Settings view. |
| `profile()` | Returns placeholder Profile view. |
| `updateProfile(Request)` | Redirects back with unavailable profile update error. |
| `logout(Request)` | Invalidates session and redirects to admin/super-admin login route. |
| `placeholder(string $title)` | Private. Returns `admin.placeholder` with standard missing-feature message. |
| `loginRoute(Request)` | Private. Chooses admin or super-admin login route by path. |

### `AdminDashboardController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, AdminPortalService, ProvincialHeadAssignmentService)` | Injects auth, dashboard/report data service, and review-scoping service. |
| `superAdminDashboard(Request)` | Renders super admin dashboard mode. |
| `adminDashboard(Request)` | Renders admin dashboard mode. |
| `adminEmployees(Request)` | Renders admin employee reports mode. |
| `adminApproved(Request)` | Renders admin approved reports mode. |
| `adminPending(Request)` | Renders admin pending reports mode. |
| `adminRevisions(Request)` | Renders admin for-revision reports mode. |
| `reportsIndex(Request)` | Renders super admin reports table mode. |
| `reportsEmployees(Request)` | Renders super admin employees mode. |
| `reportsApproved(Request)` | Renders super admin approved mode. |
| `reportsPending(Request)` | Renders super admin pending mode. |
| `reportsRevisions(Request)` | Renders super admin revisions mode. |
| `updateReportStatus(Request, Report)` | Validates status/comment, checks review authorization, updates report review state, logs commented reviews, returns JSON for modal requests or redirects back. |
| `exportReportPDF(Request, int $id)` | Requires auth, loads report with entries, renders `staff.reports.pdf` using DomPDF, downloads PDF. |
| `renderAdminReports(Request, string $mode)` | Private. Requires auth and returns `admin.reports` with built admin dashboard data. |
| `renderSuperAdminReports(Request, string $mode)` | Private. Requires auth and returns either `admin.dashboard` or `super_admin.reports-table`. |
| `authenticatedUser(Request, ?callable)` | Private wrapper around `AuthFlowService::requireAuthenticated()`. |
| `bulkDelete(Request)` | Hides selected approved reports from admin dashboard, scoped by reviewer, then permanently deletes reports hidden from all views. |
| `permanentlyDeleteFullyHiddenReports()` | Private. Deletes reports where all three hide flags are true. |

### `AuditController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, AdminPortalService)` | Injects auth and audit data service. |
| `index(Request)` | Requires authenticated user and returns `admin.audit-log` using `AdminPortalService::buildAuditData()`. |

### `AuthenticatorAuthorizationController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, AdminPortalService)` | Injects auth and activity logging service. |
| `index(Request)` | Requires super admin, filters/searches users, returns authenticator authorization view. |
| `authorize(Request, User $targetUser)` | Requires super admin, validates target active/not `super_admin`, generates/reuses 2FA secret, encrypts it, enables authorization/2FA, sends provisioning email, logs activity. |
| `revoke(Request, User $targetUser)` | Requires super admin, sets `is_authorized` false and clears authorization-code fields, logs activity. |
| `superAdminUser(Request)` | Private. Requires authenticated super admin role. |
| `buildQrImage(string $email, string $secret)` | Private. Attempts to build inline Google2FA QR image; returns null on failure. |
| `existingSecret(User)` | Private. Reads existing Google2FA secret, decrypting when possible. |

### `NotificationController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService)` | Injects auth helper. |
| `index(Request)` | Requires `ph-admin`, loads report submissions from same office, marks notifications read, returns `admin.notifications`. |
| `markAsRead(Request)` | Requires admin/super-admin role, updates `notifications_read_at`, returns JSON success. |

### `ProvincialReminderController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, ProvincialReminderService)` | Injects auth and reminder service. |
| `index(Request)` | Requires PH Admin, dispatches due reminders, loads schedule/recent reminders, returns `admin.reminders`. |
| `saveSchedule(Request)` | Requires PH Admin, validates message/time/enabled flag, saves daily schedule, redirects back. |
| `sendNow(Request)` | Requires PH Admin, validates optional message, creates manual reminder, redirects back. |
| `provincialHeadUser(Request)` | Private. Requires authenticated `ph-admin`. |

### `SuperAdminNotificationController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, SuperAdminNotificationService)` | Injects auth and notification service. |
| `index(Request)` | Requires super admin, refreshes summary notifications, returns notification center view. |
| `markRead(Request, SuperAdminNotification)` | Requires super admin, marks one notification read, redirects back. |
| `markAllRead(Request)` | Requires super admin, marks all unread notifications read, redirects back. |
| `superAdminUser(Request)` | Private. Requires authenticated super admin role. |

### `UserManagementController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, AdminPortalService)` | Injects auth and user-management service. |
| `users(Request)` | Renders managed users dashboard mode. |
| `archive(Request)` | Renders archived users mode. |
| `active(Request)` | Renders active users mode. |
| `store(Request)` | Authenticates actor, validates role/name/email/details, creates managed user, redirects back. |
| `update(Request, User $targetUser)` | Authenticates actor, validates input, updates managed user, redirects back. |
| `archiveUser(Request, User $targetUser)` | Blocks self-archive, archives target user, redirects back. |
| `restoreUser(Request, User $targetUser)` | Restores target user to active, redirects back. |
| `renderDashboard(Request, string $mode)` | Private. Returns `admin.users` with dashboard data. |
| `detailRules(array $selectedConfig)` | Private. Builds role-specific validation rules from `userFormOptions()`. |
| `buildDisplayName(array $validated)` | Private. Joins first/middle/last name. |
| `officeUsers(Request)` | Requires PH Admin, returns same-office staff/interns view. |
| `authenticatedUser(Request, ?callable)` | Private wrapper around `AuthFlowService::requireAuthenticated()`. |

## Staff Controllers

### `DashboardController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService)` | Injects auth helper. |
| `index(Request)` | Redirects unauthenticated users to login; authenticated users to `dashboard.home`. |
| `staff(Request)` | Requires staff/intern, builds dashboard report table with status/search filters and summary counts, returns `staff.dashboard`. |
| `bulkDelete(Request)` | Requires staff/intern, hides selected owned reports from staff dashboard. |

### `ReportController`

Detailed process documentation exists in [Staff and Intern Report Workflow](../report-workflow.md).

| Function | Details |
| --- | --- |
| `__construct(ReportWorkflowService)` | Injects report workflow service. |
| `index(Request)` | Lists current user's visible reports with optional search. |
| `update(UpdateReportRequest, int $id)` | Updates entries for an owned report and unhides pending/revision reports from index. |
| `updateFile(Request, int $id)` | Validates and updates only `file_name`. |
| `createReport()` | Returns create report form view. |
| `storeReport(StoreReportRequest)` | Creates draft report and entries, redirects to role-aware reports route. |
| `show(Request, int $id)` | Shows owned report with entries. |
| `exportPDF(Request, int $id)` | Exports owned draft/approved report as PDF. |
| `pdf(Request, int $id)` | Alias for `exportPDF()`. |
| `destroy(Request, int $id)` | Hides owned report from staff/intern reports index. |
| `submit(Request, int $id)` | Submits owned report to assigned Provincial Head and redirects to dashboard. |
| `storeEntry(Request)` | Creates one entry and returns JSON; not wired in `routes/web.php`. |
| `resolveStaffUser(Request)` | Private. Reads `authenticated_user_id` and returns user. |
| `normalizeOptionalText(?string, ?string)` | Private. Trims optional text and applies default. |
| `findOwnedReport(Request, int, array)` | Private. Loads report by id and current user id. |

### `StaffNotificationController`

| Function | Details |
| --- | --- |
| `__construct(AuthFlowService, ProvincialReminderService)` | Injects auth and reminder service. |
| `index(Request)` | Requires staff/intern, combines report review notifications and office reminders, returns JSON. |
| `markAsRead(Request)` | Requires staff/intern, updates `notifications_read_at`, returns JSON success. |
| `requireStaffUser(Request)` | Private. Requires authenticated staff/intern and returns JSON 401 on failure. |
| `unreadCount(User)` | Private. Counts unread report review notifications plus unread reminders. |
| `hasNotificationsReadColumn()` | Private. Safely checks for `users.notifications_read_at`. |
