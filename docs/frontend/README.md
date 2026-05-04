# Frontend Documentation

This folder documents the Laravel Blade frontend by screen, layout, component, and asset file. The app uses Blade views heavily, with most CSS and JavaScript loaded from `public/css` and `public/js`. Vite is configured, but the current interactive UI mostly depends on direct public assets and inline Blade scripts.

## Frontend Documentation Checklist

### Structure And Assets

- [x] Frontend architecture overview
- [x] Blade view inventory
- [x] Layout inventory
- [x] Component and partial inventory
- [x] CSS asset inventory
- [x] JavaScript asset inventory
- [x] Vite/resource asset notes

### Screen Flows

- [x] Sign-in and Google Authenticator verification screens
- [x] Shared home page
- [x] Staff/intern dashboard
- [x] Staff/intern reports list
- [x] Staff/intern create report form
- [x] Staff/intern report detail/edit screen
- [x] Staff/intern profile screen
- [x] Admin/super admin dashboard
- [x] Admin/super admin reports table and review modal
- [x] User management screen
- [x] Audit log screen
- [x] PH Admin reminders screen
- [x] PH Admin notifications screen
- [x] Super Admin authenticator access screen
- [x] Super Admin notifications screen
- [x] Error pages
- [x] Email templates

### Frontend Code Areas

- [x] Layouts
- [x] Blade components
- [x] Partials
- [x] Public CSS files
- [x] Public JavaScript files
- [x] Inline scripts inside Blade views
- [x] External CDN dependencies
- [x] Images/assets

### Frontend Behavior

- [x] Navigation/topbar behavior
- [x] Staff/intern notification dropdown
- [x] Admin/super admin notification dropdown
- [x] Search and filter forms
- [x] User modal form behavior
- [x] Confirm modal behavior
- [x] Report review modal behavior
- [x] Profile image/signature preview behavior
- [x] OTP/2FA code input behavior / Google Authenticator code input behavior
- [x] Toast notification behavior
- [x] Audit log expand/collapse behavior

### Frontend Risks / Cleanup Notes

- [x] Inline CSS that could be extracted
- [x] Inline JS that could be extracted
- [x] Missing `public/css/index.css`
- [x] Duplicate CDN/script/style includes
- [x] Mojibake/encoding artifacts in UI text
- [x] Mixed navigation implementations
- [x] Accessibility concerns
- [x] Responsive layout concerns
- [x] Frontend/backend route coupling
- [x] Data attribute dependencies between Blade and JS

## Current Documents

1. [Frontend Architecture Overview](architecture-overview.md)
2. [Auth And Home Screen Flows](screen-flows/auth-and-home-screens.md)
3. [Staff And Intern Screen Flows](screen-flows/staff-screens.md)
4. [Admin And Super Admin Report Screen Flows](screen-flows/admin-report-screens.md)
5. [Admin Management And Notification Screen Flows](screen-flows/admin-management-and-notifications.md)
6. [Error Pages And Email Templates](screen-flows/errors-and-email-templates.md)
7. [Layouts, Blade Components, And Partials](code-areas/layouts-components-partials.md)
8. [Public CSS And JavaScript Files](code-areas/public-css-and-javascript.md)
9. [Inline Scripts And External CDN Dependencies](code-areas/inline-scripts-and-cdns.md)
10. [Images And Static Assets](code-areas/images-and-assets.md)
11. [Navigation, Notifications, Search, And Filter Behavior](behavior/navigation-notifications-and-search.md)
12. [Modal, Report Review, Profile Preview, And OTP Behavior](behavior/modals-review-profile-and-otp.md)
13. [Toasts, Audit Expand/Collapse, And Small UI Behaviors](behavior/toasts-audit-and-small-ui-behaviors.md)
14. [Frontend Risks And Cleanup Notes](cleanup/risks-and-cleanup-notes.md)
