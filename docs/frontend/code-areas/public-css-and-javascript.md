# Public CSS And JavaScript Files

This document maps the public frontend asset files used by the Blade screens.

## Public CSS Files

### `public/css/admin-dashboard.css`

Size: about 12 KB.

Primary use:

- Admin report pages.
- PH admin notification page.
- Admin dashboard-style cards and panels.

Loaded by:

- `resources/views/admin/reports.blade.php`
- `resources/views/admin/notifications.blade.php`
- `resources/views/super_admin/reports-table.blade.php`

### `public/css/admin-reports.css`

Size: about 32 KB.

Primary use:

- Admin report summary cards, table, modal, status pills, and pagination.
- Staff report list also reuses it.
- Super admin reports and notifications reuse parts of it.

Loaded by:

- `resources/views/admin/reports.blade.php`
- `resources/views/staff/reports/index.blade.php`
- `resources/views/super_admin/reports-table.blade.php`
- `resources/views/super_admin/notifications/index.blade.php`

Important coupled JavaScript:

- `public/js/admin-reports.js` expects classes and data attributes rendered by the report table and modal.

### `public/css/audit-log.css`

Size: about 13 KB.

Primary use:

- Audit log hero, summary cards, filters, table, details rows, status badges, and pagination.

Loaded by:

- `resources/views/admin/audit-log.blade.php`

### `public/css/dashboard.css`

Size: about 25 KB.

Primary use:

- Admin dashboard layout.
- User management table and modal.
- PH admin office users page.
- PH admin reminders page.
- Audit log base dashboard layout.

Loaded by:

- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/users.blade.php`
- `resources/views/admin/audit-log.blade.php`
- `resources/views/admin/reminders.blade.php`
- `resources/views/admin/ph-users.blade.php`

### `public/css/edit-profile.css`

Size: about 21 KB.

Primary use:

- Admin profile edit screen.
- Staff/intern profile edit screen.

Loaded by:

- `resources/views/admin/edit-profile.blade.php`
- `resources/views/staff/staff_profile.blade.php`

Coupled JavaScript:

- `public/js/profile.js`

### `public/css/homepage.css`

Size: about 13 KB.

Primary use:

- Older or alternate homepage styling.

Current note:

- The active shared home page uses `shared-homepage.css`; no current Blade reference to `homepage.css` was found in the latest scan.

### `public/css/shared-dashboard-theme.css`

Size: about 10 KB.

Primary use:

- Shared dashboard theme tokens and page structure.
- Admin, PH admin, and super admin dashboard-like pages.
- Staff report list.

Loaded by:

- Admin dashboard, reports, users, audit log, reminders, PH users, notifications.
- Staff reports list.
- Super admin reports, authenticator access, notifications.

### `public/css/shared-homepage.css`

Size: about 10 KB.

Primary use:

- Shared home page hero and feature cards.

Loaded by:

- `resources/views/home_page.blade.php`

### `public/css/shared-navbar.css`

Size: about 8 KB.

Primary use:

- Shared topbar/navbar styles.
- Notification dropdown styles.

Loaded by:

- Admin dashboard-like pages.
- Shared home page.
- Staff layout.
- Admin profile.
- Super admin pages.

Coupled JavaScript:

- `public/js/topbar.js`

### `public/css/sign.css`

Size: about 3 KB.

Primary use:

- Legacy or alternate sign-in styling.

Current note:

- The current sign-in view uses inline CSS instead of this file.

### `public/css/verify-otp.css`

Size: about 4 KB.

Primary use:

- Google Authenticator / 2FA verification screen.

Loaded by:

- `resources/views/auth/verify-2fa.blade.php`

Coupled JavaScript:

- `public/js/verify-otp.js`

## Resource CSS

### `resources/css/app.css`

Purpose:

- Imports Tailwind CSS.
- Defines Tailwind source paths and theme font.

Current note:

- Vite is configured for this file, but the current Blade UI mostly loads direct files from `public/css`.

## Public JavaScript Files

### `public/js/admin-reports.js`

Size: about 16 KB.

Primary use:

- Admin reports page.
- Super admin reports table.

Responsibilities:

- Closes other legacy report action dropdowns.
- Applies live report row filtering.
- Syncs report summary card active state.
- Opens/closes the report review modal.
- Populates modal preview fields from row JSON payload.
- Renders report entries and staff signature.
- Shows/hides approve and for-revision controls.
- Sends review decisions with `fetch()`.
- Updates row status, modal status, counts, and filtered rows after review.
- Auto-opens a report modal when `data-auto-open-report-id` is present.

Important selectors:

- `[data-admin-dashboard]`
- `[data-reports-body]`
- `[data-report-row]`
- `[data-summary-card]`
- `[data-report-search]`
- `[data-status-filter]`
- `[data-results-summary]`
- `[data-report-modal]`
- `[data-open-report-modal]`
- `[data-review-choice]`

### `public/js/admin-users.js`

Size: about 1 KB.

Primary use:

- Legacy/alternate admin user action behavior.

Responsibilities:

- Handles dropdown or confirmation behavior for admin user actions.

Current note:

- The active user-management page loads `dashboard.js`, not `admin-users.js`.

### `public/js/audit-log.js`

Size: less than 1 KB.

Primary use:

- Audit log screen.

Responsibilities:

- Finds all `[data-audit-toggle]` buttons.
- Toggles the target details row's `hidden` state.
- Updates `aria-expanded`.

### `public/js/dashboard.js`

Size: about 9.5 KB.

Primary use:

- User management screen.

Responsibilities:

- Drives archive/restore confirmation modal.
- Opens/closes create/edit user modal.
- Switches between create and edit mode.
- Builds full name from first/middle/last name.
- Populates role-dependent project, bureau, division, and office selects.
- Shows/hides fields based on selected role.
- Sets required fields based on selected role.
- Applies old validation values after failed form submissions.

Important global:

- `window.dashboardConfig`

Important selectors:

- `[data-user-modal]`
- `[data-open-user-modal]`
- `[data-user-form]`
- `[data-user-form-method]`
- `[data-user-modal-title]`
- `[data-role-radio]`
- `[data-field]`
- `[data-combined-name]`
- `[data-confirm-trigger]`

### `public/js/profile.js`

Size: about 4 KB.

Primary use:

- Admin profile edit screen.
- Staff profile edit screen.

Responsibilities:

- Handles sign-out modal controls where matching selectors exist.
- Shows profile image preview from selected file.
- Shows signature image preview from selected file.
- Uses object URLs and revokes old preview URLs when replaced.

Important selectors:

- `[data-open-signout-modal]`
- `[data-signout-modal]`
- `[data-close-signout-modal]`
- `[data-avatar-input]`
- `[data-avatar-preview]`
- `[data-avatar-placeholder]`
- `[data-signature-input]`
- `[data-signature-preview]`

### `public/js/search-filter.js`

Size: about 1.7 KB.

Primary use:

- User management filters.
- PH users filters.
- Admin report pages.

Responsibilities:

- Adds live search/filter form behavior.
- Supports data attributes used by search/filter bars.
- Helps keep table filtering interactions consistent.

Important selectors:

- `[data-search-filter-form]`
- `[data-live-search]`
- `[data-live-filter]`

### `public/js/toast-notification.js`

Size: about 6.6 KB.

Primary use:

- Toast notification utilities.

Current active use:

- Loaded by `resources/views/admin/reminders.blade.php`.

Responsibilities:

- Provides reusable toast creation/close behavior.
- Supports toast styling and timed removal.

### `public/js/topbar.js`

Size: about 1.3 KB.

Primary use:

- Shared topbar notification dropdown.

Responsibilities:

- Opens/closes notification panels.
- Keeps `aria-expanded` synced.
- Closes the panel on outside click.
- Closes the panel with Escape.

Important selectors:

- `[data-notification-menu]`
- `[data-notification-toggle]`
- `[data-notification-panel]`

### `public/js/verify-otp.js`

Size: about 2.4 KB.

Primary use:

- Google Authenticator / 2FA verification screen.

Responsibilities:

- Syncs six visible OTP inputs into hidden `code` input.
- Allows only numeric input.
- Moves focus forward/backward.
- Supports pasting a full code.
- Includes optional timer/resend controls.

Important selectors:

- `[data-otp-form]`
- `[data-otp-input]`
- `[data-otp-hidden]`
- `[data-otp-timer]`
- `[data-resend-button]`

## Resource JavaScript

### `resources/js/app.js`

Purpose:

- Imports `./bootstrap`.

### `resources/js/bootstrap.js`

Purpose:

- Imports Axios.
- Sets `window.axios`.
- Sets the default `X-Requested-With: XMLHttpRequest` header.

Current note:

- These files are part of the Vite path, but most active frontend screens use direct public JavaScript files.
