# Backend Documentation: Provincial Head Assignment and Reminder Flow

## Purpose

This document covers office-based Provincial Head assignment, report review scoping, daily reminder schedules, manual reminders, and reminder delivery to staff/intern notification lists.

Main files:

- `app/Services/ProvincialHeadAssignmentService.php`
- `app/Services/ProvincialReminderService.php`
- `app/Http/Controllers/Admin/ProvincialReminderController.php`
- `app/Support/ProvincialOffice.php`
- `app/Models/OfficeReminderSchedule.php`
- `app/Models/OfficeReminder.php`

## Provincial Head Assignment

### Supported Office Options

`ProvincialHeadAssignmentService::officeOptions()` returns `ProvincialOffice::all()`.

The validation message names these supported offices:

- La Union
- Ilocos Norte
- Ilocos Sur
- Pangasinan

## Resolve Provincial Head for Staff

`resolveProvincialHeadForStaff(User $staffUser)`:

1. Reads staff user's `office`.
2. Validates the office through `ProvincialOffice::isValid()`.
3. Finds the first active user where:
   - `role = ph-admin`
   - `status = active`
   - `office = staff office`
4. Throws validation error if office is invalid.
5. Throws validation error if no active Provincial Head is assigned.
6. Returns the Provincial Head user.

This method is called during report submission by `ReportWorkflowService::submitReport()`.

## Managed User Assignment Validation

`ensureValidManagedUserAssignment()` is used when creating/updating managed users.

Rules:

- Only applies to `ph-admin`, `staff`, and `interns`.
- Office must be one of the supported provincial offices.
- Active `ph-admin` users must be unique per office.
- Updating a PH Admin excludes the target user from the duplicate check.

## Report Review Scoping

`canReviewReport()`:

- `admin` can review any report.
- `ph-admin` can review reports explicitly assigned to them.
- If a report has no explicit assignment, PH Admin can review when report owner's office equals their office.

`scopeReportsForReviewer()`:

- Non-PH Admin reviewers receive the original query.
- PH Admin reviewers see reports assigned to them or owned by users in their office.

## Reminder Routes

| Method | URI | Route name | Method | Purpose |
| --- | --- | --- | --- | --- |
| `GET` | `/dashboard/admin/reminders` | `admin.dashboard.reminders.index` | `index` | Show schedule and recent reminders |
| `POST` | `/dashboard/admin/reminders/schedule` | `admin.dashboard.reminders.schedule` | `saveSchedule` | Save daily reminder schedule |
| `POST` | `/dashboard/admin/reminders/send-now` | `admin.dashboard.reminders.send-now` | `sendNow` | Send manual reminder immediately |

Middleware: `role.session:ph-admin`.

## Reminder Index Process

1. `ProvincialReminderController::index()` requires authenticated `ph-admin`.
2. Dispatches any due reminders for the PH Admin's office.
3. Loads the current schedule for that office and creator.
4. Loads recent reminders for the office, paginated 5 per page.
5. Returns `admin.reminders`.

## Save Daily Schedule Process

1. Requires `ph-admin`.
2. Validates:
   - `message`: nullable string max 500
   - `send_time`: required `H:i`
   - `is_enabled`: nullable boolean
3. `ProvincialReminderService::saveDailySchedule()` runs in a transaction.
4. Deletes schedules for the same office created by other users.
5. Creates or updates the schedule for current office/current creator.
6. Normalizes blank message to default reminder text.
7. Saves `send_time` and `is_enabled`.

## Send Reminder Now Process

1. Requires `ph-admin`.
2. Validates nullable `message` max 500.
3. Creates an `office_reminders` row:
   - office: PH Admin office
   - message: normalized custom/default message
   - type: `manual`
   - triggered_at: now
   - created_by: PH Admin id
   - office_reminder_schedule_id: null

## Dispatch Due Reminders

`dispatchDueReminders(?string $office = null)`:

1. Finds enabled schedules where `send_time <= current time`.
2. Optionally scopes by office.
3. Ensures `last_sent_on` is null or before today.
4. For each schedule, starts a transaction.
5. Refreshes schedule to avoid stale state.
6. Creates an `OfficeReminder` with type `scheduled`.
7. Updates `last_sent_on` to today's date.
8. Returns number dispatched.

## Staff Reminder Notification Read

`reminderNotificationsForStaff()` dispatches due reminders for the staff user's office before returning reminders.

`unreadReminderCountForStaff()` also dispatches due reminders and counts reminders newer than `users.notifications_read_at`.

## Risks / Notes

- Due reminders dispatch lazily when reminder pages or staff notification endpoints are hit.
- Saving a schedule deletes other creators' schedules for the same office.
- The office uniqueness rule only prevents duplicate active PH Admins per office.
