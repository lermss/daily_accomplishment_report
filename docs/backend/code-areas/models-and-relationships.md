# Backend Code Area: Models and Relationships

## `User`

Authenticatable model for all managed roles.

Relationships:

- `reports()` has many `Report` as owner.
- `reviewedReports()` has many `Report` through `reviewed_by`.
- `assignedProvincialReports()` has many `Report` through `assigned_provincial_head_id`.
- `activityLogs()` has many `ActivityLog`.

Functions:

- `casts()` defines booleans, datetimes, and hashed password cast.
- `getDepartmentAttribute()` maps legacy `department` access to `bureau`.
- `setDepartmentAttribute()` writes legacy `department` assignment into `bureau`.
- `getFullNameAttribute()` returns first + last name, falling back to `name`.
- `isAdminRole()` checks `admin`, `hr-super-admin`, `ph-admin`.
- `isStaffRole()` checks `staff`, `interns`.
- `isProvincialHead()` checks role `ph-admin`.

## `Report`

Main accomplishment report model.

Relationships:

- `user()` belongs to owner user.
- `reviewer()` belongs to user in `reviewed_by`.
- `assignedProvincialHead()` belongs to user in `assigned_provincial_head_id`.
- `entries()` has many `ReportEntry`.

Functions:

- `submit()` sets status to pending, assigns PH Admin, sets submitted timestamp if missing, clears review fields.
- `markAsReviewed()` sets review status, reviewer, reviewed timestamp, and revision comment when applicable.
- `canExport()` returns true for `approved` and `draft`.

## `ReportEntry`

Line item/details row for a report.

Relationship:

- `report()` belongs to `Report`.

## `ActivityLog`

Audit log row.

Relationship:

- `user()` belongs to `User`.

## `OfficeReminderSchedule`

Daily reminder schedule for an office.

Relationships:

- `creator()` belongs to `User` through `created_by`.
- `reminders()` has many `OfficeReminder`.

Casts:

- `is_enabled` boolean
- `last_sent_on` date

## `OfficeReminder`

Sent reminder instance.

Relationships:

- `creator()` belongs to `User` through `created_by`.
- `schedule()` belongs to `OfficeReminderSchedule`.

Casts:

- `triggered_at` datetime

Constants:

- `TYPE_MANUAL = manual`
- `TYPE_SCHEDULED = scheduled`

## `SuperAdminNotification`

Persistent notification record for super admin notification center.

Scopes:

- `scopeUnread()` filters `read_status = false`.
- `scopeLatestFirst()` orders by newest created/id.

Casts:

- `read_status` boolean
- `read_at` datetime
- `meta` array

Constants:

- `TYPE_URGENT`
- `TYPE_REVIEW`
- `TYPE_INFO`
