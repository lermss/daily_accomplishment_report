# Frontend Risks And Cleanup Notes

This document records known frontend risks, cleanup targets, and places where behavior depends tightly on Blade markup. It is written as a practical maintenance map, not a bug list where every item must be fixed immediately.

## Inline CSS That Could Be Extracted

Several Blade files contain large inline `<style>` blocks. These styles work, but they make the frontend harder to maintain because design rules are split between Blade templates and `public/css`.

High-priority extraction candidates:

- `resources/views/auth/signin.blade.php`
  - Contains the full sign-in screen visual design inline.
  - Candidate target file: `public/css/sign.css` or a new auth-specific CSS file.
- `resources/views/staff/layouts/app.blade.php`
  - Contains staff report action icon styles and the full staff confirmation modal style block.
  - Candidate target file: `public/css/shared-navbar.css`, `public/css/dashboard.css`, or a new `staff.css`.
- `resources/views/staff/dashboard.blade.php`
  - Contains dashboard cards/table/action styling.
  - Candidate target file: staff dashboard CSS.
- `resources/views/staff/reports/createReport.blade.php`
  - Contains report form/table styling.
  - Candidate target file: report editor CSS.
- `resources/views/staff/reports/index.blade.php`
  - Contains large report-list styling despite also loading `admin-reports.css`.
- `resources/views/staff/reports/show.blade.php`
  - Contains report detail/edit styles.
- `resources/views/admin/dashboard.blade.php`
  - Contains KPI card, chart, and toast styles.
- `resources/views/admin/reminders.blade.php`
  - Contains most of the PH reminder page styling inline.
- `resources/views/admin/notifications.blade.php`
  - Contains the PH notification page styling inline.
- `resources/views/super_admin/authenticator-authorizations.blade.php`
  - Contains the authenticator access table/page styling inline.
- `resources/views/super_admin/notifications/index.blade.php`
  - Contains super admin notification center styling inline.
- `resources/views/errors/generic.blade.php` and `resources/views/errors/database-error.blade.php`
  - Contain standalone error-page styles.

Cleanup recommendation:

1. Extract page-specific styles only after the screen behavior is stable.
2. Group extracted styles by feature, not by random file size.
3. Keep error pages standalone if they need to work during partial application failure.
4. Avoid moving email template inline styles because email clients need inline CSS.

## Inline JS That Could Be Extracted

Several views contain inline scripts with real behavior. Inline scripts are workable for small one-off actions, but the current app has enough repeated modal, toast, and report behavior that extraction would make maintenance safer.

High-priority extraction candidates:

- `resources/views/staff/layouts/app.blade.php`
  - Defines `window.openStaffConfirmModal`.
  - Candidate target: `public/js/staff-confirm-modal.js`.
- `resources/views/partials/navbar-staff.blade.php`
  - Fetches staff notifications and marks them read.
  - Candidate target: `public/js/staff-notifications.js`.
- `resources/views/staff/reports/createReport.blade.php`
  - Dynamic row handling, file-name generation, draft saving.
  - Candidate target: `public/js/staff-report-editor.js`.
- `resources/views/staff/reports/show.blade.php`
  - Dynamic rows, textarea resizing, submit confirmation.
  - Candidate target: same report editor JS with create/edit modes.
- `resources/views/staff/reports/index.blade.php`
  - Edit modal setup and delete form creation.
  - Candidate target: `public/js/staff-reports-list.js`.
- `resources/views/staff/dashboard.blade.php`
  - Bulk selection/delete and PDF export confirmation.
  - Candidate target: `public/js/staff-dashboard.js`.
- `resources/views/admin/reports.blade.php`
  - Bulk delete modal/selection logic.
  - Candidate target: `public/js/admin-report-bulk-delete.js`.
- `resources/views/admin/dashboard.blade.php`
  - Chart.js rendering and chart filter behavior.
  - Candidate target: `public/js/admin-dashboard-charts.js`.
- `resources/views/admin/reminders.blade.php`
  - Custom success/error toast DOM creation.
  - Candidate target: use `public/js/toast-notification.js` consistently.

Cleanup recommendation:

1. Extract scripts only after documenting the `data-*` selectors they require.
2. Keep server-generated JSON/config blocks in Blade, but move behavior into public JS.
3. Prefer one script per behavior area: notifications, report editor, report review, profile preview, toasts.

## Missing `public/css/index.css`

The file `public/css/index.css` is referenced but was not found in the current `public/css` inventory.

References:

- `resources/views/home_page.blade.php`
- `resources/views/staff/layouts/app.blade.php`

Risk:

- The browser may request `/css/index.css` and receive a 404.
- Intended shared staff/home styles may be missing.
- A missing CSS request can create noise in browser/network logs and make debugging harder.

Cleanup options:

1. Restore/create `public/css/index.css` if it is supposed to exist.
2. Remove the link if the styles were replaced by `shared-navbar.css` and `shared-homepage.css`.
3. Rename the reference to the correct existing file if this is a stale path.

## Duplicate CDN / Script / Style Includes

Several dependencies are loaded multiple times or with different versions.

Examples:

- `resources/views/staff/layouts/app.blade.php`
  - Loads Bootstrap Icons twice.
  - Loads Bootstrap CSS `5.3.2`.
  - Loads Bootstrap JS bundle `5.3.0`.
- `resources/views/home_page.blade.php`
  - Staff branch loads Bootstrap JS `5.3.0` before content and Bootstrap JS `5.3.2` after content.
  - Loads Font Awesome CSS and also a Font Awesome kit script.
  - Loads AOS in both staff and admin/super admin branches.
- `resources/views/auth/signin.blade.php`
  - Loads Bootstrap CSS `5.3.3`, while many other screens use `5.3.2`.
- Error pages load Bootstrap CSS/JS independently, which is acceptable if they must be standalone.
- `public/css/shared-navbar.css` imports the Poppins Google Font while many Blade files also load Poppins directly.

Risk:

- Duplicate downloads.
- Version mismatch behavior, especially with Bootstrap modal/dropdown events.
- Harder performance tuning.
- Harder visual consistency because each page decides its own dependency list.

Cleanup recommendation:

1. Choose standard Bootstrap CSS/JS versions for the app.
2. Load Bootstrap Icons only once per layout.
3. Pick either Font Awesome CSS or kit script where possible.
4. Move common dependencies into shared layouts.
5. Keep error pages standalone only if they need to render when main assets are unavailable.

## Mojibake / Encoding Artifacts In UI Text

The scan found multiple mojibake-style characters in Blade and JS output, such as broken checkmarks, icons, bullets, em dashes, and emoji.

Examples:

- `resources/views/admin/dashboard.blade.php`
  - Toast icon text includes corrupted characters.
  - Some comments and separators show encoding artifacts.
- `resources/views/admin/audit-log.blade.php`
  - Pagination text has corrupted dash characters.
- `resources/views/admin/reminders.blade.php`
  - Pagination text and comments contain corrupted characters.
- `resources/views/admin/notifications.blade.php`
  - Report icon before file name appears corrupted.
- `resources/views/components/topbar.blade.php`
  - Staff notification icons include corrupted emoji sequences.
- `public/js/toast-notification.js`
  - Toast icons show corrupted symbols.

Risk:

- UI can look unpolished or confusing.
- Screen readers may announce strange characters.
- Future edits can spread mixed encodings.

Cleanup recommendation:

1. Normalize affected files to UTF-8.
2. Replace decorative emoji/text icons with inline SVG or icon-font classes already used in the app.
3. Replace corrupted punctuation with plain ASCII where possible.
4. Add a short encoding check to reviews before releases.

## Mixed Navigation Implementations

There are multiple navigation systems in the frontend.

Current implementations:

- `resources/views/components/topbar.blade.php`
  - Active shared topbar for admin, PH admin, super admin, and some shared pages.
  - Uses `public/js/topbar.js`.
- `resources/views/partials/navbar-staff.blade.php`
  - Staff/intern navbar with its own notification fetch/read behavior.
  - Included by staff layout.
- `resources/views/partials/navbar-admin.blade.php`
  - Older/alternate admin navbar partial.
  - Also loads `public/js/topbar.js`.

Risk:

- Navigation behavior can drift between roles.
- Notification behavior exists in both shared topbar and staff navbar.
- Fixes to one navbar may not apply to the other.
- Role-based route visibility is harder to audit.

Cleanup recommendation:

1. Decide whether staff should move to `<x-topbar>` or whether staff navbar remains intentionally separate.
2. If staff remains separate, extract staff notification behavior into a public JS file.
3. Confirm whether `partials/navbar-admin.blade.php` is still used. If not, mark it legacy or remove later.
4. Keep route/permission decisions in component classes or services where possible.

## Accessibility Concerns

Known accessibility concerns:

- Some icon-only buttons have labels, but not all controls consistently expose intent.
- Some modals do not fully trap focus.
- Some custom modals close on Escape, but focus return is not consistently handled.
- Notification dropdowns manage `aria-expanded`, but panel roles/keyboard navigation could be improved.
- Dynamic toast messages should consistently use `role="alert"` or `aria-live`.
- Some clickable cards use anchors correctly, but some button-like controls need clearer labels.
- Color-coded statuses should not rely only on color; most have text, but this should be checked screen by screen.

Specific examples:

- Staff confirm modal focuses the confirm button, but does not restore focus to the triggering control.
- Admin report modal closes with Escape/backdrop, but does not trap focus.
- Toast systems are inconsistent: some use `role="alert"`, some are manually created without consistent live-region behavior.
- Corrupted icon text can be noisy for assistive technology.

Cleanup recommendation:

1. Add focus return to modal close flows.
2. Consider focus trapping for report review and confirmation modals.
3. Standardize toast accessibility with `role="alert"` and `aria-live`.
4. Review icon-only buttons for `aria-label`.
5. Replace mojibake icons with accessible SVG/icon components.

## Responsive Layout Concerns

Known responsive risks:

- Large tables appear throughout the app and depend on scroll wrappers.
- Some inline styles use fixed/min widths that may be tight on small screens.
- Report preview/edit tables have many columns and can be difficult on mobile.
- Admin report modal has a preview plus sidebar layout that may be hard on narrow screens.
- Notification cards use truncation and fixed avatar/action areas.
- Staff report forms use table-like report layouts that are naturally wide.

Screens to test carefully:

- Staff create report form.
- Staff report detail/edit screen.
- Admin report review modal.
- Admin users table and modal.
- PH reminders two-column layout.
- Super admin authenticator access table.
- Audit log table/details rows.

Cleanup recommendation:

1. Test all primary screens at mobile width, tablet width, and desktop width.
2. Keep wide tables inside explicit scroll containers.
3. Consider stacked mobile layouts for report forms and review modal sidebar.
4. Avoid adding more fixed widths in inline styles.

## Frontend / Backend Route Coupling

Many frontend behaviors depend directly on named routes, hardcoded URL templates, or backend-provided payloads.

Examples:

- `resources/views/admin/users.blade.php`
  - Form stores `data-store-action`.
  - Form stores `data-update-template="{{ url('/dashboard/users/__USER__') }}"`.
- `resources/views/admin/partials/reports-table.blade.php`
  - Row payload includes `status_url`, `download_url`, entries, status labels, and signature URL.
- `public/js/admin-reports.js`
  - Builds PDF export URL as `/dashboard/admin/reports/{id}/export-pdf`.
- `resources/views/admin/notifications.blade.php`
  - Review links use `route('dashboard.admin') . '?open_report={id}'`.
- `resources/views/partials/navbar-staff.blade.php`
  - Fetches notification list/read routes from server-generated route names.
- Staff report screens build route names using staff portal prefix.

Risk:

- Backend route renames can silently break frontend behavior.
- Payload shape changes can break modals.
- Hardcoded URLs are harder to refactor than named routes rendered from Blade.

Cleanup recommendation:

1. Prefer server-rendered route URLs in data attributes over hardcoded JS URL construction.
2. Keep row payload keys documented when JS expects them.
3. Add integration/feature tests around route-driven modals.
4. Avoid building route paths directly inside public JS when named routes can be rendered into Blade.

## Data Attribute Dependencies Between Blade And JS

The frontend relies heavily on `data-*` attributes as the contract between Blade markup and JavaScript.

Important dependency groups:

- Topbar notifications:
  - `[data-notification-menu]`
  - `[data-notification-toggle]`
  - `[data-notification-panel]`
- Search/filter:
  - `[data-search-filter-form]`
  - `[data-live-search]`
  - `[data-live-filter]`
  - `[data-date-input]`
  - `[data-quick-filter-input]`
  - `[data-quick-filter-button]`
- User modal:
  - `[data-user-modal]`
  - `[data-open-user-modal]`
  - `[data-user-form]`
  - `[data-role-radio]`
  - `[data-field]`
  - `[data-combined-name]`
- Admin report modal:
  - `[data-admin-dashboard]`
  - `[data-reports-body]`
  - `[data-report-row]`
  - `[data-open-report-modal]`
  - `[data-report-modal]`
  - `[data-review-choice]`
  - `[data-approve-button]`
  - `[data-return-button]`
- Staff confirm modal:
  - `[data-staff-confirm-modal]`
  - `[data-staff-confirm-title]`
  - `[data-staff-confirm-message]`
  - `[data-staff-confirm-submit]`
- Profile preview:
  - `[data-avatar-input]`
  - `[data-avatar-preview]`
  - `[data-signature-input]`
  - `[data-signature-preview]`
- OTP code input:
  - `[data-otp-form]`
  - `[data-otp-hidden]`
  - `[data-otp-input]`
- Audit log:
  - `[data-audit-toggle]`
  - `data-target`

Risk:

- Renaming markup can silently disable behavior.
- Some scripts exit quietly when selectors are missing, which prevents obvious errors but can hide broken interactions.
- Multiple scripts may depend on the same selectors, especially notification selectors.

Cleanup recommendation:

1. Treat `data-*` attributes used by JS as API contracts.
2. Document selector changes in the same PR as JS changes.
3. Add focused browser tests for modal, dropdown, OTP, and filter behaviors.
4. Use consistent naming prefixes by feature, such as `data-report-*`, `data-user-*`, and `data-notification-*`.

## Suggested Cleanup Order

1. Fix missing `public/css/index.css` reference.
2. Fix mojibake characters in UI-visible text and JS icons.
3. Standardize duplicate CDN versions and remove duplicate includes.
4. Decide the long-term navigation strategy: shared topbar vs separate staff navbar.
5. Extract staff confirm modal JS and staff notification JS.
6. Extract report editor JS from staff create/show screens.
7. Improve modal accessibility and focus return.
8. Replace hardcoded frontend route paths with Blade-rendered route URLs.
9. Add browser-level tests for the `data-*` behavior contracts.
