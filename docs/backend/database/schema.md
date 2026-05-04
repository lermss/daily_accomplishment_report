# Backend Database Documentation: Schema

## Purpose

This document describes the database tables created by the Laravel migrations in `database/migrations`. It focuses on the application tables used by the backend workflows plus Laravel infrastructure tables.

## Users Table

Table: `users`

Purpose: Stores all account types: staff, interns, admin, PH Admin, HR Super Admin, and super admin-style accounts.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `name` | string | no | none | Display/full name |
| `first_name` | string | no | empty string | First name added after original schema |
| `middle_name` | string | yes | null | Middle name |
| `last_name` | string | no | empty string | Last name |
| `email` | string unique | no | none | Login identifier |
| `password` | string | no | none | Hashed password |
| `role` | string | no | `staff` | Role value for routing/access |
| `status` | string | no | `active` | Active/archived account state |
| `is_authorized` | boolean | no | false | Whether account may log in |
| `department` | string | yes | null | Legacy field; model maps department to bureau |
| `position` | string | yes | null | User position |
| `project` | string | yes | null | Project/assignment |
| `bureau` | string | yes | null | Bureau/department value used by model |
| `division` | string | yes | null | Division |
| `office` | string | yes | null | Provincial/office assignment |
| `institution` | string | yes | null | Institution field for role-specific users |
| `avatar_path` | string | yes | null | Profile image path on public disk |
| `signature_path` | string | yes | null | Signature image path on public disk |
| `user_avatar_path` | string | yes | null | Additional avatar field added later |
| `otp_hash` | string | yes | null | Legacy OTP hash |
| `otp_code` | string | yes | null | Legacy OTP code |
| `otp_expiration` | timestamp | yes | null | Legacy OTP expiration |
| `notifications_read_at` | timestamp | yes | null | Last time notifications were marked read |
| `google2fa_secret` | text | yes | null | Encrypted or fallback plain Google2FA secret |
| `google2fa_enabled` | boolean | no | false | Whether Google2FA is enabled |
| `two_factor_confirmed_at` | timestamp | yes | null | First successful 2FA confirmation |
| `google2fa_authorization_code_hash` | text | yes | null | Authorization code hash field, currently cleared during provisioning/confirmation |
| `google2fa_authorization_code_expires_at` | timestamp | yes | null | Authorization code expiration |
| `google2fa_authorization_sent_at` | timestamp | yes | null | When provisioning email was sent |
| `google2fa_authorized_by` | unsigned big integer | yes | null | User id of super admin who authorized access |
| `google2fa_authorized_at` | timestamp | yes | null | Authorization timestamp |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

Important model behavior:

- `User::ADMIN_ROLES`: `admin`, `hr-super-admin`, `ph-admin`
- `User::STAFF_ROLES`: `staff`, `interns`
- `password` is cast as hashed.
- `department` accessor/mutator maps to `bureau`.

## Reports Table

Table: `reports`

Purpose: Stores report headers/status/review metadata.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `user_id` | unsigned big integer | yes | null | Report owner user id |
| `assigned_provincial_head_id` | unsigned big integer | yes | null | PH Admin assigned during submission |
| `file_name` | string | yes | null | Report title/file name |
| `file_path` | string | yes | null | Legacy/possible file path |
| `status` | string | no | `pending` at DB level | Report workflow status |
| `submitted_at` | timestamp | yes | null | Submission timestamp |
| `reviewed_at` | timestamp | yes | null | Review timestamp |
| `reviewed_by` | unsigned big integer | yes | null | Reviewer user id |
| `review_comment` | text | yes | null | Admin revision/review note |
| `is_hidden_from_staff_dashboard` | boolean | no | false | Hide from staff dashboard |
| `is_hidden_from_staff_index` | boolean | no | false | Hide from staff reports index |
| `is_hidden_from_admin_dashboard` | boolean | no | false | Hide from admin dashboard |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

Status values used by the app:

- `draft`
- `pending`
- `approved`
- `for_revision`

Important note: the database default for `status` is `pending`, but `ReportWorkflowService::createDraftReport()` explicitly creates staff/intern reports with `draft`.

## Report Entries Table

Table: `report_entries`

Purpose: Stores individual activity rows under a report.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `report_id` | foreign id | no | none | References `reports.id`, cascade delete |
| `start_date` | date | no | none | Activity start date |
| `end_date` | date | yes | null | Activity end date |
| `activity` | string | no | none | Activity label; app commonly normalizes blank to `N/A` |
| `details` | text | yes | null | Details/accomplishment text |
| `remarks` | text | yes | null | Remarks |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

Relationship:

- `report_entries.report_id` is constrained to `reports.id` with `onDelete('cascade')`.

## Activity Logs Table

Table: `activity_logs`

Purpose: Stores audit log records, written defensively by `AdminPortalService::logActivity()`.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `user_id` | unsigned big integer | yes | null | Actor user id |
| `action` | string | yes | null | Activity action name |
| `event` | string | yes | null | Alternate/duplicate event name |
| `description` | text | yes | null | Human-readable description |
| `details` | text | yes | null | Alternate/duplicate details text |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

Model fillable also includes `role` and `ip_address`, but the current migrations shown do not add those columns.

## Super Admin Notifications Table

Table: `super_admin_notifications`

Purpose: Stores persistent notification-center records for Super Admin users.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `source_key` | string unique | yes | null | Stable deduplication/upsert key |
| `title` | string | no | none | Notification title |
| `message` | text | no | none | Notification body |
| `type` | string(20) | no | none | `URGENT`, `REVIEW`, or `INFO` |
| `read_status` | boolean | no | false | Whether notification is read |
| `read_at` | timestamp | yes | null | Read timestamp |
| `action_label` | string | yes | null | Button/link label |
| `action_url` | string | yes | null | Button/link URL |
| `meta` | json | yes | null | Structured metadata |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

## Office Reminder Schedules Table

Table: `office_reminder_schedules`

Purpose: Stores one daily reminder schedule for an office/creator.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `office` | string | no | none | Office receiving reminders |
| `message` | text | yes | null | Optional custom reminder text |
| `send_time` | time | no | none | Daily send time |
| `is_enabled` | boolean | no | true | Whether schedule dispatches |
| `last_sent_on` | date | yes | null | Last date a scheduled reminder was sent |
| `created_by` | unsigned big integer | no | none | PH Admin creator user id |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

## Office Reminders Table

Table: `office_reminders`

Purpose: Stores actual reminder events, both manual and scheduled.

| Column | Type | Nullable | Default | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | auto | Primary key |
| `office` | string | no | none | Office receiving reminder |
| `message` | text | no | none | Reminder text |
| `type` | string(20) | no | none | `manual` or `scheduled` |
| `triggered_at` | timestamp | no | none | When reminder was sent/created |
| `created_by` | unsigned big integer | no | none | PH Admin creator user id |
| `office_reminder_schedule_id` | unsigned big integer | yes | null | Related schedule id for scheduled reminders |
| `created_at` / `updated_at` | timestamps | yes | null | Laravel timestamps |

## Sessions Table

Table: `sessions`

Purpose: Laravel database session storage.

| Column | Type | Nullable | Key/index | Purpose |
| --- | --- | --- | --- | --- |
| `id` | string | no | primary | Session id |
| `user_id` | foreign id | yes | index | Laravel session user reference |
| `ip_address` | string(45) | yes | none | Client IP |
| `user_agent` | text | yes | none | Browser/user agent |
| `payload` | long text | no | none | Serialized session payload |
| `last_activity` | integer | no | index | Last activity timestamp |

## Cache Tables

Table: `cache`

| Column | Type | Key/index | Purpose |
| --- | --- | --- | --- |
| `key` | string | primary | Cache key |
| `value` | medium text | none | Cached value |
| `expiration` | big integer | index | Expiration timestamp |

Table: `cache_locks`

| Column | Type | Key/index | Purpose |
| --- | --- | --- | --- |
| `key` | string | primary | Lock key |
| `owner` | string | none | Lock owner token |
| `expiration` | big integer | index | Expiration timestamp |

## Jobs Table

Table: `jobs`

Purpose: Laravel queue jobs.

| Column | Type | Nullable | Key/index | Purpose |
| --- | --- | --- | --- | --- |
| `id` | big integer | no | primary | Job id |
| `queue` | string | no | index | Queue name |
| `payload` | long text | no | none | Serialized job payload |
| `attempts` | unsigned tiny integer | no | none | Attempt count |
| `reserved_at` | unsigned integer | yes | none | Reserved timestamp |
| `available_at` | unsigned integer | no | none | Available timestamp |
| `created_at` | unsigned integer | no | none | Created timestamp |

## Schema Notes / Risks

- Several migrations use `Schema::hasColumn()` checks, making them safer on partially migrated databases.
- `users.user_avatar_path` has a down migration that does not drop the column.
- `activity_logs` model fillable includes `role` and `ip_address`, but migrations in this repo do not add those columns.
- `reports.user_id`, `reports.reviewed_by`, `reports.assigned_provincial_head_id`, reminder creator fields, and activity log user fields are unsigned ids but not declared as foreign-key constraints in the migrations shown.
