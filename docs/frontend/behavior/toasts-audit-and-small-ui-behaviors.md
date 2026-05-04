# Toasts, Audit Expand/Collapse, And Small UI Behaviors

This document covers toast notification behavior, audit log expand/collapse behavior, and smaller page-specific interactions.

## Toast Notification Behavior

There are several toast/notification implementations in the frontend.

### `public/js/toast-notification.js`

Main global:

```js
window.toast = new ToastNotification();
```

Available methods:

- `window.toast.success(message, options)`
- `window.toast.error(message, options)`
- `window.toast.warning(message, options)`
- `window.toast.info(message, options)`
- `window.toast.show(message, options)`

Default options:

- `type`: `info`
- `title`: derived from type
- `duration`: `5000`
- `icon`: derived from type

Process:

1. The class constructor creates or reuses `#toast-container`.
2. If the container is missing, it is appended to `document.body`.
3. The script injects toast CSS into `<head>`.
4. `show()` creates a `.toast` element with icon, title, message, and close button.
5. The close button calls `hide(toast)`.
6. If `duration > 0`, a timeout hides the toast automatically.
7. `hide()` adds `.hide`, waits 300 milliseconds, then removes the toast from the DOM.

Supported types:

- `success`
- `error`
- `danger`
- `warning`
- `info`

Important note:

- Some icon characters in this file currently show mojibake/encoding artifacts in the source.

### Admin Dashboard Toasts

Main file:

- `resources/views/admin/dashboard.blade.php`

Behavior:

- Flash messages from `session('user_status')` or `session('user_error')` render as a fixed bottom-right toast.
- Success uses `.toast-popup--success`.
- Error uses `.toast-popup--error`.
- Inline JavaScript removes `#dashboardToast` after five seconds.
- Close button removes the toast immediately.

### User Management Toasts

Main file:

- `resources/views/admin/users.blade.php`

Behavior:

- Flash messages from `session('user_status')` or `session('user_error')` render in the dashboard content.
- Inline JavaScript removes `#dashboardToast` after five seconds.

### PH Admin Reminder Toasts

Main file:

- `resources/views/admin/reminders.blade.php`

Behavior:

1. `toast-notification.js` is loaded.
2. If `session('status')` exists, inline JavaScript manually creates a green success toast.
3. If there is a validation error, inline JavaScript manually creates a red error toast.
4. Success toast auto-dismisses after four seconds with a slide-out animation.
5. Error toast auto-dismisses after five seconds.
6. Inline keyframes `toastSlideIn` and `toastSlideOut` are defined in the Blade view.

Important distinction:

- Even though `toast-notification.js` is loaded, this page manually builds custom toast DOM instead of calling `window.toast.success()` or `window.toast.error()`.

### Component Flash Notifications

Main files:

- `resources/views/components/notifications.blade.php`
- `resources/views/components/alert-notification.blade.php`

Behavior:

- Session flash keys are converted to `<x-alert-notification>`.
- The wrapper positions alerts in the top-right corner.
- Alerts use Bootstrap alert classes.

Important code note:

- `alert-notification.blade.php` appears to have a missing opening `<script>` tag in the auto-dismiss block.

## Audit Log Expand/Collapse Behavior

Main files:

- View: `resources/views/admin/audit-log.blade.php`
- JavaScript: `public/js/audit-log.js`

Important selectors:

- `[data-audit-toggle]`
- `data-target`
- `aria-expanded`
- Detail rows with matching `id`

Process:

1. The audit log table renders each visible activity row.
2. Each row has a Details button with `[data-audit-toggle]`.
3. Each Details button has `data-target` pointing to a hidden detail row id.
4. `audit-log.js` finds all audit toggles.
5. If none exist, it exits.
6. Clicking a toggle finds the matching detail row by id.
7. The script checks whether the toggle has `aria-expanded="true"`.
8. It flips `aria-expanded` between `true` and `false`.
9. It sets the detail row's `hidden` state to the opposite of expanded.

Visible detail content:

- User.
- Role.
- Action.
- Status.
- Date.
- Time.
- Description.
- IP address and device, except in PH admin mode.

Important accessibility detail:

- The button includes `aria-controls` pointing to the detail row.
- The script keeps `aria-expanded` synced with visibility.

## Search Result And Table Selection Behaviors

### Staff Dashboard Bulk Selection

Main file:

- `resources/views/staff/dashboard.blade.php`

Behavior:

- Select-all checkbox toggles all row checkboxes.
- Individual checkbox changes update select-all checked/indeterminate state.
- Delete Selected button is disabled until at least one row is selected.
- Bulk delete submit is intercepted and confirmed through `window.openStaffConfirmModal`.

### Admin Report Bulk Delete Selection

Main file:

- `resources/views/admin/reports.blade.php`

Behavior:

- Select-all checkbox toggles non-disabled report checkboxes.
- Only approved report rows are selectable.
- Delete Selected button is disabled until at least one row is selected.
- Bulk delete form submit opens a custom delete-confirm modal.
- Confirm submits the bulk delete form.
- Cancel, backdrop, and Escape close the delete-confirm modal.

## Staff Report Form Behaviors

### Create Report Dynamic Rows

Main file:

- `resources/views/staff/reports/createReport.blade.php`

Behavior:

- Add Row appends another report entry row.
- The next row date defaults to the day after the previous row's end/start date.
- Textareas resize to content.
- Date changes regenerate the file name.
- The generated file name is written to a hidden input and readonly display field.
- Draft data is saved to localStorage.
- Submit validates that a generated file name exists.

### Report Detail Dynamic Rows And Submit

Main file:

- `resources/views/staff/reports/show.blade.php`

Behavior:

- Existing rows can be edited unless report is approved.
- Add Row appends new blank entry fields.
- Textareas resize to content.
- Save button submits the update form.
- Submit Report button uses staff confirmation modal.
- PDF export is disabled for pending and for-revision reports.

## Staff Reports List Modal/Delete Behavior

Main file:

- `resources/views/staff/reports/index.blade.php`

Behavior:

- Edit button opens Bootstrap modal.
- Modal reads report id/file/action data from the clicked button.
- Edit form action is set from the update URL template.
- Submit button changes to a saving state.
- Delete buttons create temporary DELETE forms.
- Delete action is confirmed through staff confirmation modal.
- Draft localStorage key is removed when the server sets `clear_report_draft`.

## Sign-In Submit Guard Behavior

Main file:

- `resources/views/auth/signin.blade.php`

Behavior:

- Submit button starts enabled.
- On form submit, button is disabled.
- Button text changes to `Checking account...`.
- This prevents accidental duplicate submission while the backend processes the email.
