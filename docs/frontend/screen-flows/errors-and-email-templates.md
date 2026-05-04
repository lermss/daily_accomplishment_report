# Error Pages And Email Templates

This document covers the frontend-facing error views and email templates.

## Generic Error Page

- View: `resources/views/errors/generic.blade.php`
- Layout: standalone full HTML page.
- Main assets: Bootstrap 5.3.2 CDN, Bootstrap Icons CDN, inline CSS.
- Expected variables: `$code`, `$title`, `$message`.

Process:

1. The page displays the error code, icon, title, and message.
2. The Try Again button calls `location.reload()`.
3. The Back to Login link routes to `route('login')`.
4. The footer shows the error code and current server timestamp.

Fallbacks:

- Code fallback: `500`
- Title fallback: `Server Error`
- Message fallback: `An unexpected error occurred. Please try again later.`

## Database Error Page

- View: `resources/views/errors/database-error.blade.php`
- Layout: standalone full HTML page.
- Main assets: Bootstrap 5.3.2 CDN, Bootstrap Icons CDN, inline CSS.
- Used by: `app/Http/Middleware/CheckDatabaseConnection.php`

Process:

1. The page displays `Service Temporarily Unavailable`.
2. The copy explains that the app cannot connect to the database server.
3. The Try Again button reloads the current page.
4. The Back to Login link routes to `route('login')`.
5. The footer shows `DB_503` and current server timestamp.

Important details:

- This page is intended for temporary database connectivity failures.
- It does not rely on the normal app layout.

## Google Authenticator Provisioning Email

- HTML view: `resources/views/emails/google-authenticator-provisioning.blade.php`
- Text view: `resources/views/emails/google-authenticator-provisioning-text.blade.php`
- Mail class: `app/Mail/GoogleAuthenticatorProvisioningMail.php`
- Expected variables: `$qrImage`, `$manualSetupKey`, `$recipientEmail`.

HTML email process:

1. The email tells the user their DICT login has been authorized.
2. If `$qrImage` is present, it renders the QR code block.
3. It displays the manual setup key in a highlighted block.
4. It displays the authorized email.
5. It gives four setup steps: open Google Authenticator, add the account, go to sign-in, enter the current six-digit code.

Text email process:

1. The plain-text version states that Google Authenticator is now used directly.
2. It includes the authorized email and manual setup key.
3. It tells the user to scan the QR code in the HTML version or manually enter the key.
4. It tells the user to sign in with their authorized email and current six-digit authenticator code.

Important details:

- The QR image is rendered as raw HTML with `{!! $qrImage !!}`.
- The template uses inline styles for email-client compatibility.
- There are currently no other email templates under `resources/views/emails`.
