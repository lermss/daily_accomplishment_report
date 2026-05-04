# Backend Documentation: Staff and Intern Report Workflow

## Purpose

The report workflow lets staff and interns create daily accomplishment report drafts, edit report entries, submit reports to the assigned Provincial Head, hide reports from their own list, and export allowed reports as PDF files.

The main backend code for this workflow is:

- `routes/web.php`
- `app/Http/Controllers/Staff/ReportController.php`
- `app/Services/ReportWorkflowService.php`
- `app/Http/Requests/StoreReportRequest.php`
- `app/Http/Requests/UpdateReportRequest.php`
- `app/Models/Report.php`
- `app/Models/ReportEntry.php`

## Actors

| Actor | Role value | Portal prefix | Main access |
| --- | --- | --- | --- |
| Staff | `staff` | `/staff` | Staff dashboard, profile, reports, notifications |
| Intern | `interns` | `/intern` | Intern dashboard, profile, reports, notifications |
| Provincial Head Admin | `ph-admin` | `/dashboard/admin` | Reviews reports assigned to their office |
| HR Super Admin | `super_admin` or `hr-super-admin` | `/dashboard/super-admin` | Monitors report submissions and system-wide reports |

Staff and intern report routes are protected by the `staff.session` middleware group in `routes/web.php`.

## Route Map

### Staff Routes

| Method | URI | Route name | Controller method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/staff/reports` | `staff.reports` | `ReportController@index` | Show paginated staff report list |
| `GET` | `/staff/reports/create` | `staff.reports.create` | `ReportController@createReport` | Show create report form |
| `POST` | `/staff/reports` | `staff.reports.store` | `ReportController@storeReport` | Save a new draft report and entries |
| `GET` | `/staff/reports/{id}` | `staff.reports.show` | `ReportController@show` | Show a report owned by the current staff user |
| `PUT` | `/staff/reports/{id}` | `staff.reports.update` | `ReportController@update` | Update entries for an owned report |
| `PUT` | `/staff/reports/{id}/file-name` | `staff.reports.updateFile` | `ReportController@updateFile` | Update only the report file name |
| `DELETE` | `/staff/reports/{id}` | `staff.reports.destroy` | `ReportController@destroy` | Hide the report from staff report index |
| `GET` | `/staff/reports/{id}/pdf` | `staff.reports.pdf` | `ReportController@exportPDF` | Download allowed report as PDF |
| `POST` | `/staff/reports/{id}/submit` | `staff.reports.submit` | `ReportController@submit` | Submit report for Provincial Head review |

### Intern Routes

The intern report routes mirror the staff report routes and call the same controller methods.

| Method | URI | Route name | Controller method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/intern/reports` | `intern.reports` | `ReportController@index` | Show paginated intern report list |
| `GET` | `/intern/reports/create` | `intern.reports.create` | `ReportController@createReport` | Show create report form |
| `POST` | `/intern/reports` | `intern.reports.store` | `ReportController@storeReport` | Save a new draft report and entries |
| `GET` | `/intern/reports/{id}` | `intern.reports.show` | `ReportController@show` | Show a report owned by the current intern user |
| `PUT` | `/intern/reports/{id}` | `intern.reports.update` | `ReportController@update` | Update entries for an owned report |
| `PUT` | `/intern/reports/{id}/file-name` | `intern.reports.updateFile` | `ReportController@updateFile` | Update only the report file name |
| `DELETE` | `/intern/reports/{id}` | `intern.reports.destroy` | `ReportController@destroy` | Hide the report from intern report index |
| `GET` | `/intern/reports/{id}/pdf` | `intern.reports.pdf` | `ReportController@exportPDF` | Download allowed report as PDF |
| `POST` | `/intern/reports/{id}/submit` | `intern.reports.submit` | `ReportController@submit` | Submit report for Provincial Head review |

## Session Dependency

`ReportController` identifies the current staff or intern user through:

```php
$request->session()->get('authenticated_user_id')
```

The private `resolveStaffUser()` method returns `null` when the session key is missing. Most owned-report actions then fail through `findOwnedReport()`, which throws `ModelNotFoundException` if there is no authenticated user.

## Report Status Flow

| Status | Constant | Meaning | Set by |
| --- | --- | --- | --- |
| `draft` | `Report::STATUS_DRAFT` | Staff/intern can still edit before submission | `ReportWorkflowService::createDraftReport()` |
| `pending` | `Report::STATUS_PENDING` | Submitted and waiting for Provincial Head/admin review | `Report::submit()` |
| `approved` | `Report::STATUS_APPROVED` | Reviewed and accepted by admin | `Report::markAsReviewed()` through admin workflow |
| `for_revision` | `Report::STATUS_FOR_REVISION` | Reviewed and returned with comments | `Report::markAsReviewed()` through admin workflow |

When a report is submitted, `submitted_at` is set only if it was previously empty. `reviewed_at`, `reviewed_by`, and `review_comment` are cleared during submission.

## Main Create Draft Process

1. User opens `GET /staff/reports/create` or `GET /intern/reports/create`.
2. `ReportController::createReport()` returns the `staff.reports.createReport` view.
3. User submits the form to `POST /staff/reports` or `POST /intern/reports`.
4. `StoreReportRequest` validates and normalizes the request.
5. `ReportController::storeReport()` resolves the authenticated user from session.
6. `ReportWorkflowService::createDraftReport()` starts a database transaction.
7. A `reports` row is created with:
   - `user_id` from the authenticated session user, nullable if no user is resolved
   - `file_name` from validated input
   - `status` as `draft`
8. `ReportWorkflowService::syncReportEntries()` creates one `report_entries` row for each submitted `start_date`.
9. User is redirected to the correct staff or intern reports route.
10. Flash session values are set:
   - `success`: `Draft report created.`
   - `clear_report_draft`: `true`

## Main Update Process

1. User submits `PUT /staff/reports/{id}` or `PUT /intern/reports/{id}`.
2. `UpdateReportRequest` validates and normalizes the request.
3. `ReportController::update()` loads the report with `entries` through `findOwnedReport()`.
4. `findOwnedReport()` only returns a report where `reports.user_id` matches the current session user.
5. `ReportWorkflowService::updateReport()` starts a database transaction.
6. For each submitted `start_date` index:
   - If `entry_id[index]` exists, that `report_entries` row is updated.
   - If `entry_id[index]` is empty, a new `report_entries` row is created.
7. If the report status is `pending` or `for_revision`, the controller sets `is_hidden_from_staff_index` to `false`.
8. User is redirected back with flash message `Report updated.`

Important detail: entry updates are performed with `ReportEntry::where('id', $entryId)->update($entryPayload)`. The controller has already verified report ownership, but the service does not separately verify that each `entry_id` belongs to the same report.

## Main Submit Process

1. User submits `POST /staff/reports/{id}/submit` or `POST /intern/reports/{id}/submit`.
2. `ReportController::submit()` resolves the current user from session.
3. If no user is found, the user is redirected to `login`.
4. `findOwnedReport()` loads only a report owned by the authenticated user.
5. `ReportWorkflowService::submitReport()` resolves the assigned Provincial Head by calling `ProvincialHeadAssignmentService::resolveProvincialHeadForStaff($staffUser)`.
6. `Report::submit($provincialHead->id)` updates the report:
   - `status`: `pending`
   - `assigned_provincial_head_id`: resolved Provincial Head user id
   - `submitted_at`: current time if not already set
   - `reviewed_at`: `null`
   - `reviewed_by`: `null`
   - `review_comment`: `null`
7. `SuperAdminNotificationService::recordReportSubmission()` records an HR Super Admin notification.
8. Controller sets `is_hidden_from_staff_dashboard` to `false`.
9. User is redirected to their staff or intern dashboard with flash message `Report submitted to your assigned Provincial Head for review.`

## PDF Export Process

1. User requests `GET /staff/reports/{id}/pdf` or `GET /intern/reports/{id}/pdf`.
2. `ReportController::exportPDF()` loads the owned report with entries.
3. Export is allowed only when `status` is `approved` or `draft`.
4. Any other status aborts with HTTP `403` and message `Export is only available for approved or draft reports.`
5. DomPDF renders `staff.reports.pdf`.
6. PDF settings:
   - Paper: `a4`
   - Orientation: `portrait`
   - DPI: `150`
   - Default font: `Times-Roman`
7. Download filename format is `report_{id}.pdf`.

## Delete/Hide Process

The staff delete action is a soft hide, not a database delete.

1. User submits `DELETE /staff/reports/{id}` or `DELETE /intern/reports/{id}`.
2. `ReportController::destroy()` loads only an owned report.
3. Controller updates `is_hidden_from_staff_index` to `true`.
4. User is redirected to their staff or intern reports index with flash message `Report hidden from your reports list.`

The `reports` row and related `report_entries` rows remain in the database.

## Controller Function Details

### `ReportController::__construct(ReportWorkflowService $reportWorkflowService)`

Injects the report workflow service used for report list, create, update, and submit operations.

### `ReportController::index(Request $request): View`

Reads the optional `search` query parameter, trims it, resolves the current session user, calls `ReportWorkflowService::staffReportsFor()`, and returns `staff.reports.index`.

### `ReportController::update(UpdateReportRequest $request, int $id): RedirectResponse`

Validates through `UpdateReportRequest`, loads an owned report with entries, delegates entry updates to the service, unhides pending or revision reports from the staff index, and redirects back.

### `ReportController::updateFile(Request $request, int $id): RedirectResponse`

Validates `file_name` as required string max 255 characters, updates the owned report's `file_name`, and redirects back.

### `ReportController::createReport(): View`

Returns the report creation form view `staff.reports.createReport`.

### `ReportController::storeReport(StoreReportRequest $request): RedirectResponse`

Validates through `StoreReportRequest`, resolves the current user, delegates draft creation to the service, redirects to the role-aware reports route, and asks the frontend to clear the report draft.

### `ReportController::show(Request $request, int $id): View`

Loads an owned report with entries and returns `staff.reports.show`.

### `ReportController::exportPDF(Request $request, int $id)`

Loads an owned report with entries, blocks export unless the report is `approved` or `draft`, renders the PDF view through DomPDF, and returns a PDF download response.

### `ReportController::pdf(Request $request, int $id)`

Alias method that calls `exportPDF()`.

### `ReportController::destroy(Request $request, int $id): RedirectResponse`

Loads an owned report, sets `is_hidden_from_staff_index` to `true`, and redirects to the role-aware reports index route.

### `ReportController::submit(Request $request, int $id): RedirectResponse`

Resolves the current user, loads an owned report, delegates submission to the service, unhides the report from the staff dashboard, and redirects to the role-aware dashboard route.

### `ReportController::storeEntry(Request $request)`

Validates and creates one `ReportEntry` directly, then returns JSON with `success` and the created `entry`. This method is not currently exposed in `routes/web.php`.

### `ReportController::resolveStaffUser(Request $request): ?User`

Reads `authenticated_user_id` from session and returns the matching `User`, or `null` if no session user exists.

### `ReportController::normalizeOptionalText(?string $value, ?string $default = null): ?string`

Trims optional text input. Returns the provided default when the trimmed value is empty.

### `ReportController::findOwnedReport(Request $request, int $id, array $with = []): Report`

Resolves the current user, then queries `reports` by id and `user_id`. This is the ownership gate for show, update, submit, export, and hide actions.

## Service Function Details

### `ReportWorkflowService::staffReportsFor(?User $staffUser, string $searchTerm = '', int $perPage = 10): LengthAwarePaginator`

Returns paginated reports with entries. Filters by `user_id` when a user is available, excludes reports where `is_hidden_from_staff_index` is true, supports search by file name, status, updated date, and submitted date, orders latest first, and keeps the current query string.

### `ReportWorkflowService::createDraftReport(?User $staffUser, array $validated): Report`

Creates a draft report and its entries inside a transaction. The report starts with status `draft`.

### `ReportWorkflowService::updateReport(Report $report, array $validated): void`

Updates existing entries or creates new entries inside a transaction. It iterates by `start_date` index and maps the same index across `entry_id`, `end_date`, `activity`, `details`, and `remarks`.

### `ReportWorkflowService::submitReport(Report $report, User $staffUser): void`

Resolves the Provincial Head for the staff user, submits the report to that reviewer, and records a Super Admin notification.

### `ReportWorkflowService::syncReportEntries(Report $report, array $validated): void`

Creates all report entry rows for a newly created draft report.

### `ReportWorkflowService::entryPayload(int $reportId, array $validated, int $index, string $startDate): array`

Builds the normalized payload used for both creating and updating report entries.

## Validation Details

### `StoreReportRequest`

Before validation, `prepareForValidation()` normalizes text arrays:

- Empty `activity` values become `N/A`.
- Empty `details` values become `null`.
- Empty `remarks` values become `null`.
- Non-array values become an empty array.

Rules:

| Field | Rules |
| --- | --- |
| `file_name` | required, string, max 255 |
| `start_date` | required, array, min 1 |
| `start_date.*` | required, date |
| `end_date` | nullable, array |
| `end_date.*` | nullable, date |
| `activity` | nullable, array |
| `activity.*` | nullable, string |
| `details` | nullable, array |
| `details.*` | nullable, string |
| `remarks` | nullable, array |
| `remarks.*` | nullable, string |

### `UpdateReportRequest`

Before validation, `prepareForValidation()` applies the same normalization as `StoreReportRequest`.

Rules:

| Field | Rules |
| --- | --- |
| `entry_id` | nullable, array |
| `entry_id.*` | nullable, integer, exists in `report_entries.id` |
| `start_date` | required, array |
| `start_date.*` | required, date |
| `end_date` | nullable, array |
| `end_date.*` | nullable, date |
| `activity` | nullable, array |
| `activity.*` | nullable, string |
| `details` | nullable, array |
| `details.*` | nullable, string |
| `remarks` | nullable, array |
| `remarks.*` | nullable, string |

## Model Details

### `Report`

Fillable fields:

- `user_id`
- `assigned_provincial_head_id`
- `file_name`
- `file_path`
- `status`
- `submitted_at`
- `reviewed_at`
- `reviewed_by`
- `review_comment`
- `is_hidden_from_staff_dashboard`
- `is_hidden_from_staff_index`
- `is_hidden_from_admin_dashboard`

Casts:

- `submitted_at` as `datetime`
- `reviewed_at` as `datetime`

Relationships:

| Method | Relationship |
| --- | --- |
| `user()` | Belongs to the report owner user |
| `reviewer()` | Belongs to the user referenced by `reviewed_by` |
| `assignedProvincialHead()` | Belongs to the user referenced by `assigned_provincial_head_id` |
| `entries()` | Has many `ReportEntry` rows |

Behavior:

- `submit()` moves a report to `pending` and clears previous review data.
- `markAsReviewed()` sets review status, review timestamp, reviewer, and revision comment when status is `for_revision`.
- `canExport()` returns true for `approved` and `draft`.

### `ReportEntry`

Fillable fields:

- `report_id`
- `start_date`
- `end_date`
- `activity`
- `details`
- `remarks`

Relationships:

| Method | Relationship |
| --- | --- |
| `report()` | Belongs to `Report` |

## Database Tables

### `reports`

The base migration creates `id` and timestamps. Later migrations add the workflow fields.

| Column | Type/Behavior |
| --- | --- |
| `id` | Primary key |
| `user_id` | Nullable unsigned big integer |
| `assigned_provincial_head_id` | Nullable unsigned big integer |
| `file_name` | Nullable string |
| `file_path` | Nullable string |
| `status` | String, default originally added as `pending`; application creates drafts with `draft` |
| `submitted_at` | Nullable timestamp |
| `reviewed_at` | Nullable timestamp |
| `reviewed_by` | Nullable unsigned big integer |
| `review_comment` | Nullable text |
| `is_hidden_from_staff_dashboard` | Boolean, default false |
| `is_hidden_from_staff_index` | Boolean, default false |
| `is_hidden_from_admin_dashboard` | Boolean, default false |
| `created_at` / `updated_at` | Laravel timestamps |

### `report_entries`

| Column | Type/Behavior |
| --- | --- |
| `id` | Primary key |
| `report_id` | Foreign id constrained to `reports.id`, cascade delete |
| `start_date` | Required date |
| `end_date` | Nullable date |
| `activity` | String |
| `details` | Nullable text |
| `remarks` | Nullable text |
| `created_at` / `updated_at` | Laravel timestamps |

## Tested Behavior

`tests/Feature/StaffFeatureRegressionTest.php` currently verifies:

- Staff can create a report with entries.
- Created draft reports appear on the staff dashboard.
- Staff can update an existing report entry.
- Related staff profile behavior remains stable.

## Documentation Notes / Risks

- `ReportController::storeEntry()` is documented because it exists, but it is not currently connected to a route in `routes/web.php`.
- `ReportWorkflowService::updateReport()` validates that `entry_id` exists globally, but does not verify inside the service that each entry belongs to the report being updated.
- Staff and intern routes use the same controller and views. Role-aware redirects are handled through `AuthFlowService::staffPortalRoute()`.
- Hiding a report from the staff index is not deletion. The report remains available to backend code unless explicitly filtered.
