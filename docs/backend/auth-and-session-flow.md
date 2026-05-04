# Backend Documentation: Authentication, 2FA, Logout, and Session Middleware

## Purpose

This flow controls sign-in, Google Authenticator verification, session creation, logout, 2FA disabling, and route access checks for staff, interns, admins, Provincial Head admins, and HR Super Admins.

Main files:

- `routes/web.php`
- `app/Http/Controllers/Auth/AuthController.php`
- `app/Services/AuthFlowService.php`
- `app/Http/Middleware/EnsureStaffSession.php`
- `app/Http/Middleware/EnsureRoleSession.php`
- `app/Http/Middleware/EnsurePendingTwoFactorChallenge.php`
- `app/Http/Middleware/EnsureAdminSession.php`

## Route Map

| Method | URI | Route name | Controller/middleware | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/` | `login` | `AuthController@showLogin` | Show sign-in page |
| `POST` | `/` | `auth.send-otp` | `AuthController@sendOtp` | Start Google Authenticator login challenge |
| `GET` | `/verify-otp` | `auth.verify-form` | `AuthController@showVerifyForm` | Legacy verify route, now shows 2FA form if pending |
| `POST` | `/verify-otp/resend` | `auth.resend-otp` | `AuthController@resendOtp` | Legacy email OTP resend, now disabled |
| `POST` | `/verify-otp` | `auth.verify` | `AuthController@verifyOtp` | Legacy email OTP verify, now disabled |
| `GET` | `/2fa/verify` | `auth.2fa.verify.form` | `2fa.pending`, `AuthController@showVerifyForm` | Show Google Authenticator code form |
| `POST` | `/2fa/verify` | `auth.2fa.verify` | `2fa.pending`, `AuthController@verify2fa` | Verify 6-digit Google Authenticator code |
| `POST` | `/2fa/disable` | `auth.2fa.disable` | `AuthController@disable2fa` | Disable Google Authenticator for current signed-in user |
| `GET/POST` | `/logout` | `logout` | `AuthController@logout` | Log out and destroy session |

## Login Process

1. User opens `/`.
2. `AuthController::showLogin()` returns `auth.signin`.
3. User submits an email to `POST /`.
4. `sendOtp()` validates `email` as required and valid email format.
5. `AuthFlowService::findManagedActiveUserByEmail()` searches active users by lowercase email and allowed roles:
   - `super_admin`
   - `hr-super-admin`
   - `admin`
   - `ph-admin`
   - `staff`
   - `interns`
6. If a database `QueryException` occurs, the user is redirected to `login` with a database credentials error.
7. If no user exists or `is_authorized` is false, login is blocked.
8. If Google Authenticator is not enabled or no secret exists, login is blocked.
9. Session key `2fa:user:id` is set to the user id.
10. User is redirected to `auth.2fa.verify.form`.

## Google Authenticator Verification Process

1. `EnsurePendingTwoFactorChallenge` requires session key `2fa:user:id`.
2. `showVerifyForm()` loads the pending user.
3. If the pending user is missing or does not have enabled 2FA, `2fa:user:id` is removed and the user is redirected to login.
4. The view `auth.verify-2fa` receives `userEmail`.
5. User submits `code` to `POST /2fa/verify`.
6. `verify2fa()` validates `code` as required and exactly 6 digits.
7. The pending user is loaded again.
8. `google2faSecret()` reads `google2fa_secret`.
9. The secret is decrypted with `Crypt::decryptString()` when possible; if decryption fails, the stored value is used as plain text fallback.
10. `Google2FA::verifyKey($secret, code, 1)` verifies the code with a window of 1.
11. Invalid code returns back with validation error on `code`.
12. On first successful confirmation, these user fields are updated:
   - `two_factor_confirmed_at`: current timestamp
   - `google2fa_authorization_code_hash`: `null`
   - `google2fa_authorization_code_expires_at`: `null`
13. `2fa:user:id` is removed.
14. `completeLogin()` creates the authenticated session.

## Session Creation

`AuthController::completeLogin()`:

1. Regenerates the session id.
2. Stores `authenticated_user_id` in session.
3. Removes `2fa:user:id`.
4. Logs activity through `AdminPortalService::logActivity($user, 'login', 'User signed in successfully.')`.
5. Redirects by role:

| Role | Redirect route |
| --- | --- |
| `staff` | `home_page` |
| `admin` | `home_page` |
| `ph-admin` | `home_page` |
| `hr-super-admin` | `home_page` |
| `interns` | `intern.home` |
| Other known roles | `AuthFlowService::dashboardRoute($role)` |

## Legacy OTP Behavior

Email OTP is no longer active.

| Method | Behavior |
| --- | --- |
| `resendOtp()` | Redirects to login with message: email OTP login is no longer active |
| `verifyOtp()` | Redirects to login with message: email OTP login is no longer active |

## Logout Process

1. `logout()` loads the current authenticated user through `AuthFlowService::authenticatedUser()`.
2. Logs activity as `logout`.
3. Invalidates the session.
4. Regenerates CSRF token.
5. Redirects to `login`.

## Disable 2FA Process

1. `disable2fa()` loads the current authenticated user.
2. If no user is authenticated, redirect to `login`.
3. The following columns are reset:
   - `google2fa_secret`
   - `google2fa_enabled`
   - `two_factor_confirmed_at`
   - `google2fa_authorization_code_hash`
   - `google2fa_authorization_code_expires_at`
   - `google2fa_authorization_sent_at`
4. User is redirected to `dashboard` with status message.

## AuthFlowService Function Details

| Method | Responsibility |
| --- | --- |
| `authenticatedUser()` | Reads `authenticated_user_id` from session and returns the matching user, or `null` |
| `requireAuthenticated()` | Requires active and authorized user; optionally applies a role guard callback |
| `managedRoles()` | Returns roles allowed for managed login/user flows |
| `isStaffRole()` | Delegates staff-role check to `User::isStaffRole()` |
| `staffPortalPrefix()` | Returns `intern` for interns, otherwise `staff` |
| `staffPortalRoute()` | Builds role-aware route names like `staff.profile` or `intern.profile` |
| `dashboardRoute()` | Maps roles to their dashboard/home route |
| `canManageUsers()` | Allows admin and super admin roles |
| `canAccessAudit()` | Allows admin and super admin roles |
| `isAdminRole()` | Checks `admin` and `ph-admin` |
| `isSuperAdminRole()` | Checks `super_admin` and `hr-super-admin` |
| `findManagedActiveUserByEmail()` | Finds active user by lowercase email within managed roles |

## Middleware Details

### `EnsureStaffSession`

Requires `authenticated_user_id` in session and user role in `staff` or `interns`. Invalid users flush the session and redirect to `login` with `Unauthorized staff access.`

### `EnsureRoleSession`

Requires `authenticated_user_id` and verifies the user's role appears in route middleware parameters. Invalid users flush the session and redirect to either:

- `super_admin.superAdmin.login` when the allowed roles include super admin roles
- `admin.login` otherwise

### `EnsurePendingTwoFactorChallenge`

Requires `2fa:user:id` in session. Missing challenge redirects to login with an expired-session message.

### `EnsureAdminSession`

Legacy-style admin middleware that requires `authenticated_user_id` and `User::isAdminRole($user->role)`. It selects `admin.login` for admin routes and `super_admin.superAdmin.login` otherwise.

## Session Keys

| Key | Purpose |
| --- | --- |
| `2fa:user:id` | Temporary pending user id during Google Authenticator verification |
| `authenticated_user_id` | Main signed-in user id used throughout the app |

## Risks / Notes

- Email OTP route names remain for compatibility, but the behavior redirects users to Google Authenticator.
- `disable2fa()` is route-level public in `routes/web.php`; the method itself checks authentication but no role middleware wraps the route.
- Many backend flows depend directly on `authenticated_user_id`, so losing that session key signs the user out from protected flows.
