# Navigation, Notifications, Search, And Filter Behavior

This document explains frontend behavior for navigation/topbars, notification dropdowns, and search/filter forms.

## Navigation / Topbar Behavior

Main files:

- Blade component: `resources/views/components/topbar.blade.php`
- JavaScript: `public/js/topbar.js`
- Styles: `public/css/shared-navbar.css`
- Component data provider: `app/View/Components/Topbar.php`

Where it appears:

- Admin dashboard pages.
- PH admin pages.
- Super admin report/authenticator/notification pages.
- Shared home page for admin/super admin users.

What the Blade renders:

- DICT and Bagong Pilipinas logos.
- Role-aware navigation links.
- Notification trigger button.
- Notification panel.
- Profile edit icon.

Topbar links are rendered conditionally:

- `Home` always points to `route('dashboard.home')`.
- `Dashboard` points to `route('dashboard')`.
- `Reports` appears for super admin navigation and uses `$reportsRoute`.
- `Reminders` appears when `$canManageReminders` is true.
- `Users` points either to PH admin office users or super admin user management, depending on permissions.
- `Authenticator Access` appears when `$canManageAuthenticatorAccess` is true.
- `Audit Log` appears when `$canAccessAudit` or admin navigation is true.
- Profile icon points to `route('profile.edit')`.

Topbar dropdown behavior in `topbar.js`:

1. The script finds every `[data-notification-menu]`.
2. If no menus exist, the script exits.
3. Clicking `[data-notification-toggle]` stops event propagation.
4. The script checks `aria-expanded` to decide whether the clicked menu is already open.
5. It closes all menus first.
6. If the clicked menu was closed, it sets `aria-expanded="true"` and removes `hidden` from `[data-notification-panel]`.
7. Clicking inside the panel stops propagation so the document click handler does not close it.
8. Clicking anywhere else in the document closes all panels.
9. Pressing Escape closes all panels.

Important selectors:

- `[data-notification-menu]`
- `[data-notification-toggle]`
- `[data-notification-panel]`

Important accessibility behavior:

- The notification toggle keeps `aria-expanded` synced with panel visibility.
- The panel is hidden with the native `hidden` attribute.

## Staff/Intern Notification Dropdown

Main file:

- `resources/views/partials/navbar-staff.blade.php`

Where it appears:

- Included by `resources/views/staff/layouts/app.blade.php`.

Data sources:

- Office reminders.
- Staff report review notifications.
- Staff route prefix from the current staff/intern portal.

Rendered notification types:

- Office reminder notifications.
- Approved report notifications.
- For-revision report notifications.

Frontend process:

1. The navbar renders the bell/menu UI and initial notification list.
2. The inline script waits for `DOMContentLoaded`.
3. The script wires click behavior for the notification toggle.
4. When the dropdown opens, it can call the staff notification index route through `fetch()`.
5. The response updates the notification list and unread badge/count.
6. Clicking a notification can call the read route through `fetch()`.
7. After marking read, the script navigates to the target URL with `window.location.href`.
8. Outside clicks close the dropdown.

Important route dependencies:

- `route($staffPortalPrefix . '.notifications.index')`
- `route($staffPortalPrefix . '.notifications.read')`

Important JavaScript behaviors:

- Uses CSRF-protected `fetch()` requests.
- Uses `Accept: application/json`.
- Uses DOM rebuilding for refreshed notification items.
- Keeps the user's click action connected to notification read tracking before redirecting.

Important risk:

- This navbar handles notification behavior separately from `public/js/topbar.js`, so staff navigation behavior is partly duplicated outside the shared topbar component.

## Admin/Super Admin Notification Dropdown

Main files:

- `resources/views/components/topbar.blade.php`
- `public/js/topbar.js`
- `app/View/Components/Topbar.php`

Admin behavior:

1. The topbar shows a bell icon with an unread badge when pending notification count is greater than zero.
2. The panel title is `Notifications`.
3. Admin notification copy describes latest report submissions and review alerts.
4. Pending submission items link to report review routes.
5. The `View all` link appears when the user can view notifications.

Super admin behavior:

1. The topbar shows a super admin unread count when `superAdminUnreadCount` is greater than zero.
2. The notification panel describes summarized alerts for super admin oversight.
3. Each notification item shows title, message, type, time, read/unread visual state, and optional action link.
4. The full notification center is handled by `resources/views/super_admin/notifications/index.blade.php`.

Dropdown open/close behavior:

- Same `topbar.js` behavior described above.
- The script does not fetch new notification data; it only toggles already-rendered panel content.

Important distinction:

- Admin/super admin dropdown content is rendered server-side in the topbar component.
- Staff dropdown has extra inline JavaScript for refreshing and marking notifications read.

## Search And Filter Forms

Main shared file:

- `public/js/search-filter.js`

Screens using shared search/filter behavior:

- User management screen.
- PH users screen.
- Some report/admin filter forms.

Important selectors:

- `[data-search-filter-form]`
- `[data-live-search]`
- `[data-live-filter]`
- `[data-date-input]`
- `[data-quick-filter-input]`
- `[data-quick-filter-button]`

Shared behavior:

1. The script finds all forms marked `[data-search-filter-form]`.
2. If no forms exist, it exits.
3. Search input changes are debounced for 320 milliseconds.
4. After the debounce, the form submits through `form.requestSubmit()` when available.
5. If `requestSubmit()` is not available, it falls back to `form.submit()`.
6. Select filter changes submit immediately.
7. Date input changes clear the quick-filter hidden input and remove active classes from quick filter buttons.
8. Quick filter button clicks set the hidden quick-filter value from `data-quick-filter-value` and submit the form.

Screen-specific search/filter behavior:

- Admin reports also use `public/js/admin-reports.js` for client-side row filtering after the server response loads.
- Staff dashboard uses a plain GET form for search/status preservation and custom inline checkbox behavior.
- Staff reports list uses a plain GET form for searching report records.
- Audit log filter form submits role, activity, and date filters by GET and uses `audit-log.js` only for row details toggling.

Important implementation detail:

- These forms are mostly server-side filters. JavaScript improves speed and ergonomics, but the actual filtered dataset comes from a new GET request.
