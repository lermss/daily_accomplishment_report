# Backend Documentation: Staff, Admin, and Super Admin Notification Flow

## Purpose

This flow covers report review notifications for staff/interns, report submission notifications for PH Admins, and persistent notification-center records for Super Admin users.

Main files:

- `routes/web.php`
- `app/Http/Controllers/Staff/StaffNotificationController.php`
- `app/Http/Controllers/Admin/NotificationController.php`
- `app/Http/Controllers/Admin/SuperAdminNotificationController.php`
- `app/Services/ProvincialReminderService.php`
- `app/Services/SuperAdminNotificationService.php`
- `app/Models/SuperAdminNotification.php`

## Staff / Intern Notification Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/staff/notifications` | `staff.notifications.index` | `StaffNotificationController@index` | JSON notification list |
| `POST` | `/staff/notifications/read` | `staff.notifications.read` | `StaffNotificationController@markAsRead` | Mark staff notifications as read |
| `GET` | `/intern/notifications` | `intern.notifications.index` | `index` | JSON notification list |
| `POST` | `/intern/notifications/read` | `intern.notifications.read` | `markAsRead` | Mark intern notifications as read |

These routes live under `staff.session`.

## Staff / Intern Notification List

`StaffNotificationController::index()` requires the user to be `staff` or `interns`.

It combines:

1. Report review notifications:
   - Owned reports
   - Status `approved` or `for_revision`
   - `reviewed_at` is not null
   - Sorted by latest review
2. Office reminder notifications:
   - Returned by `ProvincialReminderService::reminderNotificationsForStaff()`
   - Scoped by staff user's office

The response contains:

- `notifications`: latest 20 combined notifications
- `unread_count`: report unread count plus reminder unread count

## Staff Mark-As-Read

`markAsRead()` updates `users.notifications_read_at` to now when the column exists, then returns unread count 0.

## Admin / PH Admin Notification Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/dashboard/admin/notifications` | `admin.dashboard.notifications.index` | `NotificationController@index` | PH Admin notification inbox |
| `POST` | `/dashboard/admin/notifications/mark-read` | `admin.dashboard.notifications.mark-read` | `NotificationController@markAsRead` | Mark admin notifications read |

Middleware: `role.session:ph-admin`.

## PH Admin Notification Inbox

`NotificationController::index()`:

1. Requires authenticated `ph-admin`.
2. Reads the PH Admin's office.
3. Loads reports whose owner is in the same office.
4. Includes statuses `pending`, `approved`, and `for_revision`.
5. Orders by `COALESCE(submitted_at, created_at) DESC`.
6. Paginates 15 per page.
7. Updates `notifications_read_at` to now.
8. Returns `admin.notifications`.

`markAsRead()` accepts administrative roles, updates `notifications_read_at`, and returns JSON success.

## Super Admin Notification Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/dashboard/super-admin/notifications` | `super-admin.notifications.index` | `index` | Persistent super admin notification center |
| `POST` | `/dashboard/super-admin/notifications/mark-all-read` | `super-admin.notifications.mark-all-read` | `markAllRead` | Mark all read |
| `POST` | `/dashboard/super-admin/notifications/{notification}/mark-read` | `super-admin.notifications.mark-read` | `markRead` | Mark one read |

Middleware: `role.session:super_admin,hr-super-admin`.

## Super Admin Notification Center

`SuperAdminNotificationController::index()`:

1. Requires super admin role.
2. Calls `SuperAdminNotificationService::refreshSummaryNotifications()`.
3. Returns `super_admin.notifications.index` with paginated notifications and unread count.

## SuperAdminNotificationService Functions

| Method | Responsibility |
| --- | --- |
| `latestPreview()` | Latest notification collection for previews |
| `paginate()` | Paginated notification center data |
| `unreadCount()` | Count where `read_status` is false |
| `refreshSummaryNotifications()` | Sync pending-report and daily-summary notifications |
| `markAsRead()` | Mark one notification read |
| `markAllAsRead()` | Mark all unread notifications read |
| `recordReportSubmission()` | Upsert notification when staff/intern submits report |
| `recordOtpAbuseAttempt()` | Track repeated OTP attempts and notify after threshold |
| `recordSystemAlert()` | Upsert urgent system alert |

## Super Admin Notification Sources

| Source key pattern | Trigger |
| --- | --- |
| `report-submission:{report_id}` | Staff/intern submits a report |
| `pending-reports-summary` | Summary of current pending reports |
| `daily-summary:{date}` | Daily submitted/approved/pending summary |
| `otp-abuse:{date}` | Multiple OTP retry attempts |
| `system-alert:{hash}` | System alert caller |

## Risks / Notes

- Staff notification read state uses one timestamp for both report-review notifications and reminder notifications.
- PH Admin notification inbox marks all as read when the page is opened.
- Super Admin notifications are table-backed; service methods return empty results when the table does not exist.
