# Backend Database Documentation: Migration History

## Purpose

This document explains each migration in chronological order and what backend capability it supports.

## Migration Timeline

| Migration | Purpose |
| --- | --- |
| `2026_03_10_031857_create_users_table.php` | Creates base `users` table with name, email, password, role, department, OTP hash, and timestamps. |
| `2026_03_10_031909_create_reports_table.php` | Creates base `reports` table with id and timestamps only. Later migrations add workflow fields. |
| `2026_03_10_031918_create_activity_logs_table.php` | Creates base `activity_logs` table with id and timestamps only. Later migration adds audit fields. |
| `2026_03_10_065324_add_status_to_users_table.php` | Adds `users.status` defaulting to `active` for active/archive account management. |
| `2026_03_12_130000_add_profile_fields_to_users_table.php` | Adds profile/work assignment fields: position, project, bureau, division, office, institution, OTP code, and OTP expiration. |
| `2026_03_12_130100_add_event_fields_to_activity_logs_table.php` | Adds audit fields: user id, action, event, description, and details. |
| `2026_03_12_131000_add_dashboard_fields_to_reports_table.php` | Adds report workflow fields: owner, file name/path, status, submitted/reviewed timestamps, and reviewer id. |
| `2026_03_12_140000_add_profile_asset_fields_to_users_table.php` | Adds `avatar_path` and `signature_path` for profile images/signatures. |
| `2026_03_18_142502_create_sessions_table.php` | Creates Laravel database-backed sessions table. |
| `2026_03_18_143312_create_cache_table.php` | Creates Laravel cache and cache lock tables. |
| `2026_03_18_143451_create_jobs_table.php` | Creates Laravel queue jobs table. |
| `2026_03_18_153906_create_report_entries_table.php` | Creates report entry rows with cascade delete to reports. |
| `2026_03_24_062325_add_user_avatar_path_to_users_table.php` | Adds `user_avatar_path` to users. Rollback is currently empty/no-op. |
| `2026_03_24_120000_add_notifications_read_at_to_users_table.php` | Adds `notifications_read_at` for staff/admin notification read state. |
| `2026_03_25_090000_add_review_comment_to_reports_table.php` | Adds `review_comment` for admin revision notes. |
| `2026_03_29_000001_add_google2fa_fields_to_users_table.php` | Adds Google Authenticator secret, enabled flag, and confirmation timestamp. |
| `2026_04_01_100000_add_separate_name_columns_to_users_table.php` | Adds first/middle/last name columns and backfills them from existing `name` values. |
| `2026_04_14_000001_add_assigned_provincial_head_id_to_reports_table.php` | Adds assigned PH Admin id and backfills existing reports by matching staff office to active PH Admin office. |
| `2026_04_18_184117_add_is_hidden_from_staff_dashboard_to_reports_table.php` | Adds staff dashboard hide flag. |
| `2026_04_18_193938_add_is_hidden_from_staff_index_to_reports_table.php` | Adds staff report index hide flag. |
| `2026_04_18_201756_add_is_hidden_from_admin_dashboard_to_reports_table.php` | Adds admin dashboard hide flag. |
| `2026_04_19_120000_create_super_admin_notifications_table.php` | Creates persistent Super Admin notification center table. |
| `2026_04_20_100000_add_authenticator_authorization_fields_to_users_table.php` | Adds login authorization and Google Authenticator provisioning metadata. |
| `2026_04_20_140000_create_office_reminder_schedules_table.php` | Creates PH Admin daily office reminder schedules table. |
| `2026_04_20_140100_create_office_reminders_table.php` | Creates actual office reminder notification/event table. |

## Capability History By Feature

### Authentication / Users

Started with base user credentials and role. Later migrations added:

- active/archive status
- profile and office metadata
- profile image/signature paths
- notification read timestamp
- Google Authenticator fields
- first/middle/last name support
- authorization/provisioning metadata

### Reports

Started as an empty shell table with timestamps. Later migrations added:

- owner and file metadata
- status workflow
- submitted/reviewed timestamps
- reviewer id
- report entries table
- review comments
- assigned Provincial Head id
- hide flags for staff/admin screens

### Audit Logs

Started with only id/timestamps. Later migration added flexible activity columns. The activity logging service still writes only columns that exist, so the app can survive older/incomplete schemas.

### Notifications

Notifications are split across:

- `users.notifications_read_at` for staff/admin read state
- `super_admin_notifications` for persistent Super Admin notification records
- `office_reminders` for PH Admin reminder events

### Laravel Infrastructure

Sessions, cache, cache locks, and queue jobs use standard Laravel database tables.

## Rollback / Maintenance Notes

- `2026_03_24_062325_add_user_avatar_path_to_users_table.php` does not remove `user_avatar_path` in `down()`.
- Several migrations defensively check whether columns exist before adding or dropping. This is useful for inconsistent local databases but can hide drift between environments.
- Most user/report foreign-id-like columns are plain unsigned big integers without foreign key constraints.
