# Inline Scripts And External CDN Dependencies

This document lists inline JavaScript/CSS inside Blade views and external CDN dependencies loaded by frontend screens.

## Inline JavaScript Inside Blade Views

### `resources/views/auth/signin.blade.php`

Inline behavior:

- Waits for `DOMContentLoaded`.
- Finds `[data-send-otp-form]`.
- Disables `[data-send-otp-button]` on submit.
- Changes button label to `Checking account...`.

Purpose:

- Prevents duplicate sign-in/OTP requests.

### `resources/views/home_page.blade.php`

Inline behavior:

- Initializes AOS animations for the staff branch.
- Initializes AOS animations on `DOMContentLoaded` for the admin/super admin branch when `window.AOS` exists.

Important note:

- Staff branch loads Bootstrap bundle before content and again after content.

### `resources/views/staff/layouts/app.blade.php`

Inline behavior:

- Defines global `window.openStaffConfirmModal`.
- Opens/closes the shared staff confirmation modal.
- Supports `danger`, `success`, and default variants.
- Locks body scroll while modal is open.
- Closes on cancel, backdrop, close targets, and Escape.

Pages depending on it:

- Staff dashboard bulk delete/export.
- Staff reports list delete.
- Staff report detail submit.
- Staff profile sign out.

### `resources/views/partials/navbar-staff.blade.php`

Inline behavior:

- Refreshes staff notifications through `fetch()`.
- Marks notifications read through `fetch()`.
- Opens/closes notification dropdown.
- Updates unread badge/count.
- Handles notification link click before navigation.

Important dependencies:

- Staff notification routes based on `$staffPortalPrefix`.
- CSRF token.

### `resources/views/components/alert-notification.blade.php`

Inline behavior:

- Intended to auto-dismiss Bootstrap alerts after the `autoDismiss` interval.

Important code note:

- The auto-dismiss block appears to be missing an opening `<script>` tag before `document.addEventListener(...)`.

### `resources/views/staff/staff_profile.blade.php`

Inline behavior:

- Handles Sign Out button.
- Uses `window.openStaffConfirmModal` if available.
- Falls back to direct `route('logout')` navigation.

### `resources/views/staff/reports/createReport.blade.php`

Inline behavior:

- Auto-resizes textareas.
- Adds report entry rows.
- Removes the latest row.
- Generates file names from date ranges.
- Saves draft data to `localStorage`.
- Blocks submit when no valid generated file name exists.

Important global/function names:

- `AUTO_SAVE_KEY`
- `autoResize`
- `initTextareas`
- `emptyRowMarkup`
- `addRow`
- `generateFileName`
- `collectDraftData`
- `saveDraft`
- `initDateListeners`
- `showInlineError`

Important note:

- Draft data is saved but no matching restore-on-load flow was found in the current script.

### `resources/views/staff/reports/index.blade.php`

Inline behavior:

- Populates edit modal from report action button data.
- Sets edit form action URL.
- Disables submit button and shows saving state.
- Creates and submits a temporary delete form.
- Confirms delete through staff confirmation modal.
- Clears localStorage draft when `session('clear_report_draft')` is set.

Dependencies:

- Bootstrap modal events: `show.bs.modal`, `hidden.bs.modal`.
- Staff confirmation modal API.

### `resources/views/staff/reports/show.blade.php`

Inline behavior:

- Auto-resizes textareas.
- Adds new entry rows to existing report.
- Displays success alert when update form is submitted.
- Confirms Submit Report through staff confirmation modal.
- Has inline PDF export `onclick` logic on the export button.

### `resources/views/staff/dashboard.blade.php`

Inline behavior:

- Select-all checkbox handling.
- Enables/disables Delete Selected.
- Confirms bulk delete through staff confirmation modal.
- Confirms approved-report PDF export through staff confirmation modal.

### `resources/views/admin/dashboard.blade.php`

Inline behavior:

- Reads chart JSON from `<script id="chart-data" type="application/json">`.
- Renders Users by Role doughnut chart.
- Renders Reports Overview stacked bar chart.
- Builds custom legends.
- Handles chart date range control behavior.
- Auto-dismisses toast after five seconds.

Dependency:

- Chart.js global `Chart`.

### `resources/views/admin/reports.blade.php`

Inline behavior:

- Select-all checkbox handling for approved reports.
- Enables/disables Delete Selected.
- Opens custom delete-confirm modal.
- Submits bulk delete form after confirmation.
- Closes modal with backdrop, cancel, and Escape.

This is separate from `admin-reports.js`, which owns report preview/review modal behavior.

### `resources/views/admin/users.blade.php`

Inline behavior:

- Embeds `dashboard-config` JSON.
- Assigns parsed JSON to `window.dashboardConfig`.
- Auto-dismisses user status/error toast after five seconds.

Public script dependency:

- `public/js/dashboard.js`

### `resources/views/admin/reminders.blade.php`

Inline behavior:

- Creates manual success toast when `session('status')` exists.
- Creates manual error toast from first validation error.
- Auto-dismisses success toast after four seconds and error toast after five seconds.
- Defines `toastSlideIn` and `toastSlideOut` keyframes inline.

### `resources/views/errors/generic.blade.php`

Inline behavior:

- Try Again button calls `location.reload()`.

### `resources/views/errors/database-error.blade.php`

Inline behavior:

- Try Again button calls `location.reload()`.

## Inline CSS Inside Blade Views

Large inline style blocks are currently present in:

- `resources/views/auth/signin.blade.php`
- `resources/views/home_page.blade.php`
- `resources/views/staff/layouts/app.blade.php`
- `resources/views/staff/dashboard.blade.php`
- `resources/views/staff/staff_profile.blade.php`
- `resources/views/staff/reports/createReport.blade.php`
- `resources/views/staff/reports/index.blade.php`
- `resources/views/staff/reports/show.blade.php`
- `resources/views/staff/reports/pdf.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/reminders.blade.php`
- `resources/views/admin/notifications.blade.php`
- `resources/views/admin/ph-users.blade.php`
- `resources/views/admin/placeholder.blade.php`
- `resources/views/super_admin/authenticator-authorizations.blade.php`
- `resources/views/super_admin/notifications/index.blade.php`
- `resources/views/errors/generic.blade.php`
- `resources/views/errors/database-error.blade.php`
- `resources/views/components/notifications.blade.php`

Common reasons:

- Page-specific cards/tables/modals.
- One-off dashboard styles.
- Modal styling.
- Error-page styling.
- Email/client-safe styling.

## External CDN Dependencies

### Bootstrap CSS

Used by:

- `resources/views/staff/layouts/app.blade.php`
- `resources/views/home_page.blade.php` staff branch
- `resources/views/auth/signin.blade.php`
- `resources/views/errors/generic.blade.php`
- `resources/views/errors/database-error.blade.php`

Versions observed:

- Bootstrap CSS `5.3.2`
- Bootstrap CSS `5.3.3`

### Bootstrap JavaScript Bundle

Used by:

- `resources/views/staff/layouts/app.blade.php`
- `resources/views/home_page.blade.php`
- `resources/views/errors/generic.blade.php`
- `resources/views/errors/database-error.blade.php`

Versions observed:

- Bootstrap JS `5.3.0`
- Bootstrap JS `5.3.2`

### Bootstrap Icons

Used by:

- `resources/views/staff/layouts/app.blade.php`
- `resources/views/home_page.blade.php`
- Error pages.

Version observed:

- Bootstrap Icons `1.11.1`

### Google Fonts

Poppins is used by:

- Most dashboard/admin/staff pages.
- 2FA screen.
- Shared home page.

Manrope is used by:

- Sign-in screen.

### Font Awesome

Used by:

- Staff layout.
- Shared home page.
- Homepage content partial feature icons.

Sources observed:

- CSS from Cloudflare CDN: Font Awesome `6.5.0`.
- Kit script: `https://kit.fontawesome.com/a076d05399.js`.

### AOS

Used by:

- Shared home page.
- Homepage content partial through `data-aos` attributes.

Version observed:

- AOS `2.3.1`.

### Chart.js

Used by:

- Admin dashboard charts.

Version observed:

- Chart.js `4.4.2`.
