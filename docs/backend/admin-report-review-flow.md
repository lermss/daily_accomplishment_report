# Backend Documentation: Admin Report Review and Super Admin Monitoring Flow

## Purpose

This flow covers admin and super admin report dashboards, report filtering, review status updates, PDF export, and admin-side hiding of approved reports.

Main files:

- `routes/web.php`
- `app/Http/Controllers/Admin/AdminDashboardController.php`
- `app/Services/AdminPortalService.php`
- `app/Services/ProvincialHeadAssignmentService.php`
- `app/Models/Report.php`

## Route Map

### Super Admin Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/dashboard/super-admin` | `dashboard.super-admin` | `superAdminDashboard` | Super admin dashboard |
| `GET` | `/dashboard/super-admin/reports` | `reports.index` | `reportsIndex` | All report table |
| `GET` | `/dashboard/super-admin/reports/employees` | `reports.employees` | `reportsEmployees` | Employee reports |
| `GET` | `/dashboard/super-admin/reports/approved` | `reports.approved` | `reportsApproved` | Approved reports |
| `GET` | `/dashboard/super-admin/reports/pending` | `reports.pending` | `reportsPending` | Pending reports |
| `GET` | `/dashboard/super-admin/reports/revisions` | `reports.revisions` | `reportsRevisions` | For-revision reports |
| `POST` | `/dashboard/super-admin/reports/bulk-delete` | `reports.bulk-delete` | `bulkDelete` | Hide approved reports from admin dashboard |

Middleware: `role.session:super_admin,hr-super-admin`.

### Admin Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/dashboard/admin` | `dashboard.admin` | `adminDashboard` | Admin dashboard |
| `GET` | `/dashboard/admin/employees` | `admin.dashboard.employees` | `adminEmployees` | Employee reports |
| `GET` | `/dashboard/admin/approved` | `admin.dashboard.approved` | `adminApproved` | Approved reports |
| `GET` | `/dashboard/admin/pending` | `admin.dashboard.pending` | `adminPending` | Pending reports |
| `GET` | `/dashboard/admin/revisions` | `admin.dashboard.revisions` | `adminRevisions` | For-revision reports |
| `POST` | `/dashboard/admin/reports/{report}/status` | `admin.dashboard.reports.status` | `updateReportStatus` | Approve or return report |
| `GET` | `/dashboard/admin/reports/{id}/export-pdf` | `admin.dashboard.reports.export-pdf` | `exportReportPDF` | Export any report as PDF |
| `POST` | `/dashboard/admin/bulk-delete` | `admin.dashboard.bulk-delete` | `bulkDelete` | Hide approved reports from admin dashboard |

Middleware: `role.session:admin,ph-admin`.

## Dashboard Rendering

| Controller method | Internal renderer | View |
| --- | --- | --- |
| `adminDashboard()` and admin report pages | `renderAdminReports()` | `admin.reports` |
| `superAdminDashboard()` | `renderSuperAdminReports()` | `admin.dashboard` |
| Super admin report table pages | `renderSuperAdminReports()` | `super_admin.reports-table` |

Both renderers call `AuthFlowService::requireAuthenticated()`.

## Admin Report Query

`AdminPortalService::buildAdminDashboardData()` loads reports with:

- `user:id,name,avatar_path,signature_path,office`
- `assignedProvincialHead:id,name`
- ordered `entries`

Filters and constraints:

- Excludes `is_hidden_from_admin_dashboard = true`
- Includes only statuses `pending`, `approved`, `for_revision`
- Applies `ProvincialHeadAssignmentService::scopeReportsForReviewer()`
- Optional mode/status filter
- Optional date filters using `COALESCE(submitted_at, created_at)`
- Optional search by user name, file name, or status
- Orders by latest submitted/created date
- Paginates 15 per page

## Review Status Update Process

1. Admin submits `POST /dashboard/admin/reports/{report}/status`.
2. `updateReportStatus()` loads authenticated user.
3. Request validates:
   - `status`: required, must be `approved` or `for_revision`
   - `comment`: nullable string max 1000
4. Report loads owner office.
5. `ProvincialHeadAssignmentService::canReviewReport()` checks authorization.
6. `AdminPortalService::updateReportStatus()` calls `Report::markAsReviewed()`.
7. `Report::markAsReviewed()` sets:
   - `status`
   - `reviewed_at` to current time
   - `reviewed_by` to reviewer id
   - `review_comment` only when status is `for_revision`
8. If a comment exists, activity is logged as `report_approved` or `report_returned`.
9. JSON requests receive updated report metadata and refreshed counts.
10. Non-JSON requests redirect back with success status.

## Review Authorization Rules

`ProvincialHeadAssignmentService::canReviewReport()`:

- `admin` can review any report.
- Non-`ph-admin` users cannot review through this rule.
- `ph-admin` can review reports assigned to their own user id.
- If a report has no assigned Provincial Head, `ph-admin` can review when report owner office matches their office.

## Super Admin Monitoring

`AdminPortalService::buildDashboardData()` builds overview data for users, active/archive counts, role counts, report counts, KPI cards, chart data, and user-management options.

Super admin report pages reuse `buildAdminDashboardData()`, so they use the same report status/date/search behavior but route prefixes differ.

## Admin PDF Export

`exportReportPDF()`:

1. Requires authenticated user.
2. Loads report by id with entries.
3. Does not apply status restriction.
4. Renders `staff.reports.pdf`.
5. Downloads `report_{id}.pdf`.

## Bulk Hide Process

1. Admin or super admin submits selected `report_ids`.
2. Only reports with status `approved` are eligible.
3. `scopeReportsForReviewer()` limits PH Admins to their office/assigned reports.
4. Eligible reports are updated with `is_hidden_from_admin_dashboard = true`.
5. `permanentlyDeleteFullyHiddenReports()` deletes reports hidden from:
   - admin dashboard
   - staff dashboard
   - staff index

## Risks / Notes

- Admin PDF export currently allows any status.
- `bulkDelete()` hides approved reports from admin dashboard; it only permanently deletes when all three hide flags are true.
- Super admin report pages use the same admin dashboard data builder, so query changes affect both admin and super admin views.
