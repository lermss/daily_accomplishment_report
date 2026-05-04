# Backend Documentation: User Management, Profile, and Audit Log Flow

## Purpose

This document covers managed user CRUD/archive behavior, staff/admin profile updates, and audit-log viewing/writing.

Main files:

- `routes/web.php`
- `app/Http/Controllers/Admin/UserManagementController.php`
- `app/Http/Controllers/Shared/ProfileController.php`
- `app/Http/Controllers/Admin/AuditController.php`
- `app/Services/AdminPortalService.php`
- `app/Services/AuthFlowService.php`
- `app/Services/ProvincialHeadAssignmentService.php`

## User Management Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `super-admin/users` | `dashboard.users` | `users` | User management list |
| `GET` | `/dashboard/archive` | `dashboard.archive` | `archive` | Archived users list |
| `GET` | `/dashboard/active` | `dashboard.active` | `active` | Active users list |
| `POST` | `/dashboard/users` | `dashboard.users.store` | `store` | Create managed user |
| `PUT` | `/dashboard/users/{targetUser}` | `dashboard.users.update` | `update` | Update managed user |
| `POST` | `/dashboard/users/{targetUser}/archive` | `dashboard.users.archive` | `archiveUser` | Archive managed user |
| `POST` | `/dashboard/users/{targetUser}/restore` | `dashboard.users.restore` | `restoreUser` | Restore managed user |
| `GET` | `/dashboard/admin/users` | `dashboard.admin.users` | `officeUsers` | PH Admin office staff/intern list |

Management routes use `role.session:admin,ph-admin,super_admin,hr-super-admin`. Office users route uses `role.session:ph-admin`.

## Create Managed User Process

1. Controller authenticates the actor through `AuthFlowService::requireAuthenticated()`.
2. `AdminPortalService::userFormOptions()` defines allowed roles and role-specific fields.
3. Request validates:
   - `role`: required and in configured roles
   - `first_name`: required string max 255
   - `middle_name`: nullable string max 255
   - `last_name`: required string max 255
   - `name`: nullable string max 255
   - `email`: required email max 255 unique
4. Display name is built from first, middle, and last name.
5. Role-specific details are validated by `detailRules()`.
6. `AdminPortalService::createManagedUser()` validates office assignment rules.
7. User is created with `status = active` and `is_authorized = false`.
8. Google Authenticator fields are cleared/disabled.
9. Activity is logged as `user_created`.
10. Redirect back with `user_status`.

## Update Managed User Process

Same validation as create, except email must be unique excluding the target user id. `AdminPortalService::updateManagedUser()` updates profile/name/role fields and logs `user_updated`.

## Archive / Restore Process

- `archiveUser()` blocks archiving your own account.
- Archive sets `status = archived` and logs `user_archived`.
- Restore sets `status = active` and logs `user_restored`.

## PH Admin Office Users

`officeUsers()` shows only `staff` and `interns` where `office` equals the PH Admin's office. It supports search and role filter.

## Profile Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/profile/edit` | `profile.edit` | `edit` | Admin/super admin profile edit |
| `POST` | `/profile/edit` | `profile.update` | `update` | Admin/super admin profile update |
| `GET` | `/staff/profile` | `staff.profile` | `staffProfile` | Staff profile |
| `PUT` | `/staff/profile` | `staff.profile.update` | `update` | Staff profile update |
| `GET` | `/intern/profile` | `intern.profile` | `staffProfile` | Intern profile |
| `PUT` | `/intern/profile` | `intern.profile.update` | `update` | Intern profile update |

## Profile Update Process

1. `ProfileController::update()` authenticates current user.
2. Request validates:
   - `first_name`: required string max 255
   - `middle_name`: nullable string max 255
   - `last_name`: required string max 255
   - `position`: nullable string max 255
   - `project`: nullable string max 255
   - `bureau`: nullable string max 255
   - `office`: required string max 255
   - `profile_image`: nullable image jpg/jpeg/png/webp max 5120 KB
   - `signature_image`: nullable image jpg/jpeg/png/webp max 5120 KB
3. `AdminPortalService::updateProfile()` updates names, office, and optional position/project/bureau.
4. If profile image is uploaded, old `avatar_path` is deleted from public disk and new file is stored in `profile-images`.
5. If signature image is uploaded, old `signature_path` is deleted and new file is stored in `signature-images`.
6. Activity is logged as `profile_updated`.
7. Staff/intern users redirect to role-aware profile route; admins redirect to `profile.edit`.

## Audit Log Routes

| Method | URI | Route name | Access | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/audit-log` | `audit.index` | admin, ph-admin, super admin, hr-super-admin | Show audit log |
| `GET` | `/intern/audit-log` | `intern.audit.index` | interns | Calls same controller |

## Audit Display Process

1. Route middleware limits normal audit access to administrative roles.
2. `AuditController::index()` requires authenticated user.
3. `AdminPortalService::buildAuditData()` reads filters:
   - `search`
   - `role`
   - `activity`
   - `date`
4. PH Admin users are scoped to staff/interns in their own office.
5. Service checks table/columns with `safeHasTable()` and `safeHasColumn()` before querying.
6. Logs are selected with user data and paginated.

## Audit Writing

`AdminPortalService::logActivity()` inserts into `activity_logs` only if the table exists. It conditionally writes columns that exist:

- `user_id`
- `action`
- `event`
- `description`
- `details`
- timestamps

Write failures are swallowed.

## Risks / Notes

- Newly created managed users are active but unauthorized until the authenticator authorization flow enables access.
- PH Admin office users page is view-only; `canManageUsers` is false in returned data.
- `/intern/audit-log` uses `AuditController@index`, whose service logic is primarily designed around admin audit data.
