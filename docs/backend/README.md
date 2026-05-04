# Backend Documentation

This folder documents the backend by process first, then by function. Each process document should explain the route entry points, controller methods, service methods, validation rules, database tables, model behavior, and known risks.

## Process Documents

1. [Staff and Intern Report Workflow](report-workflow.md)
2. [Authentication, 2FA, Logout, and Session Middleware](auth-and-session-flow.md)
3. [Admin Report Review and Super Admin Monitoring](admin-report-review-flow.md)
4. [User Management, Profile, and Audit Log](user-profile-audit-flow.md)
5. [Staff, Admin, and Super Admin Notifications](notifications-flow.md)
6. [Provincial Head Assignment and Reminder Flow](provincial-flows.md)
7. [Health Check, Database Reconnect, and Public Media](health-and-media-flow.md)

## Core Backend Flow Checklist

- [x] Authentication, OTP, logout, and 2FA flow
- [x] Staff/intern session and role middleware flow
- [x] Admin report review flow
- [x] Super Admin dashboard/report monitoring flow
- [x] User management flow
- [x] Staff/intern profile update flow
- [x] Admin/super admin profile update flow
- [x] Audit log flow
- [x] Staff notification flow
- [x] Admin notification flow
- [x] Super Admin notification flow
- [x] Provincial Head assignment flow
- [x] Provincial reminder schedule/send-now flow
- [x] Health check and database reconnect flow
- [x] Public media access flow

## Still To Document

- [x] Users table schema
- [x] Reports table schema
- [x] Report entries table schema
- [x] Activity logs table schema
- [x] Super admin notifications table schema
- [x] Office reminder schedules table schema
- [x] Office reminders table schema
- [x] Sessions/cache/jobs tables
- [x] Migration history and purpose of each migration
- [x] `routes/web.php`
- [x] Controllers and function reference
- [x] Services reference
- [x] Middleware reference
- [x] Form request validation reference
- [x] Models and relationships map
- [x] Helpers reference
- [x] Support classes reference
- [x] Mail classes reference
- [x] View components reference
- [x] Providers reference
- [x] Staff feature regression tests
- [x] Authentication flow tests
- [x] Authenticator / 2FA tests
- [x] Admin notification tests
- [x] Provincial reminder flow tests
- [x] Access control tests
- [x] Gaps where behavior has no tests
- [x] Unused or legacy controllers
- [x] Duplicate/legacy routes
- [x] Functions not connected to routes
- [x] Role/session edge cases
- [x] Database columns with unclear ownership
- [x] Service methods that need ownership checks
- [x] Existing docs that need updating

## Code Area References

1. [Routes Web Reference](code-areas/routes-web.md)
2. [Controllers Reference](code-areas/controllers.md)
3. [Services Reference](code-areas/services.md)
4. [Middleware Reference](code-areas/middleware.md)
5. [Form Requests and Validation Reference](code-areas/validation.md)
6. [Models and Relationships Reference](code-areas/models-and-relationships.md)
7. [Helpers, Support, Mail, View Components, and Providers Reference](code-areas/helpers-support-mail-components-providers.md)

## Database References

1. [Database Schema](database/schema.md)
2. [Migration History](database/migration-history.md)

## Test References

1. [Test Coverage Map and Gaps](tests/coverage-map.md)

## Cleanup References

1. [Known Risks and Cleanup Notes](cleanup/known-risks.md)
