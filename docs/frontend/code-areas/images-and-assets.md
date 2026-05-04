# Images And Static Assets

This document maps image/static assets used by the frontend.

## Public Image Files

### `public/images/dict_logo.png`

Used by:

- `resources/views/auth/signin.blade.php`
- `resources/views/auth/verify-2fa.blade.php`
- `resources/views/partials/homepage-content.blade.php`
- `resources/views/partials/navbar-staff.blade.php`
- `resources/views/partials/navbar-admin.blade.php`
- `resources/views/components/topbar.blade.php`

Purpose:

- DICT logo in auth screens, navbars, and shared homepage hero.

### `public/images/bagong_pilipinas.png`

Used by:

- `resources/views/auth/signin.blade.php`
- `resources/views/auth/verify-2fa.blade.php`
- `resources/views/partials/navbar-staff.blade.php`
- `resources/views/partials/navbar-admin.blade.php`
- `resources/views/components/topbar.blade.php`

Purpose:

- Bagong Pilipinas branding alongside DICT logo.

### `public/images/HEADER.png`

Used by:

- `resources/views/staff/reports/createReport.blade.php`
- `resources/views/staff/reports/show.blade.php`

Purpose:

- Header image for Daily Accomplishment Report forms and report detail/edit screen.

### `public/images/default.png`

Current note:

- Present in `public/images`.
- No current Blade reference was found in the scan.

Potential purpose:

- Default avatar or fallback image.

## Favicon

### `public/favicon.ico`

Purpose:

- Browser favicon served from the public root.

Current note:

- The active layouts do not explicitly link this favicon, but browsers can request `/favicon.ico` by convention.

## Uploaded Media Assets

The UI also displays user-uploaded profile and signature images.

Access paths:

- `route('media.public', ['path' => ltrim($path, '/')])`
- `asset('storage/' . $path)` in staff profile fallback logic.

Used by:

- Admin reports table for staff avatars/signatures.
- Super admin reports table for staff avatars/signatures.
- Admin notifications for staff avatars.
- User management table for user avatars.
- PH users table for user avatars.
- Admin profile screen.
- Staff profile screen.

Important details:

- Report preview modal displays signature images through `[data-preview-signature]`.
- Profile edit screens preview selected image files in the browser before upload.
- Public media access depends on backend `media.public` route behavior documented in backend docs.

## Resource Assets

### `resources/css/app.css`

Vite-managed CSS source.

Current content role:

- Imports Tailwind.
- Defines theme/source paths.

### `resources/js/app.js`

Vite-managed JavaScript entry.

Current content role:

- Imports `./bootstrap`.

### `resources/js/bootstrap.js`

Vite-managed bootstrap file.

Current content role:

- Imports Axios.
- Sets the default AJAX header.

## Missing Or Suspicious Asset References

### `public/css/index.css`

Referenced by:

- `resources/views/home_page.blade.php`
- `resources/views/staff/layouts/app.blade.php`

Current status:

- The file was not present in the current `public/css` inventory.

Impact:

- Requests for `css/index.css` may return 404.
- Any intended styles from that file will not load.

### `public/css/homepage.css`

Current status:

- Present in `public/css`.
- No current active Blade reference was found in the scan.

Impact:

- May be legacy/unused unless loaded by a route or view not scanned.

### `public/css/sign.css`

Current status:

- Present in `public/css`.
- The active sign-in screen uses inline CSS instead of this file.

Impact:

- May be legacy/unused.
