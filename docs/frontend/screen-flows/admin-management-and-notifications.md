# Admin Management And Notification Screen Flows

This document covers user management, audit logs, PH Admin reminders/notifications, super admin authenticator access, and super admin notifications.

## User Management Screen

- Full management view: `resources/views/admin/users.blade.php`
- PH admin office-scoped view: `resources/views/admin/ph-users.blade.php`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Main assets: `dashboard.css`, `shared-dashboard-theme.css`, `shared-navbar.css`, `public/js/dashboard.js`, `public/js/search-filter.js`.

Process:

1. The page renders `<x-topbar active="users">`.
2. The table lists users with avatar/initials, name, email, position, office, project/division, bureau, and actions.
3. Search and role filters submit as `GET` requests and are enhanced by `search-filter.js`.
4. Users with management permission can open a modal to create or edit users.
5. Archive/restore buttons submit hidden forms after the shared confirmation modal accepts the action.
6. `dashboard.js` fills role-dependent select options and shows/hides fields based on `window.dashboardConfig`.

Modal behavior:

- Create mode posts to `route('dashboard.users.store')`.
- Edit mode changes the form action to `/dashboard/users/{id}` and sets `_method=PUT`.
- The name field is stored as a hidden combined `name`, built from first, middle, and last name inputs.
- Role radio selection controls required fields and available project, bureau, division, and office options.

PH admin office-scoped view:

- `admin/ph-users.blade.php` is read-only.
- It shows only staff/intern users assigned to the PH admin office.
- It includes search/filter controls but no create, edit, archive, or restore actions.

## Audit Log Screen

- View: `resources/views/admin/audit-log.blade.php`
- Main assets: `audit-log.css`, `dashboard.css`, `shared-dashboard-theme.css`, `shared-navbar.css`, `public/js/audit-log.js`.

Process:

1. The page renders summary cards for total logs today, successful logins, profile updates, and warnings.
2. Filters allow role, activity, and date filtering.
3. The log table lists activity, description, role/user, date/time, status, and a Details button.
4. `audit-log.js` toggles the hidden details row for each log.
5. Pagination appears when the log paginator has more pages.

Important details:

- PH admin mode hides IP address and device details.
- The search input markup is present but commented out in the Blade file.

## PH Admin Reminders Screen

- View: `resources/views/admin/reminders.blade.php`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Main assets: `dashboard.css`, `shared-dashboard-theme.css`, `shared-navbar.css`, inline CSS/JS, `toast-notification.js`.

Process:

1. The page shows the PH admin office as the reminder scope.
2. Summary cards show office scope, automation status/time, and recent reminder count.
3. Daily Reminder Automation form posts message, send time, and enabled flag to `admin.dashboard.reminders.schedule`.
4. Send Reminder Now form posts a message to `admin.dashboard.reminders.send-now`.
5. Recent reminder activity lists manual and scheduled reminder history.
6. Pagination appears when recent reminders have more pages.

Important details:

- Reminders are office-scoped to `$user->office`.
- Success and error toasts are manually created in inline JavaScript.
- Manual send-now does not change the saved daily schedule.

## PH Admin Notifications Screen

- View: `resources/views/admin/notifications.blade.php`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Main assets: `admin-dashboard.css`, `shared-dashboard-theme.css`, `shared-navbar.css`, inline CSS.

Process:

1. The page renders `<x-topbar active="notifications">`.
2. The notification list shows report submissions from staff in the admin's office.
3. Each card shows avatar/initials, report file name, relative submitted time, and current status.
4. Unread cards are determined by comparing report submitted time to `notifications_read_at`.
5. The Review button links to `route('dashboard.admin')` with `?open_report={report_id}`.
6. The admin report dashboard reads that query and can auto-open the report modal.

## Super Admin Authenticator Access Screen

- View: `resources/views/super_admin/authenticator-authorizations.blade.php`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Main route family: `super-admin.authenticator.*`

Process:

1. The page renders `<x-topbar active="authenticator">`.
2. Search and role filter submit as `GET` requests to `super-admin.authenticator.index`.
3. The table lists managed users with login access, authenticator status, access email status, and actions.
4. Authorize & Send Access posts to `super-admin.authenticator.authorize`.
5. Send Access Email uses the same authorize route after the user is already provisioned.
6. Revoke Access posts to `super-admin.authenticator.revoke` when the user is currently authorized.

Important details:

- Login access badge is Authorized or Blocked.
- Authenticator badge is Provisioned or Not Provisioned.
- The page explains that the email contains the QR code and manual setup key.

## Super Admin Notifications Screen

- View: `resources/views/super_admin/notifications/index.blade.php`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Main route family: `super-admin.notifications.*`

Process:

1. The page shows a hero count of unread super admin notifications.
2. Mark All As Read posts to `super-admin.notifications.mark-all-read`.
3. Each notification card shows type, title, message, created time, read/unread flag, and optional action link.
4. If a notification has `action_url`, the card shows the configured action label or `View Details`.
5. Individual unread notifications can be marked read through `super-admin.notifications.mark-read`.
6. Pagination is rendered when the notification collection supports `links()`.

Topbar notification preview:

- Shared component: `resources/views/components/topbar.blade.php`
- JavaScript: `public/js/topbar.js`
- Super admin preview shows latest summarized notifications and unread badge.
- Admin preview shows pending report submissions.
- Staff preview shows office reminders and report review updates.
