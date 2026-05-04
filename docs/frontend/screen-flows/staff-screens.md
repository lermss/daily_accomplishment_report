# Staff And Intern Screen Flows

This document covers the staff/intern screens for dashboards, reports, and profile management.

## Staff/Intern Dashboard

- View: `resources/views/staff/dashboard.blade.php`
- Layout: `resources/views/staff/layouts/app.blade.php`
- Main assets: staff layout CSS/JS plus inline dashboard styles and scripts.
- Main routes used by links/forms: staff portal route prefix from `AuthFlowService::staffPortalPrefix()`.

Process:

1. The page shows four status cards: Submitted, Approved, Pending, and For Revision.
2. Each card links back to the dashboard with a status filter query.
3. The search form sends a `GET` request to the current dashboard URL and preserves the selected status.
4. The report table lists each report with checkbox, submitted date, file name, status/review comment, returned date, and PDF action.
5. Bulk delete submits selected report IDs to the route ending in `.dashboard.bulk-delete`.
6. Approved reports can be exported to PDF through a confirmation modal.

Important JavaScript:

- Select-all checkbox syncs individual checkboxes.
- Delete button is enabled only when at least one row is selected.
- Bulk delete and PDF export prefer `window.openStaffConfirmModal` from the staff layout; direct navigation/submission is used as fallback.

## Staff/Intern Reports List

- View: `resources/views/staff/reports/index.blade.php`
- Layout: `resources/views/staff/layouts/app.blade.php`
- Main assets: `admin-reports.css`, `shared-dashboard-theme.css`, inline scripts.

Process:

1. The summary card shows total report count and latest update.
2. The Create Report button links to the staff report creation route.
3. The search form sends a `GET` request to the reports index route.
4. The table lists file name, status, review note, last edited date, and actions.
5. The edit action opens a Bootstrap modal for renaming the report file.
6. The delete action creates a temporary `POST` form with `_method=DELETE`, then confirms through the staff confirmation modal.

Important details:

- The edit modal gets its route from `data-update-template`.
- If `session('clear_report_draft')` is present, the page removes `localStorage.staff_report_draft_{user_id}`.

## Staff/Intern Create Report Form

- View: `resources/views/staff/reports/createReport.blade.php`
- Layout: `resources/views/staff/layouts/app.blade.php`
- Form action: route ending in `.reports.store`
- Main fields: `file_name`, `entries[start_date][]`, `entries[end_date][]`, `entries[activity][]`, `entries[details][]`, `entries[remarks][]`.

Process:

1. The page renders an accomplishment report form with a DICT header image.
2. The first row contains date range, activity/task, details/description, and remarks fields.
3. The Add Row button calls global `addRow()`.
4. New rows calculate the next date from the previous row and append another entry block.
5. The file name is generated from the earliest and latest selected dates.
6. On submit, the script regenerates the file name and blocks submission if no valid start date/file name exists.

Important JavaScript:

- Textareas auto-resize on input.
- Draft data is saved to `localStorage.staff_report_draft_{user_id}`.
- The current script saves draft data but does not visibly restore the saved draft on page load.
- Only the last row keeps a remove button.

## Staff/Intern Report Detail/Edit Screen

- View: `resources/views/staff/reports/show.blade.php`
- Layout: `resources/views/staff/layouts/app.blade.php`
- Update form action: route ending in `.reports.update`
- Submit-for-review form action: route ending in `.reports.submit`

Process:

1. The page displays the selected report in an editable accomplishment-report layout.
2. Existing report entries are rendered with hidden `entry_id[]` fields.
3. Approved reports become read-only and show a locked message.
4. Pending or for-revision reports can be edited and saved.
5. The Submit Report button asks for confirmation through the staff modal before submitting.
6. PDF export is disabled while the report is pending or for revision.

Important details:

- The page shows the review comment when a report was returned for revision.
- The update form displays a success alert immediately on submit before the server response returns.
- Add Row only appears when the report is not approved.

## Staff/Intern Profile Screen

- View: `resources/views/staff/staff_profile.blade.php`
- Layout: `resources/views/staff/layouts/app.blade.php`
- Form action: route ending in `.profile.update`
- Main assets: `edit-profile.css`, `public/js/profile.js`, inline sign-out script.

Process:

1. The page builds profile image and signature URLs from stored media paths.
2. The hero card shows avatar/initials, name, role, and email.
3. The multipart profile form accepts profile photo and signature uploads.
4. First, middle, and last name fields are displayed as read-only for staff/intern users.
5. Position, project, bureau, and office are editable select fields.
6. Save Changes submits the profile update.
7. Sign Out opens the staff confirmation modal if available, then routes to logout.

Important details:

- `profile.js` handles image preview behavior and profile modal helpers.
- Uploaded profile/signature files must be accepted by the backend profile update flow.
