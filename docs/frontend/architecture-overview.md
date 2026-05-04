# Frontend Architecture Overview

## Purpose

This document maps the current frontend structure: Blade views, layouts, components, partials, public CSS/JS assets, Vite resources, and external dependencies.

## Frontend Stack

| Area | Current usage |
| --- | --- |
| Server rendering | Laravel Blade templates in `resources/views` |
| Layouts | Separate admin, staff, and super admin layouts |
| Components | Blade components under `resources/views/components` plus `App\View\Components\Topbar` |
| Styling | Mostly direct files in `public/css`; some large inline `<style>` blocks inside Blade |
| JavaScript | Mostly direct files in `public/js`; some inline `<script>` blocks inside Blade |
| Vite | Configured for `resources/css/app.css` and `resources/js/app.js`, but not the main loading path for most pages |
| CSS framework/CDNs | Bootstrap, Bootstrap Icons, Font Awesome, Google Fonts, AOS, Chart.js |

## Vite And Resource Assets

Configured in `vite.config.js`:

- `resources/css/app.css`
- `resources/js/app.js`

Resource files:

| File | Purpose |
| --- | --- |
| `resources/css/app.css` | Imports Tailwind CSS and defines Tailwind source paths/theme font. |
| `resources/js/app.js` | Imports `./bootstrap`. |
| `resources/js/bootstrap.js` | Imports Axios, sets `window.axios`, and adds `X-Requested-With: XMLHttpRequest`. |

Important note: many Blade screens use `asset('css/...')` and `asset('js/...')` directly instead of `@vite`.

## Layout Inventory

| Layout | Used by | Purpose |
| --- | --- | --- |
| `resources/views/admin/layouts/app.blade.php` | Admin, PH Admin, Super Admin dashboard-style pages | Minimal shell with title, body class, style stack, content, script stack |
| `resources/views/staff/layouts/app.blade.php` | Staff/intern pages | Full shell with Bootstrap/CDNs, staff navbar, confirm modal, inline confirm modal behavior |
| `resources/views/super_admin/layouts/app.blade.php` | Auth/sign-in style super admin pages | Minimal shell for auth screens |

## Main View Inventory

### Auth

| View | Purpose |
| --- | --- |
| `auth/signin.blade.php` | Main email sign-in form for Google Authenticator flow |
| `auth/verify-2fa.blade.php` | Six-digit Google Authenticator code entry screen |

### Shared

| View | Purpose |
| --- | --- |
| `home_page.blade.php` | Shared home page used by multiple roles |

### Staff / Intern

| View | Purpose |
| --- | --- |
| `staff/dashboard.blade.php` | Staff/intern dashboard with report summary and table |
| `staff/staff_profile.blade.php` | Staff/intern profile edit screen |
| `staff/reports/index.blade.php` | Staff/intern report list |
| `staff/reports/createReport.blade.php` | Create draft report form |
| `staff/reports/show.blade.php` | Report detail/edit/submission screen |
| `staff/reports/pdf.blade.php` | PDF rendering template for report export |

### Admin / PH Admin

| View | Purpose |
| --- | --- |
| `admin/dashboard.blade.php` | Super admin dashboard overview cards/charts |
| `admin/reports.blade.php` | Admin/PH Admin reports table and review modal host |
| `admin/users.blade.php` | Managed user list and modal form |
| `admin/ph-users.blade.php` | PH Admin office users page |
| `admin/audit-log.blade.php` | Audit log screen |
| `admin/edit-profile.blade.php` | Admin profile edit screen |
| `admin/notifications.blade.php` | PH Admin notifications inbox |
| `admin/reminders.blade.php` | PH Admin reminders schedule/send-now page |
| `admin/placeholder.blade.php` | Placeholder for unavailable legacy screens |

### Super Admin

| View | Purpose |
| --- | --- |
| `super_admin/reports-table.blade.php` | Super admin reports table wrapper/view |
| `super_admin/authenticator-authorizations.blade.php` | Authenticator access management screen |
| `super_admin/notifications/index.blade.php` | Super admin notification center |

### Errors And Emails

| View | Purpose |
| --- | --- |
| `errors/database-error.blade.php` | Database connection error page |
| `errors/generic.blade.php` | Generic error page |
| `emails/google-authenticator-provisioning.blade.php` | HTML email for authenticator setup |
| `emails/google-authenticator-provisioning-text.blade.php` | Plain-text email for authenticator setup |

## Component And Partial Inventory

### Components

| Component view | Purpose |
| --- | --- |
| `components/topbar.blade.php` | Shared role-aware topbar for admin/super admin and some shared pages |
| `components/alert-notification.blade.php` | Reusable alert notification |
| `components/confirm-modal.blade.php` | Admin/shared confirm modal |
| `components/notification-panel.blade.php` | Notification panel markup |
| `components/notifications.blade.php` | Notification list wrapper |

### Partials

| Partial | Purpose |
| --- | --- |
| `partials/navbar-staff.blade.php` | Staff/intern navbar with notification dropdown logic |
| `partials/navbar-admin.blade.php` | Admin navbar partial |
| `partials/homepage-content.blade.php` | Shared home page content |
| `admin/partials/dashboard-summary-cards.blade.php` | Dashboard summary cards |
| `admin/partials/reports-summary.blade.php` | Report summary cards/counts |
| `admin/partials/reports-table.blade.php` | Report table rows and action data |
| `admin/partials/reports-modal.blade.php` | Admin report preview/review modal |
| `staff/layouts/confirm-modal.blade.php` | Staff confirm modal markup |

## Public CSS Inventory

| File | Primary use |
| --- | --- |
| `public/css/admin-dashboard.css` | Admin dashboard and some admin pages |
| `public/css/admin-reports.css` | Admin reports and staff reports list styling |
| `public/css/audit-log.css` | Audit log page |
| `public/css/dashboard.css` | Dashboard/user/admin shared dashboard styling |
| `public/css/edit-profile.css` | Admin and staff profile forms |
| `public/css/homepage.css` | Home page styling if referenced by older views |
| `public/css/shared-dashboard-theme.css` | Shared dashboard theme tokens/layout helpers |
| `public/css/shared-homepage.css` | Shared home page styles |
| `public/css/shared-navbar.css` | Shared navbar/topbar and notification styles |
| `public/css/sign.css` | Sign-in styling if referenced by older auth views |
| `public/css/verify-otp.css` | Google Authenticator code screen |

Note: Some views reference `css/index.css`, but that file was not present in the current `public/css` inventory.

## Public JavaScript Inventory

| File | Primary behavior |
| --- | --- |
| `public/js/admin-reports.js` | Admin report table filtering, modal preview, review submission via fetch, counts update, auto-open report modal |
| `public/js/admin-users.js` | Dropdown/confirmation behavior for admin user actions |
| `public/js/audit-log.js` | Audit log row/details toggle behavior |
| `public/js/dashboard.js` | User management modal, dynamic role fields, confirmation modal, name sync |
| `public/js/profile.js` | Sign-out modal and avatar/signature preview object URLs |
| `public/js/search-filter.js` | Live search/filter/date quick filter form submission helpers |
| `public/js/toast-notification.js` | Toast notification class and close behavior |
| `public/js/topbar.js` | Notification dropdown open/close and keyboard behavior |
| `public/js/verify-otp.js` | Six-input OTP/2FA code handling, paste, hidden input sync, timer/resend controls |

## External Dependencies Loaded In Views

Observed CDN dependencies:

- Bootstrap CSS/JS
- Bootstrap Icons
- Font Awesome
- Google Fonts: Poppins and Manrope
- Chart.js
- AOS animation library

## Navigation Patterns

There are two main navigation implementations:

1. `components/topbar.blade.php`
   - Role-aware admin/super admin/staff topbar.
   - Uses data prepared by `App\View\Components\Topbar`.
   - Loads `public/js/topbar.js`.

2. `partials/navbar-staff.blade.php`
   - Staff/intern navbar.
   - Pulls user and notification data directly inside the Blade partial.
   - Includes inline JavaScript for fetching and marking notifications read.

## Initial Frontend Risks / Notes

- Several Blade views contain large inline `<style>` and `<script>` blocks.
- Some UI text shows mojibake/encoding artifacts such as broken emoji characters.
- `css/index.css` is referenced but not found in the current `public/css` listing.
- Bootstrap and icon libraries are loaded in several places, sometimes repeatedly.
- Vite is configured but most pages do not use `@vite`.
- Staff/intern navigation logic is partly duplicated between `Topbar` and `navbar-staff`.
- Some page scripts depend tightly on `data-*` attributes in Blade markup; renaming markup can silently break JS.
