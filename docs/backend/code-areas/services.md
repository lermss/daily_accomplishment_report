# Backend Code Area: Services

## `AdminPortalService`

Central service for admin dashboards, report data, audit data, user management, profile updates, activity logs, charts, and safe schema checks.

| Function | Details |
| --- | --- |
| `userFormOptions()` | Defines role-specific labels, required fields, and select options for managed users. |
| `buildDashboardData()` | Builds super-admin/user dashboard data, counts, KPI cards, role filters, and chart data. |
| `buildAdminDashboardData()` | Builds admin/super-admin report table data with status/date/search filters and scoped review access. |
| `reportSummaryCounts()` | Counts employee, approved, pending, and revision reports. |
| `buildAuditData()` | Builds filtered/paginated audit log data with PH Admin office scoping and schema-safe column checks. |
| `formatRoleLabel()` | Formats internal role strings for display. |
| `buildProfileData()` | Builds profile form data and fallback options. |
| `createManagedUser()` | Creates unauthorized active managed user and logs activity. |
| `updateManagedUser()` | Updates managed user identity/role/detail fields and logs activity. |
| `archiveManagedUser()` | Sets target user status to archived and logs activity. |
| `restoreManagedUser()` | Sets target user status to active and logs activity. |
| `updateReportStatus()` | Calls `Report::markAsReviewed()`. |
| `updateProfile()` | Updates profile fields, uploads avatar/signature images, deletes old files, logs activity. |
| `logActivity()` | Safely inserts activity log if table/columns exist. |
| Private helpers | Build chart data, resolve date ranges, build queries, format audit metadata, and check table/column existence safely. |

## `AuthFlowService`

Owns session-user resolution and role-routing logic.

| Function | Details |
| --- | --- |
| `authenticatedUser()` | Reads `authenticated_user_id` and returns user or null. |
| `requireAuthenticated()` | Requires active/authorized user and optional guard callback. |
| `managedRoles()` | Lists roles included in managed login/user flows. |
| `isStaffRole()` | Checks staff/intern roles through `User`. |
| `staffPortalPrefix()` | Returns `intern` for interns, otherwise `staff`. |
| `staffPortalRoute()` | Builds staff/intern route names. |
| `dashboardRoute()` | Maps roles to dashboard/home route names. |
| `canManageUsers()` | Allows admin and super admin role families. |
| `canAccessAudit()` | Allows admin and super admin role families. |
| `isAdminRole()` | Checks `admin` and `ph-admin`. |
| `isSuperAdminRole()` | Checks `super_admin` and `hr-super-admin`. |
| `findManagedActiveUserByEmail()` | Finds active managed user by lowercase email. |

## `DatabaseErrorService`

Static helper service for database errors and health checks.

| Function | Details |
| --- | --- |
| `handle()` | Logs database exceptions and returns user-friendly error payload. |
| `isConnectionError()` | Detects connection-related database errors. |
| `attemptReconnect()` | Attempts DB PDO access, logs success/failure, returns boolean. |
| `getConnectionStatus()` | Returns connected/host/port/database/error data. |

## `ProvincialHeadAssignmentService`

Owns office options, PH Admin assignment, and report review scoping.

| Function | Details |
| --- | --- |
| `officeOptions()` | Returns supported provincial offices. |
| `resolveProvincialHeadForStaff()` | Validates staff office and returns active PH Admin for that office. |
| `ensureValidManagedUserAssignment()` | Validates office and prevents duplicate active PH Admin per office. |
| `canReviewReport()` | Checks whether admin/PH Admin can review a report. |
| `scopeReportsForReviewer()` | Applies PH Admin assigned-office report scoping to a query. |

## `ProvincialReminderService`

Owns PH Admin reminder schedules, manual reminders, lazy dispatch, and staff reminder notifications.

| Function | Details |
| --- | --- |
| `scheduleForUser()` | Gets latest schedule for user's office and creator id. |
| `recentRemindersForOffice()` | Gets recent reminders for an office. |
| `recentRemindersForOfficePaginated()` | Gets paginated recent reminders for an office. |
| `saveDailySchedule()` | Saves daily schedule and removes other creators' schedule for same office. |
| `sendReminderNow()` | Creates manual reminder for user's office. |
| `dispatchDueReminders()` | Creates scheduled reminders due today and updates `last_sent_on`. |
| `reminderNotificationsForStaff()` | Dispatches due reminders then returns office reminders. |
| `unreadReminderCountForStaff()` | Dispatches due reminders then counts reminders after read timestamp. |
| `normalizeMessage()` | Private. Applies default reminder message when blank. |

## `ReportWorkflowService`

Owns staff/intern report listing, draft creation, update, submission, and entry synchronization.

| Function | Details |
| --- | --- |
| `staffReportsFor()` | Returns paginated visible reports for staff/intern with search. |
| `createDraftReport()` | Creates draft report and entries in transaction. |
| `updateReport()` | Updates existing entries or creates new entries in transaction. |
| `submitReport()` | Resolves PH Admin, submits report, records super admin notification. |
| `syncReportEntries()` | Private. Creates entries for new draft. |
| `entryPayload()` | Private. Builds entry create/update payload. |

## `SuperAdminNotificationService`

Owns table-backed super admin notifications.

| Function | Details |
| --- | --- |
| `latestPreview()` | Returns latest notification preview collection. |
| `paginate()` | Returns paginated notification center data. |
| `unreadCount()` | Counts unread notifications. |
| `refreshSummaryNotifications()` | Syncs pending report and daily summary notifications. |
| `markAsRead()` | Marks one notification read. |
| `markAllAsRead()` | Marks all unread notifications read. |
| `recordReportSubmission()` | Upserts notification for submitted report. |
| `recordOtpAbuseAttempt()` | Tracks repeated OTP attempts and creates urgent notification after threshold. |
| `recordSystemAlert()` | Upserts urgent system notification. |
| Private helpers | Sync summary notifications, upsert source-keyed notifications, and check table existence. |
