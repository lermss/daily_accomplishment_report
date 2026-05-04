# Layouts, Blade Components, And Partials

This document maps the reusable Blade code areas that shape the frontend shell, navigation, notifications, shared modals, and repeated report/dashboard sections.

## Layouts

### `resources/views/admin/layouts/app.blade.php`

Used by admin, PH admin, and several super admin dashboard-style pages.

Code structure:

```blade
<title>@yield('title', 'Admin Panel')</title>
@stack('styles')
<body class="@yield('body_class')">
    @yield('content')
    @stack('scripts')
</body>
```

Responsibilities:

- Provides the smallest shared dashboard shell.
- Lets each page provide its own page title through `@section('title')`.
- Lets each page provide body-specific styling hooks through `@section('body_class')`.
- Loads page CSS through `@push('styles')`.
- Loads page JavaScript through `@push('scripts')`.

Important detail:

- This layout does not load Bootstrap, fonts, navigation, or global CSS by itself. Each admin-like screen must push the assets it needs.

### `resources/views/staff/layouts/app.blade.php`

Used by staff/intern dashboard, reports, report detail/create, and staff profile screens.

Main code used:

- Bootstrap CSS CDN.
- Bootstrap Icons CDN.
- Poppins Google Font.
- Font Awesome CDN.
- `asset('css/index.css')`.
- `asset('css/shared-navbar.css')`.
- Bootstrap bundle CDN.
- `@include('partials.navbar-staff')`.
- `@yield('content')`.
- `@include('staff.layouts.confirm-modal')`.
- Inline JavaScript that defines `window.openStaffConfirmModal`.

Responsibilities:

- Provides the full staff/intern shell.
- Loads staff navigation on every staff page.
- Wraps each page in `<div class="container mt-4">`.
- Provides one shared confirmation modal API for staff pages.

Shared confirmation API:

```js
window.openStaffConfirmModal({
    title: 'Confirm Action',
    message: 'Are you sure you want to continue?',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    variant: 'danger',
    onConfirm: function () {}
});
```

Modal selectors used by the layout script:

- `[data-staff-confirm-modal]`
- `[data-staff-confirm-title]`
- `[data-staff-confirm-message]`
- `[data-staff-confirm-icon]`
- `[data-staff-confirm-cancel]`
- `[data-staff-confirm-submit]`
- `[data-staff-confirm-close]`

Important details:

- `css/index.css` is referenced here but is not present in `public/css`.
- Bootstrap Icons is loaded twice in this layout.
- The layout owns confirmation modal behavior, so staff pages depend on `window.openStaffConfirmModal` existing.

### `resources/views/super_admin/layouts/app.blade.php`

Used by authentication-style super admin pages, especially the sign-in screen.

Code structure:

```blade
<title>@yield('title', 'Super Admin')</title>
@stack('styles')
<body class="@yield('body_class')">
    @yield('content')
    @stack('scripts')
</body>
```

Responsibilities:

- Provides a minimal auth shell.
- Lets each auth screen define all visual and script dependencies.

## Blade Components

### `resources/views/components/topbar.blade.php`

Used by admin, PH admin, super admin, and some shared pages.

Primary responsibilities:

- Renders DICT and Bagong Pilipinas logos.
- Renders role-aware navigation links.
- Shows notifications with unread badges.
- Shows the profile edit icon.
- Loads `public/js/topbar.js` with `defer`.

Navigation links are controlled by component state:

- Home: `route('dashboard.home')`
- Dashboard: `route('dashboard')`
- Reports: `$reportsRoute`, only for super admin navigation.
- Reminders: `route('admin.dashboard.reminders.index')`, only when reminders are manageable.
- Users: `route('dashboard.admin.users')` for PH admin office users, or `route('dashboard.users')` for super admin user management.
- Authenticator Access: `route('super-admin.authenticator.index')`.
- Audit Log: `route('audit.index')`.
- Profile: `route('profile.edit')`.

Notification panel modes:

- Super admin mode shows latest super admin notifications.
- Staff mode shows office reminders and report review alerts.
- Admin mode shows pending report submissions.
- Users without notification access get an availability message.

Important selectors:

- `[data-notification-menu]`
- `[data-notification-toggle]`
- `[data-notification-panel]`

### `resources/views/components/confirm-modal.blade.php`

Used by admin/user-management flows that need a reusable yes/no modal.

Important selectors:

- `[data-confirm-modal]`
- `[data-confirm-title]`
- `[data-confirm-message]`
- `[data-confirm-cancel]`
- `[data-confirm-submit]`

The JavaScript that drives this component currently lives in `public/js/dashboard.js` for user actions and in page-specific scripts where needed.

### `resources/views/components/alert-notification.blade.php`

Reusable Bootstrap alert component.

Props:

- `type`, default `info`.
- `dismissible`, default `true`.
- `autoDismiss`, default `5000`.

Supported types:

- `success`
- `error`
- `warning`
- `info`

Important code note:

- The component contains `document.addEventListener(...)` and a closing `</script>` inside the auto-dismiss block, but the opening `<script>` tag is missing in the current file. If this component is used, the auto-dismiss JavaScript may render incorrectly.

### `resources/views/components/notifications.blade.php`

Flash-message wrapper component.

Responsibilities:

- Reads session keys `error`, `success`, `warning`, and `info`.
- Converts them to alert notifications.
- Provides fixed-position notification container styles.

Important detail:

- This component uses inline CSS for `.notification-container` and alert animations.

### `resources/views/components/notification-panel.blade.php`

Reusable notification panel component that accepts notification data through props.

Props:

- `notifications`
- `notificationRoute`
- `canViewNotifications`
- `isSuperAdminNavigation`

Important selectors:

- `[data-notification-panel]`
- `[data-notification-item]`
- `data-notification-url`
- `data-notification-id`

Current usage note:

- The main topbar currently inlines its notification panel logic in `components/topbar.blade.php`, so this component appears to be a reusable/legacy variant.

## Partials

### `resources/views/partials/navbar-staff.blade.php`

Staff/intern navigation partial.

Responsibilities:

- Renders DICT and Bagong Pilipinas logos.
- Builds staff portal links.
- Displays staff notification dropdown.
- Fetches notification updates from the staff notification routes.
- Marks staff notifications as read before navigating.

Important script behavior:

- Uses `fetch()` for notification list refresh.
- Uses `fetch()` for notification read marking.
- Redirects through `window.location.href`.
- Adds click listeners to notification links.

Important route dependencies:

- `route($staffPortalPrefix . '.notifications.index')`
- `route($staffPortalPrefix . '.notifications.read')`

### `resources/views/partials/navbar-admin.blade.php`

Admin navbar partial.

Responsibilities:

- Renders admin logos and navigation.
- Builds report notification counts with role/office scoping.
- Loads `public/js/topbar.js`.

Current usage note:

- Most active admin screens now use `<x-topbar>`. This partial appears to be an older or alternate admin navbar.

### `resources/views/partials/homepage-content.blade.php`

Shared home page content partial.

Inputs:

- `$dashboardRoute`

Responsibilities:

- Renders shared DAR system hero.
- Renders DICT logo image.
- Renders feature cards with AOS attributes.

External dependency:

- Uses Font Awesome icon classes like `fas fa-chart-line`.
- Uses AOS attributes like `data-aos="fade-up"`.

### `resources/views/admin/partials/dashboard-summary-cards.blade.php`

Admin dashboard summary card partial.

Input:

- `$stats`

Responsibilities:

- Loops through dashboard stats.
- Renders clickable cards with labels, counts, metadata, and CSS-built illustrations.

### `resources/views/admin/partials/reports-summary.blade.php`

Admin/super admin report summary partial.

Inputs:

- `$isSuperAdminView`
- `$counts`
- `$mode`
- `$reportRoutes`
- `$latestApprovedAt`

Responsibilities:

- Renders page intro.
- Renders Submitted, Approved, Pending, and For Revision summary cards.
- Uses `data-summary-card` and `data-filter-value` for client-side filtering in `admin-reports.js`.

### `resources/views/admin/partials/reports-table.blade.php`

Admin report table partial.

Inputs:

- `$reports`
- `$search`
- `$statusFilter`
- `$statusFilterOptions`
- `$isSuperAdminView`
- `$canManageReportRecords`

Responsibilities:

- Renders search and status filter form.
- Renders bulk delete form.
- Renders report rows with avatar/signature media URLs.
- Builds a JSON report payload for each row.
- Provides `data-open-report-modal` buttons consumed by `admin-reports.js`.

Important selectors/data:

- `[data-reports-body]`
- `[data-report-row]`
- `[data-status-pill]`
- `[data-open-report-modal]`
- `data-report`
- `data-search`
- `data-status`

### `resources/views/admin/partials/reports-modal.blade.php`

Shared admin/super admin report preview and review modal.

Responsibilities:

- Renders A4-style report preview.
- Renders report metadata, entries, signature, status, and download/export links.
- Renders approve/for-revision review controls when `$canManageReportRecords` is true.

Important selectors:

- `[data-report-modal]`
- `[data-close-report-modal]`
- `[data-modal-subtitle]`
- `[data-preview-file-name]`
- `[data-preview-user-name]`
- `[data-preview-submitted-at]`
- `[data-preview-status-label]`
- `[data-preview-entries]`
- `[data-preview-signature]`
- `[data-preview-download]`
- `[data-review-choice]`
- `[data-review-comment]`
- `[data-approve-button]`
- `[data-return-button]`
- `[data-review-feedback]`

### `resources/views/staff/layouts/confirm-modal.blade.php`

Staff confirmation modal markup.

Driven by:

- Inline script in `resources/views/staff/layouts/app.blade.php`.

Important selectors:

- `[data-staff-confirm-modal]`
- `[data-staff-confirm-close]`
- `[data-staff-confirm-title]`
- `[data-staff-confirm-message]`
- `[data-staff-confirm-icon]`
- `[data-staff-confirm-cancel]`
- `[data-staff-confirm-submit]`
