# Backend Code Area: `routes/web.php`

## Purpose

`routes/web.php` is the backend entry-point map for web requests. It connects URLs to controllers, assigns route names used by redirects/views, and applies role/session middleware.

## Route Groups

| Group | Main middleware | Controllers | Purpose |
| --- | --- | --- | --- |
| Health checks | global middleware only | `HealthCheckController` | Health, database status, reconnect |
| Authentication | global middleware only | `AuthController` | Login, Google Authenticator challenge, logout, legacy OTP redirects |
| Public media | authenticated inside controller | `MediaController` | Serve files from public storage |
| General navigation | controller auth checks | `HomeController` | Dashboard redirects and home page |
| Super admin dashboards | `role.session:super_admin,hr-super-admin` | `AdminDashboardController`, `SuperAdminNotificationController`, `AuthenticatorAuthorizationController` | Super admin reports, notifications, authenticator access |
| Admin dashboards | `role.session:admin,ph-admin` | `AdminDashboardController` | Admin/PH admin report dashboards and review |
| PH admin reminders | `role.session:ph-admin` | `ProvincialReminderController` | Daily reminder schedule and send-now |
| PH admin notifications | `role.session:ph-admin` | `NotificationController` | Office report-submission inbox |
| User management | `role.session:admin,ph-admin,super_admin,hr-super-admin` | `UserManagementController` | User list, create, update, archive, restore |
| Audit | role middleware | `AuditController` | Audit log views |
| Admin/super admin profile | `role.session:admin,ph-admin,super_admin,hr-super-admin` | `ProfileController` | Profile edit/update |
| Staff/intern area | `staff.session` | `HomeController`, `DashboardController`, `ProfileController`, `StaffNotificationController`, `ReportController` | Staff/intern home, dashboard, profile, notifications, reports |
| Legacy aliases | varies | redirects / `AuthController` | Compatibility routes for old URLs |

## Important Route Notes

- Staff and intern report routes mirror each other and both use `Staff\ReportController`.
- Intern routes are inside the `staff.session` middleware group, and `EnsureStaffSession` accepts both `staff` and `interns`.
- `/2fa/verify` routes are protected by `2fa.pending`.
- `/2fa/disable` is not wrapped in route middleware, but `AuthController::disable2fa()` checks authentication internally.
- `/health`, `/health/database`, and `/health/reconnect` are not auth-protected in `routes/web.php`.
- `/media/public/{path}` is route-public, but `MediaController::showPublic()` calls `AuthFlowService::requireAuthenticated()`.
- There is a duplicate `Route::redirect('/staff/reports/index', '/staff/reports')` definition.
- Route comments such as `ADD THIS CODE` indicate recent additions and are not behavior by themselves.

## Route Documentation Checklist

- [x] Health routes
- [x] Authentication routes
- [x] General navigation routes
- [x] Super admin dashboard/report routes
- [x] Admin report review routes
- [x] User management routes
- [x] Audit route
- [x] Profile routes
- [x] Staff/intern dashboard routes
- [x] Staff/intern report routes
- [x] Staff/intern notification routes
- [x] Legacy redirect routes
