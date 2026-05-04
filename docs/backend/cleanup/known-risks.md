# Backend Known Risks and Cleanup Notes

## Purpose

This document records backend code areas that look legacy, duplicated, disconnected, risky, or likely to need cleanup before production hardening. It is based on the current code in `routes/web.php`, `app/`, and the backend documentation set.

## Summary Checklist

- [x] Unused or legacy controllers
- [x] Duplicate/legacy routes
- [x] Functions not connected to routes
- [x] Role/session edge cases
- [x] Database columns with unclear ownership
- [x] Service methods that need ownership checks
- [x] Existing docs that need updating

## Unused Or Legacy Controllers

### `App\Http\Controllers\Admin\AdminController`

Status: legacy placeholder.

Evidence:

- Not imported or referenced by `routes/web.php`.
- Most methods return `admin.placeholder`, `back()->with('error', ...)`, or HTTP 501.
- Controller message says the original controller was missing and this placeholder prevents route failures.

Cleanup options:

- Remove if no old route file or view references it.
- Keep only if older routes outside `routes/web.php` still need it.
- If kept, mark it explicitly as deprecated in a class-level comment.

### `App\Http\Controllers\Auth\AdminAuthController`

Status: legacy/restored placeholder.

Evidence:

- Not imported or referenced by `routes/web.php`.
- Registration and OTP methods return placeholder/unavailable messages.
- Current auth flow uses `AuthController` and Google Authenticator.
- Legacy route names such as `admin.login` and `admin.verify-otp` are redirects to the current auth flow.

Cleanup options:

- Remove if no external route references remain.
- Or keep as deprecated compatibility code until old links are confirmed unused.

## Duplicate / Legacy Routes

### Duplicate Staff Reports Index Redirect

Route appears twice:

```php
Route::redirect('/staff/reports/index', '/staff/reports')->name('staff.reports.index');
```

Risk:

- Duplicate route names can cause confusion during route generation or route-list review.
- The later definition may override route name registration depending on Laravel route loading behavior.

Cleanup:

- Keep one definition inside the staff route area.
- Remove the duplicate after confirming route list output.

### Legacy Auth And Dashboard Aliases

Legacy redirects:

- `/login` -> `/`
- `/signin` -> `/`
- `/home` -> `/dashboard/home`
- `/admin/login` -> `/`
- `/super-admin/login` -> `/`
- `/admin/verify-otp` -> `/verify-otp`
- `/super-admin/verify-otp` -> `/verify-otp`
- `/admin/dashboard` -> `/dashboard/admin`
- `/super-admin/dashboard` -> `/dashboard/super-admin`

Risk:

- Helpful for old links, but can make the true auth/dashboard flow harder to trace.
- Some middleware redirects still point to legacy route names like `admin.login` and `super_admin.superAdmin.login`, which are now redirect aliases.

Cleanup:

- Keep if users/bookmarks rely on them.
- Document them as compatibility aliases.
- If removing, update middleware redirect route names first.

### Legacy Dashboard Route

Route:

```php
Route::get('/legacy/dashboard', [DashboardController::class, 'index'])->name('legacy.dashboard');
```

Risk:

- It redirects authenticated users to `dashboard.home`.
- May be unnecessary once old frontend links are removed.

Cleanup:

- Search frontend views/JS for `legacy.dashboard` or `/legacy/dashboard`.
- Remove if unused.

## Functions Not Connected To Routes

### `ReportController::storeEntry()`

Status: public controller method, no route in `routes/web.php`.

Behavior:

- Validates one report entry payload.
- Creates `ReportEntry` directly.
- Returns JSON.

Risk:

- It validates `report_id` exists but does not check that the report belongs to the current staff/intern user.
- If routed later without adding ownership checks, a user could create entries under another user's report.

Cleanup:

- Remove if no frontend uses it.
- If needed for AJAX entry creation, add route under `staff.session` and check report ownership before creating.

### Placeholder Controller Methods

Many `AdminController` and `AdminAuthController` methods are intentionally non-functional. They are not connected to `routes/web.php`, but they could confuse future maintainers.

Cleanup:

- Remove deprecated controllers or move their purpose into docs/comments.

## Role / Session Edge Cases

### `AuthController::disable2fa()` Route Has No Middleware

Route:

```php
Route::post('/2fa/disable', [AuthController::class, 'disable2fa'])->name('auth.2fa.disable');
```

Current protection:

- Method calls `AuthFlowService::authenticatedUser()`.
- Unauthenticated users are redirected to login.

Risk:

- Route-level intent is less clear than the rest of auth-protected routes.

Cleanup:

- Add explicit auth/session middleware or document why method-level auth is enough.

### Health Routes Are Public

Routes:

- `/health`
- `/health/database`
- `/health/reconnect`

Risk:

- Exposes environment/debug flag and database host/port/database values.
- `/health/reconnect` uses GET and can trigger reconnect attempts from a browser or crawler.

Cleanup:

- Decide whether health endpoints are internal-only.
- Consider protecting detailed database output in production.
- Consider making reconnect POST-only or admin-only.

### `/intern/audit-log` Uses Audit Controller

Route:

```php
Route::get('/intern/audit-log', [AuditController::class, 'index'])
    ->middleware('role.session:interns')
    ->name('intern.audit.index');
```

Risk:

- `AuditController` and `AdminPortalService::buildAuditData()` are primarily admin-oriented.
- Intern access to audit data may be unintended or may need special scoping.

Cleanup:

- Confirm product requirement.
- If interns need audit data, create a scoped intern audit view/service path.
- If not needed, remove the route.

### Admin/Super Admin Route Naming Is Mixed

Examples:

- `dashboard.super-admin`
- `super_admin.superAdmin.login`
- `super_admin.superAdmin.dashboard`
- `reports.index`
- `admin.dashboard.*`

Risk:

- Mixed naming styles make redirects harder to reason about.

Cleanup:

- Keep compatibility aliases, but standardize new route names.

## Database Columns With Unclear Ownership

### `users.department` vs `users.bureau`

Current behavior:

- Migration creates `department`.
- Later migration adds `bureau`.
- `User` model maps `department` accessor/mutator to `bureau`.

Risk:

- Two columns may exist with overlapping meaning.
- Reads/writes through Eloquent `department` do not necessarily reflect raw database `department`.

Cleanup:

- Decide canonical field.
- Migrate old data if needed.
- Remove or ignore the legacy column intentionally.

### `users.avatar_path` vs `users.user_avatar_path`

Current behavior:

- `avatar_path` is used by profile code.
- `user_avatar_path` is added by migration `2026_03_24_062325_add_user_avatar_path_to_users_table.php`.
- Its rollback is empty.

Risk:

- Unclear which avatar column should be used.
- No-op rollback creates schema drift.

Cleanup:

- Confirm whether `user_avatar_path` is used anywhere.
- Drop or document it.
- Fix rollback in a new migration if needed.

### `activity_logs.role` and `activity_logs.ip_address`

Current behavior:

- `ActivityLog` model fillable includes `role` and `ip_address`.
- Current migrations in this repo do not add those columns.
- `AdminPortalService::logActivity()` only writes columns that exist.

Risk:

- Model suggests fields exist, but database may not.
- Audit UI has defensive checks, which can hide schema drift.

Cleanup:

- Add migration for intended columns or remove them from model fillable/docs.

### Foreign-Key-Like Columns Without Constraints

Examples:

- `reports.user_id`
- `reports.reviewed_by`
- `reports.assigned_provincial_head_id`
- `activity_logs.user_id`
- `office_reminders.created_by`
- `office_reminder_schedules.created_by`
- `office_reminders.office_reminder_schedule_id`

Risk:

- Orphaned rows are possible.
- Deletes/archives may not preserve relationship integrity.

Cleanup:

- Decide whether to enforce constraints.
- Add foreign keys carefully with cleanup migration for existing orphan data.

## Service Methods That Need Ownership Checks

### `ReportWorkflowService::updateReport()`

Current behavior:

- Controller loads an owned report first.
- Service accepts `entry_id` values and updates `ReportEntry::where('id', $entryId)`.
- Service does not confirm each `entry_id` belongs to the passed report.

Risk:

- If a malicious request includes another report's entry id, the service could update that entry.

Cleanup:

- Change update query to include `report_id`:

```php
ReportEntry::where('id', $entryId)
    ->where('report_id', $report->id)
    ->update($entryPayload);
```

### `ReportController::storeEntry()`

Current behavior:

- Directly creates a `ReportEntry` by submitted `report_id`.
- No ownership check.

Cleanup:

- Remove or add ownership check before routing.

### `AdminDashboardController::exportReportPDF()`

Current behavior:

- Authenticates user.
- Loads report by id.
- Comment says admin can export any report.
- Does not apply PH Admin office/assignment scoping.

Risk:

- PH Admin route middleware allows `ph-admin`; a PH Admin may export reports outside their assigned office if they know the id.

Cleanup:

- Apply `ProvincialHeadAssignmentService::canReviewReport()` or separate export authorization.

### `AdminDashboardController::bulkDelete()`

Current behavior:

- Scopes reports through `scopeReportsForReviewer()`.
- Only hides approved reports.

Risk:

- Safer than export, but no explicit validation that submitted ids are integers.

Cleanup:

- Validate `report_ids` as array of existing integer ids.

## Existing Docs That Need Updating

### Root `README.md`

Current state:

- Contains revision notes and has mojibake/encoding artifacts.
- Does not explain setup, backend docs, tests, or current auth flow.

Cleanup:

- Replace or supplement with project setup and documentation links.

### Existing Error Handling Docs

Files:

- `DATABASE_ERROR_HANDLING.md`
- `README_ERROR_HANDLING.md`
- `ERROR_HANDLING_QUICK_REFERENCE.md`
- `INTEGRATION_CHECKLIST.md`
- `IMPLEMENTATION_SUMMARY.md`

Risk:

- These may describe older implementation details.
- They should be checked against `DatabaseErrorService`, `DatabaseErrorHelper`, `CheckDatabaseConnection`, and `bootstrap/app.php`.

Cleanup:

- Review and either update, archive, or link from backend docs.

### Backend Docs Created In This Pass

New docs are process/reference-level and should be updated when code changes:

- `docs/backend/report-workflow.md`
- `docs/backend/auth-and-session-flow.md`
- `docs/backend/admin-report-review-flow.md`
- `docs/backend/user-profile-audit-flow.md`
- `docs/backend/notifications-flow.md`
- `docs/backend/provincial-flows.md`
- `docs/backend/health-and-media-flow.md`
- `docs/backend/code-areas/*.md`
- `docs/backend/database/*.md`
- `docs/backend/tests/*.md`

Maintenance rule:

- When a route, controller method, validation rule, migration, or test changes, update the corresponding doc in the same PR/change batch.

## Suggested Cleanup Order

1. Fix ownership/security risks:
   - `ReportWorkflowService::updateReport()`
   - `ReportController::storeEntry()` or remove it
   - `AdminDashboardController::exportReportPDF()` scoping

2. Clean route duplication:
   - duplicate `/staff/reports/index`
   - confirm `/intern/audit-log`
   - confirm `/legacy/dashboard`

3. Decide legacy controller fate:
   - `AdminController`
   - `AdminAuthController`

4. Clarify schema ownership:
   - `department` vs `bureau`
   - `avatar_path` vs `user_avatar_path`
   - missing/extra activity log columns

5. Update old root docs.
