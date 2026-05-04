# Modal, Report Review, Profile Preview, And OTP Behavior

This document explains interactive frontend behavior for user modals, confirmation modals, report review, profile image/signature preview, and Google Authenticator code input.

## User Modal Form Behavior

Main files:

- View: `resources/views/admin/users.blade.php`
- JavaScript: `public/js/dashboard.js`

Configuration source:

- The view embeds JSON in `<script id="dashboard-config" type="application/json">`.
- The next inline script assigns it to `window.dashboardConfig`.
- `dashboard.js` reads `window.dashboardConfig`.

Important config data:

- `userFormOptions`
- `initialRole`
- `oldValues`

Important selectors:

- `[data-user-modal]`
- `[data-open-user-modal]`
- `[data-close-user-modal]`
- `[data-user-form]`
- `[data-user-form-method]`
- `[data-user-modal-title]`
- `[data-role-radio]`
- `[data-field]`
- `[data-combined-name]`
- `[data-name-part]`

Create mode process:

1. User clicks the Add User button with `[data-open-user-modal]`.
2. `dashboard.js` calls `setCreateMode()`.
3. The form action is set to `data-store-action`.
4. The hidden method field is cleared.
5. Modal title becomes `Create New User`.
6. The form resets.
7. The default role radio is selected from `initialRole`.
8. `applyRole()` shows/hides fields based on role config.
9. Old validation values are reapplied when present.
10. The modal becomes visible.

Edit mode process:

1. User clicks an edit button with `data-mode="edit"` and `data-user`.
2. `dashboard.js` parses the JSON user payload.
3. The form action is built from `data-update-template` by replacing `__USER__` with the user id.
4. The hidden method field becomes `PUT`.
5. Modal title becomes `Edit User`.
6. Name, email, position, institution, and role-specific values are filled.
7. The matching role radio is selected.
8. `applyRole()` refreshes role-specific fields and options.

Name behavior:

- First, middle, and last name inputs are combined into a hidden `name` input.
- `syncCombinedName()` runs on name input changes.
- The form runs `syncCombinedName()` again on submit.

Role behavior:

- `applyRole(roleValue, values)` looks up the role config.
- Fields not included in that role config are hidden and cleared.
- Visible fields are marked required when the role config requires them.
- Project, bureau, division, and office selects are rebuilt from role-specific option arrays.

Archive/restore behavior:

- Buttons with `[data-confirm-trigger]` point to hidden forms by `data-form-id`.
- The shared confirmation modal appears.
- Confirm submits the pending hidden form.

## Confirm Modal Behavior

There are two main confirm modal systems.

### Shared/Admin Confirm Modal

Main files:

- Markup: `resources/views/components/confirm-modal.blade.php`
- Driver for user screen: `public/js/dashboard.js`
- Additional custom delete modal: `resources/views/admin/reports.blade.php`

Important selectors:

- `[data-confirm-modal]`
- `[data-confirm-title]`
- `[data-confirm-message]`
- `[data-confirm-cancel]`
- `[data-confirm-submit]`
- `[data-confirm-trigger]`

User-management process:

1. A button with `[data-confirm-trigger]` is clicked.
2. The script finds the hidden form by `data-form-id`.
3. Modal title/message are filled from button data or defaults.
4. Modal gets `is-visible`.
5. Cancel hides the modal and clears the pending form.
6. Confirm submits the pending form.
7. Clicking the backdrop closes the modal.

### Staff Confirm Modal

Main files:

- Markup: `resources/views/staff/layouts/confirm-modal.blade.php`
- Driver: inline script in `resources/views/staff/layouts/app.blade.php`

Global API:

```js
window.openStaffConfirmModal({
    title: 'Submit Report',
    message: 'Are you sure?',
    confirmText: 'Submit',
    cancelText: 'Cancel',
    variant: 'success',
    onConfirm: function () {}
});
```

Process:

1. A staff page calls `window.openStaffConfirmModal(options)`.
2. The layout fills the title, message, button text, and icon/button variant.
3. Body scroll is locked.
4. Modal is shown and the confirm button receives focus.
5. Cancel, backdrop, or Escape closes the modal.
6. Confirm closes the modal, then runs `onConfirm`.

Supported variants:

- Default/primary.
- `danger`.
- `success`.

Used by:

- Staff dashboard bulk delete.
- Staff dashboard PDF export.
- Staff reports list delete.
- Staff report detail submit.
- Staff profile sign out.

## Report Review Modal Behavior

Main files:

- View host: `resources/views/admin/reports.blade.php`
- Admin table partial: `resources/views/admin/partials/reports-table.blade.php`
- Super admin table view: `resources/views/super_admin/reports-table.blade.php`
- Modal partial: `resources/views/admin/partials/reports-modal.blade.php`
- JavaScript: `public/js/admin-reports.js`

Important selectors:

- `[data-admin-dashboard]`
- `[data-dashboard-mode]`
- `[data-can-manage]`
- `[data-csrf-token]`
- `[data-auto-open-report-id]`
- `[data-reports-body]`
- `[data-report-row]`
- `[data-status-pill]`
- `[data-open-report-modal]`
- `[data-report-modal]`
- `[data-close-report-modal]`
- `[data-review-choice]`
- `[data-review-comment]`
- `[data-approve-button]`
- `[data-return-button]`
- `[data-review-feedback]`

Opening process:

1. Each report row button contains a JSON payload in `data-report`.
2. Clicking `[data-open-report-modal]` parses that payload.
3. `populateModal(report)` fills the modal title/subtitle, file name, user name, submitted date, signatory name, status, entries, and signature.
4. `openModal()` removes `hidden` from the modal and locks body scroll.

Preview rendering:

- Entries are rendered into `[data-preview-entries]`.
- Staff signature is rendered into `[data-preview-signature]`.
- Current status appears in `[data-modal-status-pill]` and `[data-preview-status-label]`.
- Download link appears only when the payload contains a `download_url`.
- PDF export link is built as `/dashboard/admin/reports/{id}/export-pdf`.

Review controls:

1. `resetControls(status)` clears radio choices, comments, saved comment panel, buttons, and feedback.
2. If the user can manage reports, radio options are active.
3. Choosing `approved` reveals the Approve Report button.
4. Choosing `for_revision` reveals the comment textarea and Return For Revision button.
5. If the report is already approved, approve/return controls are disabled/hidden.

AJAX review process:

1. Button click calls `submitReview(status)`.
2. The script builds a `URLSearchParams` payload.
3. Payload includes `_token` and `status`.
4. For revision, non-empty comment text is included as `comment`.
5. `fetch()` posts to `currentReport.status_url`.
6. Headers include:
   - `Accept: application/json`
   - `Content-Type: application/x-www-form-urlencoded; charset=UTF-8`
   - `X-Requested-With: XMLHttpRequest`
7. On success, the script updates the current report, table row status, status pill class/text, summary counts, modal status, saved comment panel, and visible filters.
8. On failure, review feedback becomes `Unable to save the review right now.`

Auto-open behavior:

- If `[data-admin-dashboard]` has `data-auto-open-report-id`, the script finds the matching row and opens its modal automatically.
- PH admin notification cards link to the dashboard with `?open_report={report_id}`, which feeds this behavior.

Closing behavior:

- Close button closes the modal.
- Clicking the modal backdrop closes it.
- Escape closes it.

## Profile Image / Signature Preview Behavior

Main files:

- Admin profile view: `resources/views/admin/edit-profile.blade.php`
- Staff profile view: `resources/views/staff/staff_profile.blade.php`
- JavaScript: `public/js/profile.js`

Important selectors:

- `[data-avatar-input]`
- `[data-avatar-preview]`
- `[data-avatar-placeholder]`
- `[data-signature-input]`
- `[data-signature-preview]`
- `[data-open-signout-modal]`
- `[data-signout-modal]`
- `[data-close-signout-modal]`

Preview process:

1. The script listens for file input changes.
2. It reads the first selected file.
3. If no file exists or the file type does not start with `image/`, it exits.
4. It creates an object URL with `URL.createObjectURL(file)`.
5. It finds the current preview image by selector.
6. If no preview image exists, it creates an `<img>`.
7. It sets the preview `src` to the object URL.
8. It removes the avatar placeholder when present.
9. It revokes the previous object URL when a new one replaces it.
10. On `beforeunload`, it revokes any remaining object URLs.

Auto-submit behavior:

- The options passed to `previewFile()` set `autoSubmit: true`.
- After a valid image is selected, `submitProfileForm()` calls `profileForm.requestSubmit()`.
- `profileForm` is selected as `.profile-update-shell`.

Important compatibility note:

- Some profile forms in the current Blade use class names like `.profile-form`, so the auto-submit behavior depends on whether `.profile-update-shell` exists on that screen.

Sign-out behavior:

- If the sign-out modal selectors exist, `profile.js` toggles `.is-visible`.
- Staff profile also has an inline sign-out script that prefers `window.openStaffConfirmModal`.

## OTP / 2FA / Google Authenticator Code Input Behavior

Main files:

- View: `resources/views/auth/verify-2fa.blade.php`
- JavaScript: `public/js/verify-otp.js`
- CSS: `public/css/verify-otp.css`

Important selectors:

- `[data-otp-form]`
- `[data-otp-hidden]`
- `[data-otp-input]`
- `[data-otp-timer]`
- `[data-resend-button]`

Input process:

1. The script finds `[data-otp-form]`.
2. If the form does not exist, it exits.
3. It finds the hidden code input and all visible digit inputs.
4. `syncOtpValue()` joins all visible digit values and writes them into the hidden input.
5. On each visible input event, non-digits are stripped.
6. Only the last typed digit is kept in the field.
7. After a digit is entered, focus moves to the next input and selects it.
8. Backspace on an empty input moves focus to the previous input.
9. Paste is intercepted, stripped to digits, limited to the number of available inputs, and distributed across the fields.
10. The hidden input is synced after paste.

Timer/resend behavior:

- The script can read `window.otpConfig.resendAvailableAt`.
- If timer node, resend button, and availability date all exist, it counts down every second.
- While time remains, resend button is disabled.
- When time reaches zero, timer displays `0:00` and resend button is enabled.

Current screen note:

- The Google Authenticator verification view renders the six code inputs and hidden code field.
- It does not currently render resend timer controls, so the timer/resend branch is inactive on that screen.
