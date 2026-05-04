# Auth And Home Screen Flows

This document covers the frontend screens that users see before or immediately after authentication.

## Sign-In Screen

- View: `resources/views/auth/signin.blade.php`
- Layout: `resources/views/super_admin/layouts/app.blade.php`
- Route behavior: the form posts to the current URL because the form action is empty. In the current auth routes this is the login/send-OTP entry route.
- Main assets: Bootstrap 5.3.3 CDN, Manrope Google Font, inline CSS.
- Main form fields: `email`, CSRF token.
- Main JavaScript: inline `DOMContentLoaded` handler disables the submit button on form submit and changes the button label to `Checking account...`.

Process:

1. The screen renders a two-panel login page with DICT and Bagong Pilipinas branding.
2. The user enters an email address.
3. Laravel validation feedback appears from `session('status')`, `session('error')`, and `$errors->first('email')`.
4. On submit, the frontend disables the send button to prevent duplicate requests.
5. The backend decides whether the user can proceed to Google Authenticator verification.

Important details:

- The visible title is `DICT Sign In`.
- The page uses `body_class` value `super-admin-signin-page`.
- There is no separate public JavaScript file for this screen.
- The CSS is embedded directly in the Blade file, so design changes must be made in the view unless the styles are extracted later.

## Google Authenticator / 2FA Verification Screen

- View: `resources/views/auth/verify-2fa.blade.php`
- Layout: standalone full HTML page.
- Form action: `route('auth.2fa.verify')`
- Main assets: `public/css/verify-otp.css`, `public/js/verify-otp.js`, Poppins Google Font.
- Main form fields: hidden `code`, CSRF token.

Process:

1. The screen shows the DICT and Bagong Pilipinas logos with the title `Google Authenticator`.
2. The user enters a six-digit authenticator code into six separate visible digit inputs.
3. `verify-otp.js` syncs the six visible inputs into the hidden `code` field.
4. The script accepts only digits, moves focus forward after each digit, moves focus backward on backspace, and supports pasting a full code.
5. The submitted code is posted to `auth.2fa.verify`.
6. Session and validation errors render above the form.

Important details:

- The screen displays the authenticated/pending user email in the helper text.
- `verify-otp.js` contains optional resend timer support using `window.otpConfig.resendAvailableAt`, `[data-otp-timer]`, and `[data-resend-button]`; this 2FA view does not currently render those timer/resend elements, so that code path is dormant here.
- The visible inputs use `data-otp-input`; the hidden input uses `data-otp-hidden`.

## Shared Home Page

- View: `resources/views/home_page.blade.php`
- Shared content partial: `resources/views/partials/homepage-content.blade.php`
- Staff navbar: `resources/views/partials/navbar-staff.blade.php`
- Admin/super admin navbar: `resources/views/components/topbar.blade.php`
- Main assets: `shared-navbar.css`, `shared-homepage.css`, AOS CDN, Font Awesome kit, Bootstrap on the staff branch.

Process:

1. The controller passes the current user, role, and dashboard target.
2. The Blade determines whether the current user is staff/intern/special access or admin/super admin.
3. Staff, intern, and special access users get the staff navbar and a dashboard link based on their staff portal route prefix.
4. Admin and super admin users get the `<x-topbar>` component and a dashboard link to `route('dashboard')`.
5. Both branches render the same homepage content partial.

Visible content:

- Hero headline: `Daily Accomplishment Records System`
- Primary action: `Get Started`
- Feature cards: Easy Report Submission, Real-Time Monitoring, Automated Summary Reports, Secure Access.

Important details:

- Staff branch includes `css/index.css`, but that file is currently missing from `public/css`.
- Staff branch loads Bootstrap bundle twice.
- The shared page initializes AOS animations when available.
- The shared topbar also owns notification dropdown behavior through `public/js/topbar.js`.
