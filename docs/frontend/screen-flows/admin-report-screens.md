# Admin And Super Admin Report Screen Flows

This document covers admin dashboards, report tables, and report review modals.

## Admin/Super Admin Dashboard

- View: `resources/views/admin/dashboard.blade.php`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Summary partial: `resources/views/admin/partials/dashboard-summary-cards.blade.php`
- Main assets: `dashboard.css`, `shared-dashboard-theme.css`, `shared-navbar.css`, Chart.js CDN, inline chart script.

Process:

1. The page renders the `<x-topbar>` component with `dashboard` or `reports` active depending on mode.
2. Flash messages appear as bottom-right toast popups.
3. Primary summary cards come from `admin.partials.dashboard-summary-cards`.
4. Secondary KPI cards render from `$kpiCards`.
5. The chart filter form supports all-time, quick ranges, and custom from/to dates.
6. Chart data is embedded into a JSON script tag with id `chart-data`.
7. Inline JavaScript renders the Users by Role doughnut chart and Reports Overview stacked bar chart.

Important details:

- Chart rendering depends on `Chart` from the Chart.js CDN.
- The chart date inputs use `max="{{ date('Y-m-d') }}"`.
- Toast messages auto-dismiss after five seconds.

## Admin/Super Admin Reports Table

- Admin view: `resources/views/admin/reports.blade.php`
- Super admin table view: `resources/views/super_admin/reports-table.blade.php`
- Shared summary partial: `resources/views/admin/partials/reports-summary.blade.php`
- Admin table partial: `resources/views/admin/partials/reports-table.blade.php`
- Main JavaScript: `public/js/admin-reports.js`

Process:

1. The page renders summary cards for Submitted, Approved, Pending, and For Revision reports.
2. The report table supports server-side search and status filtering.
3. `admin-reports.js` also applies client-side filtering for visible rows after page load.
4. Each row carries a JSON report payload in `data-report`.
5. Clicking View or View & Review opens the shared report modal.
6. Pagination is rendered by the Blade view when the paginator has more pages.

Admin-only behavior:

- Admin reports table includes approved-report checkboxes and a Delete Selected bulk action.
- Bulk delete posts to `route('admin.dashboard.bulk-delete')`.
- Approved rows can be selected; pending and for-revision rows have disabled checkboxes.
- Admin rows may include attachment download links when `file_path` exists.

Super admin monitoring behavior:

- The super admin report table is read-only.
- It displays a banner stating report files are not accessible in this view.
- Download URLs are intentionally set to `null`.
- The modal opens in monitoring mode unless `$canManageReportRecords` is true.

## Admin Report Review Modal

- Partial: `resources/views/admin/partials/reports-modal.blade.php`
- Main JavaScript: `public/js/admin-reports.js`
- Trigger selector: `[data-open-report-modal]`
- Modal selector: `[data-report-modal]`

Process:

1. The trigger button parses the row's `data-report` JSON payload.
2. The modal preview is populated with file name, prepared-by name, submitted date, status, entries, and signature.
3. The entries table is rendered client-side from the payload.
4. If the user can manage report records, the sidebar shows Approve and For Revision radio choices.
5. Choosing For Revision reveals the review comment textarea.
6. Approve Report or Return For Revision sends an AJAX `POST` request to the row's `status_url`.
7. On success, the script updates the modal status, table row status pill, summary counts, existing comment panel, and filtered visible rows.

Important details:

- AJAX payload includes `_token`, `status`, and optional `comment`.
- Requests are sent as `application/x-www-form-urlencoded` with `X-Requested-With: XMLHttpRequest`.
- Pressing Escape closes the modal.
- The modal can auto-open a report when the dashboard has `data-auto-open-report-id`.
