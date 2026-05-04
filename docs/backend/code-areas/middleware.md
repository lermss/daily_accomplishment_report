# Backend Code Area: Middleware

## Registered Middleware

Defined in `bootstrap/app.php`:

- Prepended: `CheckDatabaseConnection`
- Appended: `SetSecurityHeaders`
- Aliases:
  - `admin.session`
  - `admin.register`
  - `role.session`
  - `staff.session`
  - `2fa.pending`

## Middleware Reference

| Middleware | Function | Details |
| --- | --- | --- |
| `CheckDatabaseConnection` | `handle()` | Checks DB PDO before request continues. On `QueryException` or `PDOException`, logs critical error and returns `errors.database-error` with HTTP 503. |
| `EnsureAdminRegistrationAllowed` | `handle()` | Allows first admin bootstrap when no admin roles exist. Otherwise requires authenticated admin role. |
| `EnsureAdminRegistrationAllowed` | `loginRoute()` | Private. Chooses admin or super-admin login route by path. |
| `EnsureAdminSession` | `handle()` | Requires `authenticated_user_id` and `User::isAdminRole()`, otherwise flushes session and redirects. |
| `EnsureAdminSession` | `loginRoute()` | Private. Chooses admin or super-admin login route by path. |
| `EnsurePendingTwoFactorChallenge` | `handle()` | Requires pending `2fa:user:id`, otherwise redirects to login with expired challenge message. |
| `EnsureRoleSession` | `handle()` | Requires authenticated user whose role is in middleware arguments. Invalid access flushes session. |
| `EnsureRoleSession` | `loginRoute()` | Private. Sends super-admin role groups to super admin login; others to admin login. |
| `EnsureStaffSession` | `handle()` | Requires authenticated user with role `staff` or `interns`; invalid access flushes session. |
| `SetSecurityHeaders` | `handle()` | Adds frame/content/referrer/permissions headers when enabled; adds HSTS on secure requests; disables cache on admin login/verify pages. |

## Notes

- `CheckDatabaseConnection` runs early on every request because it is prepended globally.
- `SetSecurityHeaders` can be disabled through `SECURITY_HEADERS_ENABLED=false`.
- HSTS depends on secure requests and `SECURITY_HSTS_ENABLED=true`.
