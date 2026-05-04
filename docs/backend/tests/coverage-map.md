# Backend Test Documentation: Coverage Map and Gaps

## Purpose

This document maps the existing backend tests to the behavior they protect and lists important backend behavior that is currently untested or lightly tested.

Test files:

- `tests/Feature/StaffFeatureRegressionTest.php`
- `tests/Feature/AuthenticatorFlowTest.php`
- `tests/Feature/AuthenticatorAccessControlTest.php`
- `tests/Feature/AuthenticatorSuperAdminAuthorizationTest.php`
- `tests/Feature/AdminNotificationFlowTest.php`
- `tests/Feature/ProvincialReminderFlowTest.php`
- `tests/Feature/ExampleTest.php`
- `tests/Unit/ExampleTest.php`

## Test Suite Style

Most feature tests manually reset and create only the database tables they need inside `setUp()` using `Schema::dropAllTables()` and `Schema::create()`.

This means:

- Tests are focused and fast.
- Tests do not always use the real migration schema.
- A behavior can pass tests even if the real migrations differ from the simplified test schema.
- View compilation paths are isolated under `tests/.compiled-views/...`.

## Staff Feature Regression Tests

File: `tests/Feature/StaffFeatureRegressionTest.php`

### Covered Behavior

| Test | What it verifies |
| --- | --- |
| `test_staff_can_create_report_with_entries` | Staff can create a draft report with one entry through `staff.reports.store`; report owner, file name, status, and entry fields persist. |
| `test_created_draft_report_appears_on_staff_dashboard` | A created draft report appears in `DashboardController::staff()` data. |
| `test_staff_can_update_existing_report_entry` | Staff can update an existing report entry through `staff.reports.update`. |
| `test_staff_profile_update_persists_changes` | Staff profile update persists name, position, project, bureau, and office fields. |
| `test_staff_profile_update_allows_missing_optional_position_field` | Staff profile update does not overwrite existing position when the optional field is omitted. |
| `test_profile_data_includes_fallback_position_options_when_database_has_none` | `AdminPortalService::buildProfileData()` provides fallback position options when database has none. |

### Main Areas Protected

- Staff report draft creation
- Report entry creation/update
- Staff dashboard draft visibility
- Staff profile update behavior
- Profile option fallback behavior

### Important Gaps

- Report submission to Provincial Head is not covered here.
- Staff report ownership protection is not tested for show/update/delete/export.
- Staff bulk hide/delete from dashboard is not tested.
- PDF export rules are not tested.
- Intern equivalents are not directly tested.
- Multiple-entry create/update behavior is only lightly covered.

## Authentication Flow Tests

File: `tests/Feature/AuthenticatorFlowTest.php`

### Covered Behavior

| Test | What it verifies |
| --- | --- |
| `test_authorized_user_with_provisioned_google_authenticator_is_sent_to_verify_screen` | Authorized active user with enabled Google Authenticator is redirected to 2FA verify screen and `2fa:user:id` is stored. |
| `test_verify_screen_route_loads_for_pending_two_factor_session` | Pending 2FA session can load `auth.verify-2fa` view with user email. |
| `test_authorized_user_without_provisioned_google_authenticator_gets_resend_message` | Authorized user without provisioned Google Authenticator is blocked with setup/resend message. |
| `test_super_admin_authorization_provisions_secret_and_sends_access_email` | Super admin authorization enables login/2FA, stores secret, resets confirmation, and sends provisioning mail. |
| `test_resending_access_email_preserves_confirmed_state_and_existing_secret` | Re-authorizing an already provisioned user preserves existing secret and confirmed state. |
| `test_successful_google_authenticator_login_marks_first_confirmation` | Successful Google Authenticator verification sets first confirmation timestamp and redirects. |

### Main Areas Protected

- Google Authenticator login start
- Pending 2FA verify form
- Missing 2FA setup block
- Provisioning mail dispatch
- Existing secret preservation
- First 2FA confirmation timestamp

### Important Gaps

- Invalid 2FA code behavior is not tested.
- Expired/missing pending 2FA session redirect is only partially covered by access-control tests.
- Unauthorized inactive user login path is not deeply tested.
- Logout behavior is not tested.
- `disable2fa()` behavior is not tested.
- Legacy email OTP disabled routes are not tested.

## Authenticator / 2FA Access Control Tests

Files:

- `tests/Feature/AuthenticatorAccessControlTest.php`
- `tests/Feature/AuthenticatorSuperAdminAuthorizationTest.php`

### Covered Behavior

| Test | What it verifies |
| --- | --- |
| `test_super_admin_authenticator_page_requires_authenticated_super_admin` | Authenticator access page redirects unauthenticated users to login. |
| `test_non_super_admin_cannot_access_super_admin_authenticator_page` | Non-super-admin user cannot access authenticator management page. |
| `test_revoked_user_cannot_start_google_authenticator_login` | Revoked authorization blocks future login start. |
| `test_hr_super_admin_account_can_be_authorized_from_authenticator_access` | HR Super Admin account can be authorized and receives provisioning email. |
| `test_seed_only_super_admin_account_stays_blocked_from_authenticator_access_authorization` | `super_admin` role is blocked from authenticator provisioning workflow. |

### Main Areas Protected

- Authenticator management access restriction
- Login authorization revocation
- HR Super Admin provisioning
- Seed-only `super_admin` provisioning block

### Important Gaps

- Inactive target user authorization block is not tested.
- Revoke does not test Google2FA fields remaining enabled/secret preserved.
- Authenticator index search/filter behavior is not tested.
- QR image generation failure fallback is not tested.

## Admin Notification Tests

File: `tests/Feature/AdminNotificationFlowTest.php`

### Covered Behavior

| Test | What it verifies |
| --- | --- |
| `test_provincial_head_topbar_lists_all_pending_notifications_with_modal_links` | PH Admin topbar lists pending assigned reports and builds modal-opening routes. |
| `test_admin_dashboard_carries_auto_open_report_id_for_notification_redirect` | Admin dashboard receives `autoOpenReportId` and includes the matching report data. |

### Main Areas Protected

- PH Admin topbar pending submission notifications
- Pending report notification route format
- Dashboard `open_report` query handoff

### Important Gaps

- `NotificationController@index()` PH Admin inbox is not directly tested.
- Admin `markAsRead()` JSON endpoint is not tested.
- Super Admin notification center controller is not tested.
- `SuperAdminNotificationService` summary/upsert behavior is not directly tested.
- Staff report review notifications are covered indirectly in reminder tests only for reminders, not approved/revision reports.

## Provincial Reminder Flow Tests

File: `tests/Feature/ProvincialReminderFlowTest.php`

### Covered Behavior

| Test | What it verifies |
| --- | --- |
| `test_only_provincial_head_can_access_reminder_page` | Non-PH Admin user is redirected away from reminder page. |
| `test_send_now_creates_office_scoped_reminder` | PH Admin send-now creates manual reminder scoped to their office. |
| `test_daily_schedule_dispatches_once_for_the_office` | Due daily schedule dispatches once per day for an office. |
| `test_provincial_head_dashboard_view_receives_recent_reminder_data` | Reminder page view receives recent reminder data. |
| `test_staff_notifications_include_only_matching_office_reminders` | Staff notification endpoint includes reminders only for matching office. |

### Main Areas Protected

- PH Admin reminder access restriction
- Manual reminder creation
- Daily reminder dispatch idempotency
- Recent reminders passed to view
- Office-scoped reminder notifications for staff

### Important Gaps

- Saving schedule through controller route is not directly tested.
- Disabled schedule behavior is not tested.
- Default reminder message normalization is not tested.
- Reminder read count after `notifications_read_at` is not deeply tested.
- Intern reminder notification path is not directly tested.

## Access Control Tests

Covered mostly by:

- `AuthenticatorAccessControlTest`
- `ProvincialReminderFlowTest::test_only_provincial_head_can_access_reminder_page`
- Auth/session checks embedded in controller tests

### Covered Access Control

- Unauthenticated users cannot access Super Admin authenticator page.
- Non-super-admin users cannot access Super Admin authenticator page.
- Revoked users cannot start 2FA login.
- Non-PH Admin users cannot access reminder page.

### Important Gaps

- `EnsureStaffSession` middleware is not directly tested.
- `EnsureRoleSession` middleware is not directly tested across all role combinations.
- Admin report review authorization by office/assignment is not directly tested.
- User management route authorization is not tested.
- Profile route authorization for admin/staff/intern variants is only indirectly tested.
- Public media access authorization/path traversal is not tested.
- Health route behavior is not tested.

## Example Tests

### `tests/Feature/ExampleTest.php`

| Test | What it verifies |
| --- | --- |
| `test_the_application_returns_a_successful_response` | `GET /` returns HTTP 200. |

### `tests/Unit/ExampleTest.php`

| Test | What it verifies |
| --- | --- |
| `test_that_true_is_true` | Placeholder unit test asserting true is true. |

These are starter tests and do not provide meaningful backend regression coverage beyond basic app boot/page response.

## Overall Coverage Summary

| Checklist item | Status | Notes |
| --- | --- | --- |
| Staff feature regression tests | Covered | Good coverage for create/update/profile basics; missing submit/export/delete/ownership/intern cases. |
| Authentication flow tests | Covered | Good Google Authenticator happy-path coverage; missing invalid/expired/logout/disable cases. |
| Authenticator / 2FA tests | Covered | Covers provisioning, resend preservation, revocation, super-admin restrictions. |
| Admin notification tests | Partially covered | Topbar and dashboard handoff covered; controllers/services mostly untested. |
| Provincial reminder flow tests | Covered | Good coverage for send-now, dispatch once, office-scoped staff notifications. |
| Access control tests | Partially covered | A few important restrictions covered; middleware/role matrix needs more coverage. |
| Gaps where behavior has no tests | Documented | See priority list below. |

## Highest-Priority Test Gaps

1. Report submission workflow:
   - Staff submits report.
   - PH Admin assignment is resolved.
   - Report status changes to `pending`.
   - Super Admin notification is recorded.

2. Admin report review:
   - PH Admin can approve assigned/same-office report.
   - PH Admin cannot review other-office report.
   - Approved/for-revision status updates set review fields correctly.

3. Report ownership protection:
   - Staff cannot view/update/delete/export another user's report.
   - Entry update cannot modify another report's entry.

4. Auth negative paths:
   - Invalid 2FA code.
   - Missing/expired pending 2FA session.
   - Logout clears session.
   - Disable 2FA clears fields.

5. Notification controllers:
   - Staff approved/revision notification payload.
   - PH Admin inbox marks notifications read.
   - Super Admin notification center mark-one/mark-all behavior.

6. Middleware role matrix:
   - `staff.session`
   - `role.session`
   - `2fa.pending`
   - security/database middleware behavior where practical.

7. Public media and health:
   - Authenticated file access works.
   - Path traversal returns 404.
   - Health endpoints return expected status payloads.

8. Database migration/schema alignment:
   - A migration-based test database would catch drift between test schemas and real migrations.

## Suggested Next Tests To Add

Start with these because they protect core business behavior:

1. `ReportSubmissionFlowTest`
2. `AdminReportReviewAccessTest`
3. `StaffReportOwnershipTest`
4. `AuthNegativePathTest`
5. `NotificationControllerFlowTest`
